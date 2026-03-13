<template>

    <div class="kanban-wrapper">

        <div class="kanban-header">

            <h1>ServiceFlow</h1>

            <button class="create-btn" @click="createRequest">
                + Create Request
            </button>

        </div>

        <div v-if="pipeline" class="kanban">

            <StageColumn
                v-for="stage in pipeline.stages"
                :key="stage.id"
                :stage="stage"
            />

        </div>

    </div>

</template>

<script setup>

import { computed, onMounted } from 'vue'
import { useRequestStore } from '../stores/requestStore'
import StageColumn from './StageColumn.vue'

const store = useRequestStore()

const pipeline = computed(() => store.pipeline)

onMounted(() => {
    store.loadPipeline()
})

function createRequest(){

    const title = prompt('Request title')

    if(!title) return

    store.createRequest(title, 1)

}

</script>

<style>

body{
    margin:0;
    font-family:system-ui;
    background:#eef1f5;
}

.kanban-wrapper{
    padding:25px;
}

.kanban-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.kanban-header h1{
    font-size:22px;
}

.create-btn{
    background:#4f46e5;
    color:white;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
    transition:.2s;
}

.create-btn:hover{
    background:#4338ca;
}

.kanban{
    display:flex;
    gap:20px;
    align-items:flex-start;
}

</style>
