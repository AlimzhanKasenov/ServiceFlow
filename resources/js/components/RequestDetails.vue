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

            <!-- META -->

            <div class="meta-grid">

                <div class="meta-item">
                    <label>Этап</label>
                    <div class="badge stage">
                        {{ request.stage?.name }}
                    </div>
                </div>

                <div class="meta-item">
                    <label>Приоритет</label>
                    <div class="badge priority">
                        {{ request.priority }}
                    </div>
                </div>

                <div class="meta-item">
                    <label>Исполнитель</label>
                    <div class="value">
                        {{ request.assigned_user?.name || "не назначен" }}
                    </div>
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

                    <div class="comment-author">
                        {{ comment.user?.name }}
                    </div>

                    <div class="comment-text">
                        {{ comment.comment }}
                    </div>

                </div>

            </div>

            <!-- COMMENT FORM -->

            <div class="comment-form">

            <textarea
                v-model="commentText"
                placeholder="Написать комментарий..."
            ></textarea>

                <button
                    class="send"
                    @click="sendComment"
                >
                    Отправить
                </button>

            </div>

        </div>

    </div>

</template>



<script setup>

import { ref } from "vue"
import axios from "axios"

const props = defineProps({
    request: Object
})

const commentText = ref("")
const comments = ref(props.request.comments || [])

function formatDate(date){

    if(!date) return ""

    return new Date(date).toLocaleString()

}

async function sendComment(){

    if(!commentText.value.trim()) return

    const response = await axios.post(
        `/api/requests/${props.request.id}/comments`,
        {
            comment: commentText.value
        }
    )

    comments.value.push(response.data)

    commentText.value = ""

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
    width:650px;
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

.meta-grid{
    margin-top:25px;
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

.badge{
    display:inline-block;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
}

.stage{
    background:#e3f2fd;
}

.priority{
    background:#fff3cd;
}

.value{
    background:#f5f6f7;
    padding:8px 12px;
    border-radius:6px;
}

.comments{
    margin-top:30px;
}

.comment{
    background:#f6f7fb;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}

.comment-author{
    font-weight:600;
    font-size:14px;
}

.comment-text{
    margin-top:4px;
}

.comment-form{
    margin-top:20px;
}

.comment-form textarea{
    width:100%;
    min-height:80px;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
}

.send{
    margin-top:10px;
    background:#4f46e5;
    color:white;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    cursor:pointer;
}

.empty{
    color:#888;
}

</style>
