<template>
    <div
        class="stage"
        :class="{
            'stage-allowed': isAllowedDrop,
            'stage-blocked': isBlockedDrop
        }"
    >
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
            @start="onDragStart"
            @end="onDragEnd"
            @change="onChange"
            ghost-class="ghost"
            chosen-class="chosen"
            drag-class="dragging"
            animation="200"
        >
            <template #item="{ element }">
                <div
                    class="request-card"
                    :class="[
                        'priority-' + (element.priority || 'normal'),
                        slaClass(element)
                    ]"
                    @click="$emit('open-request', element.id)"
                >
                    <div class="request-title">
                        {{ element.title }}
                    </div>

                    <div class="request-id">
                        ID:{{ element.id }}
                    </div>
                </div>
            </template>
        </draggable>
    </div>
</template>

<script setup>
import {computed} from 'vue'
import draggable from 'vuedraggable'
import {useRequestStore} from '../stores/requestStore'

const props = defineProps({
    stage: Object
})

defineEmits([
    'open-request'
])

const store = useRequestStore()

/**
 * Можно ли перетащить текущую карточку в эту колонку
 */
const isAllowedDrop = computed(() => {
    const draggedRequest = store.draggedRequest

    if (!draggedRequest) {
        return false
    }

    return Array.isArray(draggedRequest.allowed_transitions)
        && draggedRequest.allowed_transitions.includes(props.stage.id)
})

/**
 * Нужно ли подсветить колонку как запрещённую
 */
const isBlockedDrop = computed(() => {
    const draggedRequest = store.draggedRequest

    if (!draggedRequest) {
        return false
    }

    return Array.isArray(draggedRequest.allowed_transitions)
        && !draggedRequest.allowed_transitions.includes(props.stage.id)
})

/**
 * Начало перетаскивания карточки
 */
function onDragStart(evt) {
    const request = evt.item?._underlying_vm_

    if (!request) {
        return
    }

    store.setDraggedRequest(request)
}

/**
 * Завершение перетаскивания
 */
function onDragEnd() {
    store.clearDraggedRequest()
}

/**
 * Обработка перемещения между колонками
 */
function onChange(evt) {
    if (!evt.added) return

    const request = evt.added.element

    /**
     * Если нет списка allowed_transitions, пока не блокируем
     */
    if (!Array.isArray(request.allowed_transitions)) {
        store.moveRequest(request.id, props.stage.id)
        return
    }

    /**
     * Если текущая колонка не входит в разрешённые переходы —
     * откатываем карточку назад и ничего не сохраняем
     */
    if (!request.allowed_transitions.includes(props.stage.id)) {
        store.loadPipeline()
        return
    }

    store.moveRequest(
        request.id,
        props.stage.id
    )
}

function slaClass(request) {
    if (!request?.sla_due_at) {
        return ''
    }

    if (request.sla_breached) {
        return 'sla-breached'
    }

    const now = new Date()
    const due = new Date(request.sla_due_at)

    const diff = (due - now) / 60000

    if (diff < 0) {
        return 'sla-breached'
    }

    if (diff < 30) {
        return 'sla-warning'
    }

    return 'sla-ok'
}
</script>

<style>
.sla-ok {
    border-right: 5px solid #22c55e;
}

.sla-warning {
    border-right: 5px solid #f59e0b;
}

.sla-breached {
    border-right: 5px solid #ef4444;
}

/* LOW */
.priority-low {
    border-left: 5px solid #9ca3af;
    background: #f9fafb;
}

/* NORMAL */
.priority-normal {
    border-left: 5px solid #3b82f6;
    background: #eff6ff;
}

/* HIGH */
.priority-high {
    border-left: 5px solid #f59e0b;
    background: #fffbeb;
}

/* CRITICAL */
.priority-critical {
    border-left: 5px solid #ef4444;
    background: #fef2f2;
}

.stage {
    width: 320px;
    background: #f3f4f6;
    border-radius: 12px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    transition: 0.2s ease;
}

.stage-allowed {
    background: #ecfdf5;
    box-shadow: inset 0 0 0 2px #22c55e;
}

.stage-blocked {
    background: #eff6ff;
    box-shadow: inset 0 0 0 2px #3b82f6;
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
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.request-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
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
