<template>

    <div class="kanban-wrapper">

        <div class="kanban-header">

            <h2>ServiceFlow</h2>

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

import {computed, onMounted} from 'vue'
import {useRequestStore} from '../stores/requestStore'
import StageColumn from './StageColumn.vue'

const store = useRequestStore()

const pipeline = computed(() => store.pipeline)

onMounted(() => {
    store.loadPipeline()
})

function createRequest() {

    const title = prompt('Request title')

    if (!title) return

    store.createRequest(title, 1)

}

</script>

<style>

body {
    margin: 0;
    font-family: system-ui;
    background: #f1f3f5;
}

.kanban-wrapper {
    padding: 20px;
}

.kanban-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.create-btn {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
}

.create-btn:hover {
    background: #4338ca;
}

.kanban {
    display: flex;
    gap: 20px;
}

</style>
