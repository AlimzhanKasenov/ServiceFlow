<template>

    <div class="modal">

        <div
            class="card"
            :class="'priority-card-' + (editablePriority || 'normal')"
        >

            <!-- HEADER -->

            <div class="header">

                <div class="title-row">

                    <input
                        v-model="editableTitle"
                        class="title-input"
                        placeholder="Название заявки"
                    />

                    <span
                        class="priority-badge"
                        :class="'badge-' + editablePriority"
                    >
                        {{ editablePriority }}
                    </span>

                    <div class="request-id">ID:{{ request.id }}</div>

                </div>

                <button
                    class="close"
                    @click="closeWithoutSave"
                >
                    ✕
                </button>

            </div>


            <!-- CONTENT -->

            <div class="content">

                <!-- LEFT SIDE -->

                <div class="left">

                    <!-- META -->

                    <div class="meta-grid">

                        <div class="meta-item">

                            <label>Этап</label>

                            <select
                                v-model="selectedStage"
                                @change="changeStage"
                                class="stage-select"
                            >

                                <option
                                    v-for="stage in stages"
                                    :key="stage.id"
                                    :value="stage.id"
                                >

                                    {{ stage.name }}

                                </option>

                            </select>

                        </div>


                        <div class="meta-item">

                            <label>Приоритет</label>

                            <div class="priority-row">

                                <select
                                    v-model="editablePriority"
                                    class="stage-select"
                                >
                                    <option value="low">low</option>
                                    <option value="normal">normal</option>
                                    <option value="high">high</option>
                                    <option value="critical">critical</option>
                                </select>

                            </div>

                        </div>


                        <div class="meta-item">

                            <label>Исполнитель</label>

                            <select
                                v-model="selectedAssignee"
                                @change="changeAssignee"
                                class="stage-select"
                            >

                                <option :value="null">не назначен</option>

                                <option
                                    v-for="user in users"
                                    :key="user.id"
                                    :value="user.id"
                                >
                                    {{ user.name }}
                                </option>

                            </select>

                        </div>


                        <div class="meta-item">

                            <label>Создана</label>

                            <div class="value">

                                {{ formatDate(request.created_at) }}

                            </div>

                        </div>

                    </div>


                    <!-- COMMENTS -->

                    <div class="comments">

                        <h3>Комментарии</h3>

                        <div v-if="comments.length === 0" class="empty">

                            Пока нет комментариев

                        </div>


                        <div
                            v-for="comment in visibleComments"
                            :key="comment.id"
                            class="comment"
                        >

                            <div class="comment-header">

                                <div class="comment-author">
                                    {{ comment.user?.name || 'Пользователь' }}
                                </div>

                                <div class="comment-date">
                                    {{ formatDate(comment.created_at) }}
                                </div>

                            </div>

                            <div class="comment-text">
                                {{ comment.comment }}
                            </div>

                        </div>

                    </div>

                    <div v-if="comments.length > 5" class="comments-toggle">

                        <button
                            v-if="!showAllComments"
                            @click="showAllComments = true"
                        >
                            Показать все комментарии
                        </button>

                        <button
                            v-else
                            @click="showAllComments = false"
                        >
                            Скрыть
                        </button>

                    </div>


                    <!-- COMMENT INPUT -->

                    <div class="comment-input">

<textarea
    v-model="commentText"
    placeholder="Написать комментарий..."
