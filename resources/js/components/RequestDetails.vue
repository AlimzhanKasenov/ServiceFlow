<template>

    <div class="modal">

        <div class="card">

            <!-- HEADER -->

            <div class="header">

                <div>

                    <h2>{{ request.title }}</h2>
                    <div class="request-id">#{{ request.id }}</div>

                </div>

                <button class="close" @click="$emit('close')">
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

                            <div class="badge priority">

                                {{ request.priority }}

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
                            v-for="comment in comments"
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


                    <!-- ACTIONS -->

                    <div class="actions">

                        <button
                            class="save"
                            @click="saveRequest"
                        >
                            Сохранить
                        </button>

                        <button
                            class="cancel"
                            @click="$emit('close')"
                        >
                            Отмена
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

                    </div>

                </div>

            </div>

        </div>

    </div>

</template>


<script setup>

import {ref,onMounted} from "vue"
import axios from "axios"

const props = defineProps({
    request:Object
})

const users = ref([])
const selectedAssignee = ref(props.request.assigned_to || null)

const emit = defineEmits([
    'close',
    'stageChanged'
])

const commentText = ref("")

const stages = ref([])

const selectedStage = ref(props.request.stage_id)

const comments = ref(props.request.comments || [])
const activities = ref(props.request.activities || [])

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


async function changeStage(){

    const response = await axios.post(
        `/api/requests/${props.request.id}/move`,
        {
            stage_id:selectedStage.value
        }
    )

    if(response.data.activity){

        activities.value.unshift(response.data.activity)

    }

    emit('stageChanged',{
        request_id:props.request.id,
        stage_id:selectedStage.value
    })

}


async function changeAssignee(){

    const response = await axios.post(
        `/api/requests/${props.request.id}/assign`,
        {
            user_id: selectedAssignee.value
        }
    )

    Object.assign(props.request, response.data.request)

    if(response.data.activity){

        activities.value.unshift(response.data.activity)

    }

}


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


async function saveRequest(){

    const response = await axios.patch(

        `/api/requests/${props.request.id}`,

        {}

    )

    Object.assign(props.request,response.data)

}

</script>


<style scoped>

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
    overflow:auto;
    background:white;
    border-radius:10px;
    padding:25px;
    box-shadow:0 20px 60px rgba(0,0,0,0.2);
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
    align-items:flex-start;
}

.left{
    flex:1;
}

.right{
    width:320px;
    min-width:320px;
    background:#f8fafc;
    padding:15px;
    border-radius:8px;
    max-height:500px;
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
