<template>

    <div class="stage">

        <div class="stage-header">

            {{ stage.name }}

            <span class="count">
            {{ stage.requests?.length || 0 }}
        </span>

        </div>

        <draggable
            :list="stage.requests"
            group="requests"
            item-key="id"
            @change="onMove"
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

function onMove(evt){

    if(!evt.added) return

    const request = evt.added.element

    store.moveRequest(request.id, props.stage.id)

}

</script>

<style>

.stage{
    width:320px;
    background:#e9ecef;
    border-radius:10px;
    padding:10px;
}

.stage-header{
    font-weight:600;
    margin-bottom:10px;
    display:flex;
    justify-content:space-between;
}

.count{
    background:#dee2e6;
    padding:2px 8px;
    border-radius:10px;
}

</style>
