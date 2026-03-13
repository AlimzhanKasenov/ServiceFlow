<template>

    <div class="stage">

        <h3>{{ stage.name }}</h3>

        <draggable
            :list="stage.requests"
            group="requests"
            item-key="id"
            @end="onMove"
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

/**
 * props
 */
const props = defineProps({
    stage: Object
})

/**
 * store
 */
const store = useRequestStore()

/**
 * обработка перемещения карточки
 */
function onMove(evt) {

    const request = evt.item.__draggable_context.element

    store.moveRequest(request.id, props.stage.id)

}

</script>

<style>

.stage {
    width: 300px;
    background: #f4f5f7;
    padding: 10px;
    border-radius: 6px;
}

</style>
