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

            <button class="close-btn" @click="$emit('close')">
                Закрыть
            </button>

        </div>

    </div>

</template>

<script setup>

defineProps({
    request: Object
})

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
    width:420px;
    border-radius:10px;
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