></textarea>

                        <button
                            class="add-comment"
                            @click="sendComment"
                        >
                            Добавить комментарий
                        </button>

                    </div>


                    <div class="actions">

                        <button
                            v-if="hasChanges"
                            class="save"
                            @click="saveRequest"
                        >
                            Сохранить
                        </button>

                        <button
                            class="cancel"
                            @click="$emit('close')"
                        >
                            Закрыть
                        </button>

                    </div>

                </div>


                <!-- RIGHT SIDE -->

                <div class="right">

                    <h3>История</h3>

                    <div
                        v-if="!activities || activities.length === 0"
                        class="empty"
                    >

                        Пока нет действий

                    </div>


                    <div
                        v-for="activity in activities"
                        :key="activity.id"
                        class="history-item"
                    >

                        <div v-if="activity.type === 'stage_changed'">

                            <b>{{ activity.user?.name }}</b>

                            изменил стадию

                            <br>

                            {{ activity.data.from_stage }}

                            →

                            {{ activity.data.to_stage }}

                            <div class="time">

                                {{ formatDate(activity.created_at) }}

                            </div>

                        </div>

                        <div v-if="activity.type === 'assignment'">

                            <b>{{ activity.user?.name }}</b>
                            назначил ответственным

                            <br>

                            {{ activity.data.new_assignee }}

                            <div class="time">
                                {{ formatDate(activity.created_at) }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>


<script setup>

import { ref, onMounted, computed } from "vue"
import axios from "axios"

const hasChanges = computed(() => {

    return (
        editableTitle.value !== initialTitle ||
        editablePriority.value !== initialPriority ||
        selectedStage.value !== initialStage ||
        selectedAssignee.value !== initialAssignee
    )

})

const props = defineProps({
    request:Object
})

const users = ref([])
const selectedAssignee = ref(props.request.assigned_to || null)

const emit = defineEmits([
    'close',
    'stageChanged',
    'updated'
])

const commentText = ref("")

const editableTitle = ref(props.request.title || "")
const editablePriority = ref(props.request.priority || "normal")
const initialTitle = props.request.title
const initialPriority = props.request.priority
const initialStage = props.request.stage_id
const initialAssignee = props.request.assigned_to

const stages = ref([])

const selectedStage = ref(props.request.stage_id)

const comments = ref(props.request.comments || [])
const activities = ref(props.request.activities || [])

const showAllComments = ref(false)

const visibleComments = computed(() => {

    if (showAllComments.value) {
        return comments.value
    }

    return comments.value.slice(0, 5)

})

function formatDate(date){
    if(!date) return ""

    return new Date(date).toLocaleString()
}

onMounted(async ()=>{
    const res = await axios.get(
        `/api/pipelines/${props.request.pipeline_id}/stages`
    )

    stages.value = res.data

    const usersRes = await axios.get('/api/users')
    users.value = usersRes.data
})

async function sendComment(){
    if(!commentText.value.trim()) return

    const response = await axios.post(
        `/api/requests/${props.request.id}/comments`,
        {
            comment: commentText.value
        }
    )

    comments.value.unshift(response.data)
    commentText.value=""
}

function closeWithoutSave(){
    emit('close')
}

async function saveRequest(){

    try{

        // обновляем основные поля
        const response = await axios.patch(
            `/api/requests/${props.request.id}`,
            {
                title: editableTitle.value,
                priority: editablePriority.value
            }
        )

        Object.assign(props.request, response.data)

        // если поменяли этап
        if(selectedStage.value !== props.request.stage_id){

            await axios.post(
                `/api/requests/${props.request.id}/move`,
                {
                    stage_id:selectedStage.value
                }
            )

        }

        // если поменяли исполнителя
        if(selectedAssignee.value !== props.request.assigned_to){

            await axios.post(
                `/api/requests/${props.request.id}/assign`,
                {
                    user_id:selectedAssignee.value
                }
            )

        }

        emit('updated')
        emit('close')

    }catch(e){

        console.error(e)
        alert("Ошибка сохранения")

    }

}
</script>


<style scoped>
/* Полоса приоритета слева */
.priority-card-low{
    border-left:6px solid #9ca3af;
}

.priority-card-normal{
    border-left:6px solid #3b82f6;
}

.priority-card-high{
    border-left:6px solid #f59e0b;
}

.priority-card-critical{
    border-left:6px solid #ef4444;
}

.priority-row{
    display:flex;
    gap:10px;
    align-items:center;
}

.priority-badge{
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    text-transform:uppercase;
}

.comments-toggle{
    margin-top:10px;
}

.comments-toggle button{
    background:#e5e7eb;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    cursor:pointer;
    font-size:12px;
}

.title-row{
    display:flex;
    align-items:center;
    gap:10px;
}

.priority-badge{
    padding:4px 10px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    text-transform:uppercase;
}

.badge-low{
    background:#f3f4f6;
    color:#374151;
}

.badge-normal{
    background:#dbeafe;
    color:#1e40af;
}

.badge-high{
    background:#fde68a;
    color:#92400e;
}

.badge-critical{
    background:#fecaca;
    color:#7f1d1d;
}

.title-input{
    width:100%;
    font-size:24px;
    font-weight:700;
    border:1px solid #d1d5db;
    border-radius:8px;
    padding:8px 12px;
    outline:none;
}

.title-input:focus{
    border-color:#4f46e5;
}

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.35);
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    width:900px;
    max-height:90vh;
    background:white;
    border-radius:10px;
    padding:25px;
    box-shadow:0 20px 60px rgba(0,0,0,0.2);
    overflow-y:auto;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.request-id{
    color:#888;
    font-size:13px;
}

.close{
    border:none;
    background:none;
    font-size:20px;
    cursor:pointer;
}

/* LAYOUT */

.content{
    display:flex;
    gap:30px;
    margin-top:25px;
    align-items:stretch;
}

.left{
    flex:1;
    overflow-y:auto;
    padding-right:5px;
}

.right{
    width:320px;
    min-width:320px;
    background:#f8fafc;
    padding:15px;
    border-radius:8px;
    max-height:600px;
    overflow-y:auto;
}

/* META */

.meta-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

.meta-item label{
    font-size:12px;
    color:#777;
    display:block;
    margin-bottom:4px;
}

.stage-select{
    width:100%;
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #ccc;
}

.badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
}

.priority{
    background:#fff3cd;
}

.value{
    background:#f5f6f7;
    padding:8px 12px;
    border-radius:6px;
}

/* COMMENTS */

.comments{
    margin-top:30px;
}

.comment{
    background:#f6f7fb;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}

.comment-header{
    display:flex;
    justify-content:space-between;
}

.comment-author{
    font-weight:600;
    font-size:14px;
}

.comment-date{
    font-size:12px;
    color:#888;
}

.comment-text{
    margin-top:4px;
}

/* COMMENT INPUT */

.comment-input{
    margin-top:20px;
    display:flex;
    flex-direction:column;
}

.comment-input textarea{
    width:100%;
    min-height:120px;
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
    resize:vertical;
}

.add-comment{
    margin-top:8px;
    align-self:flex-start;
    background:#e5e7eb;
    color:#374151;
    border:none;
    padding:6px 12px;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
}

.add-comment:hover{
    background:#d1d5db;
}

/* ACTIONS */

.actions{
    display:flex;
    gap:10px;
    margin-top:30px;
}

.save{
    background:#4f46e5;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}

.cancel{
    background:#e5e7eb;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}

/* HISTORY */

.history-item{
    background:#eef2ff;
    padding:10px;
    border-radius:8px;
    margin-bottom:8px;
}

.time{
    margin-top:4px;
    font-size:12px;
    color:#666;
}

.empty{
    color:#888;
}

</style>
