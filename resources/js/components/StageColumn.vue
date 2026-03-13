<template>

    <div class="stage">

        <div class="stage-header">
            <span class="stage-title">{{ stage.name }}</span>
            <span class="count">{{ stage.requests?.length || 0 }}</span>
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
                <RequestCard :request="element" />
            </template>
        </draggable>

    </div>

</template>

<script setup>

import draggable from 'vuedraggable'
import RequestCard from './RequestCard.vue'
import { useRequestStore } from '../stores/requestStore'

const props = defineProps({
    stage: Object
})

const store = useRequestStore()

function onChange(evt) {

    // интересует только когда элемент ДОБАВИЛСЯ в эту колонку
    if (!evt.added) return

    const request = evt.added.element

    store.moveRequest(request.id, props.stage.id)

}

</script>

<style>

.stage{
    width:320px;
    background:#f3f4f6;
    border-radius:12px;
    padding:10px;
    display:flex;
    flex-direction:column;
}

.stage-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-weight:600;
    margin-bottom:10px;
}

.stage-title{
    font-size:15px;
}

.count{
    background:#e5e7eb;
    border-radius:12px;
    padding:2px 8px;
    font-size:12px;
}

.stage-body{
    min-height:50px;
}

/* drag стили */

.ghost{
    opacity:0.4;
}

.chosen{
    transform:scale(1.02);
}

.dragging{
    transform:rotate(1deg);
}

</style>
