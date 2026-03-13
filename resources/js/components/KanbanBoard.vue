<template>

    <div class="kanban-wrapper">

        <div class="kanban-header">

            <h1 class="text-3xl font-bold text-blue-600">
                ServiceFlow — система заявок
            </h1>

            <button class="create-btn" @click="createRequest">
                + Создать заявку
            </button>

        </div>

        <div v-if="pipeline" class="kanban">

            <StageColumn
                v-for="stage in pipeline.stages"
                :key="stage.id"
                :stage="stage"
                @open-request="openRequest"
            />

        </div>

    </div>

    <RequestDetails
        v-if="selectedRequest"
        :request="selectedRequest"
        @close="selectedRequest = null"
    />

</template>


<script setup>

import {computed, onMounted, ref} from 'vue'
import {useRequestStore} from '../stores/requestStore'
import StageColumn from './StageColumn.vue'
import requestApi from '../api/requestApi'
import RequestDetails from './RequestDetails.vue'

const store = useRequestStore()

const pipeline = computed(() => store.pipeline)

const selectedRequest = ref(null)

onMounted(() => {
    store.loadPipeline()
})

function createRequest() {

    const title = prompt('Введите название заявки')

    if (!title) return

    store.createRequest(title, 1)

}

async function openRequest(id) {

    selectedRequest.value = await requestApi.getRequest(id)

}

</script>


<style>

body {
    margin: 0;
    font-family: system-ui;
    background: #eef1f5;
}

.kanban-wrapper {
    padding: 25px;
}

.kanban-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.kanban-header h1 {
    font-size: 22px;
}

.create-btn {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
}

.kanban {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

</style>
