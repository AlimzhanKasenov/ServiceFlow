<template>

    <div class="stage">

        <div class="stage-header">

        <span class="stage-title">
            {{ stage.name }}
        </span>

            <span class="count">
            {{ stage.requests?.length || 0 }} шт
        </span>

        </div>

        <draggable
            :list="stage.requests"
            group="requests"
            item-key="id"
            class="stage-body"
            @change="onChange"
            ghost-class="ghost"
            chosen-class="chosen"
            drag-class="dragging"
            animation="200"
        >

            <template #item="{ element }">

                <div
                    class="request-card"
                    @click="$emit('open-request', element.id)"
                >

                    <div class="request-title">
                        {{ element.title }}
                    </div>

                    <div class="request-id">
                        #{{ element.id }}
                    </div>

                </div>

            </template>

        </draggable>

    </div>

</template>

<script setup>

import draggable from 'vuedraggable'
import { useRequestStore } from '../stores/requestStore'

const props = defineProps({
    stage: Object
})

defineEmits([
    'open-request'
])

const store = useRequestStore()

function onChange(evt) {

    if (!evt.added) return

    const request = evt.added.element

    store.moveRequest(
        request.id,
        props.stage.id
    )

}

</script>

<style>

.stage {
    width: 320px;
    background: #f3f4f6;
    border-radius: 12px;
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.stage-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    margin-bottom: 10px;
}

.stage-title {
    font-size: 15px;
}

.count {
    background: #e5e7eb;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
}

.stage-body {
    min-height: 50px;
}

.request-card {
    background: white;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.request-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.request-title {
    font-weight: 500;
}

.request-id {
    font-size: 12px;
    color: #6b7280;
}

.ghost {
    opacity: 0.4;
}

.chosen {
    transform: scale(1.02);
}

.dragging {
    transform: rotate(1deg);
}

</style>
