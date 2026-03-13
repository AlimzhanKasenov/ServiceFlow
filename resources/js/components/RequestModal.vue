<template>

    <div class="modal">

        <div class="modal-content">

            <h2>Заявка №{{ request.id }}</h2>

            <h3>{{ request.title }}</h3>

            <p>{{ request.description }}</p>

            <hr>

            <p>
                <b>Этап:</b>
                {{ request.stage?.name }}
            </p>

            <p>
                <b>Приоритет:</b>
                {{ request.priority }}
            </p>

            <p>
                <b>Исполнитель:</b>
                {{ request.assignee?.name || 'не назначен' }}
            </p>

            <hr>

            <h3>Комментарии</h3>

            <div class="comments">

                <div
                    v-for="comment in comments"
                    :key="comment.id"
                    class="comment"
                >

                    <div class="comment-header">
                        {{ comment.user?.name || 'Пользователь' }}
                    </div>

                    <div class="comment-text">
                        {{ comment.message }}
                    </div>

                </div>

            </div>

            <div class="comment-form">

            <textarea
                v-model="newComment"
                placeholder="Написать комментарий..."
            ></textarea>

                <button @click="sendComment">
                    Отправить
                </button>

            </div>

            <button class="close-btn" @click="$emit('close')">
                Закрыть
            </button>

        </div>

    </div>

</template>

<script setup>

import { ref, onMounted } from 'vue'
import requestApi from '../api/requestApi'

const props = defineProps({
    request: Object
})

const comments = ref([])
const newComment = ref('')

async function loadComments(){

    const res = await requestApi.getComments(props.request.id)

    comments.value = res.data

}

async function sendComment(){

    if(!newComment.value) return

    const res = await requestApi.addComment(
        props.request.id,
        newComment.value
    )

    comments.value.unshift(res.data)

    newComment.value = ''

}

onMounted(loadComments)

</script>

<style>

.modal{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.4);
    display:flex;
    justify-content:center;
    align-items:center;
}

.modal-content{
    background:white;
    padding:20px;
    width:450px;
    border-radius:10px;
    max-height:80vh;
    overflow:auto;
}

.comments{
    margin-top:10px;
}

.comment{
    background:#f3f4f6;
    padding:8px;
    border-radius:6px;
    margin-bottom:8px;
}

.comment-header{
    font-size:12px;
    font-weight:600;
    margin-bottom:4px;
}

.comment-text{
    font-size:13px;
}

.comment-form textarea{
    width:100%;
    margin-top:10px;
    padding:8px;
    border-radius:6px;
    border:1px solid #d1d5db;
    resize:none;
}

.comment-form button{
    margin-top:6px;
    background:#4f46e5;
    color:white;
    border:none;
    padding:6px 12px;
    border-radius:6px;
    cursor:pointer;
}

.close-btn{
    margin-top:15px;
    background:#e5e7eb;
    border:none;
    padding:8px 12px;
    border-radius:6px;
    cursor:pointer;
}

</style>
