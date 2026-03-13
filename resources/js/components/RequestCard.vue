<template>

    <div class="card" @click="$emit('open-request', request.id)">

        <div class="card-title">
            {{ request.title }}
        </div>

        <div class="card-footer">

        <span class="id">
            №{{ request.id }}
        </span>

            <span class="priority">
            {{ priorityLabel }}
        </span>

        </div>

    </div>

</template>

<script setup>

import { computed } from 'vue'

const props = defineProps({
    request: Object
})

defineEmits([
    'open-request'
])

const priorityLabel = computed(() => {

    const map = {
        low: 'низкий',
        normal: 'обычный',
        high: 'высокий',
        urgent: 'срочный'
    }

    return map[props.request.priority] || 'обычный'

})

</script>

<style>

.card {
    background: white;
    border-radius: 10px;
    padding: 12px;
    margin-bottom: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    cursor: grab;
    transition: all .2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}

.card-title {
    font-size: 14px;
    font-weight: 500;
}

.card-footer {
    margin-top: 6px;
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #6b7280;
}

.priority {
    background: #e5e7eb;
    padding: 2px 6px;
    border-radius: 6px;
}

</style>
