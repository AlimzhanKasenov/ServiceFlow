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

        <!-- Канбан доска -->
        <div v-if="pipeline" class="kanban">

            <StageColumn
                v-for="stage in pipeline.stages"
                :key="stage.id"
                :stage="stage"
                @open-request="openRequest"
            />

        </div>

        <div v-else class="loading">
            Загрузка канбана...
        </div>

    </div>

    <!-- Карточка заявки -->
    <RequestDetails
        v-if="selectedRequest"
        :request="selectedRequest"
        @close="selectedRequest = null"
        @stageChanged="onStageChanged"
        @updated="reloadKanban"
    />

</template>


<script setup>

import { computed, onMounted, ref } from 'vue'
import { useRequestStore } from '../stores/requestStore'
import StageColumn from './StageColumn.vue'
import requestApi from '../api/requestApi'
import RequestDetails from './RequestDetails.vue'

const store = useRequestStore()

/**
 * pipeline из store
 */
const pipeline = computed(() => store.pipeline)

/**
 * выбранная заявка
 */
const selectedRequest = ref(null)

/**
 * загрузка канбана
 */
onMounted(() => {
    store.loadPipeline()
})

function reloadKanban(){
    store.loadPipeline()
}

function onStageChanged(data){

    if(!pipeline.value) return

    const stages = pipeline.value.stages

    let movedRequest = null

    // ищем карточку
    for(const stage of stages){

        const index = stage.requests.findIndex(
            r => r.id === data.request_id
        )

        if(index !== -1){

            movedRequest = stage.requests.splice(index,1)[0]

            break

        }

    }

    if(!movedRequest) return

    // добавляем в новую колонку
    const targetStage = stages.find(
        s => s.id === data.stage_id
    )

    if(targetStage){

        movedRequest.stage_id = data.stage_id

        targetStage.requests.push(movedRequest)

    }

}

/**
 * создание заявки
 */
function createRequest() {

    const title = prompt('Введите название заявки')

    if (!title) return

    store.createRequest(title, 1)

}


/**
 * открытие карточки заявки
 */
async function openRequest(id) {

    try {

        const request = await requestApi.getRequest(id)

        selectedRequest.value = request

    } catch (error) {

        console.error('Ошибка загрузки заявки', error)

        alert('Не удалось загрузить заявку')

    }
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

.create-btn:hover {
    background: #4338ca;
}

.kanban {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.loading {
    color: #666;
}

</style>
