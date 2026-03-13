<template>

    <div v-if="pipeline" class="kanban">

        <StageColumn
            v-for="stage in pipeline.stages"
            :key="stage.id"
            :stage="stage"
        />

    </div>

    <div v-else class="loading">

        Loading pipeline...

    </div>

</template>

<script setup>

import {computed, onMounted} from 'vue'
import {useRequestStore} from '../stores/requestStore'
import StageColumn from './StageColumn.vue'

/**
 * Подключаем Pinia store
 */
const store = useRequestStore()

/**
 * Делаем pipeline реактивным
 */
const pipeline = computed(() => store.pipeline)

/**
 * При загрузке компонента
 * загружаем pipeline из API
 */
onMounted(() => {

    store.loadPipeline()

})

</script>

<style>

.kanban {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 20px;
}

.loading {
    padding: 20px;
    font-size: 18px;
}

</style>
