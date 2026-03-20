import { defineStore } from 'pinia'
import requestApi from '../api/requestApi'

export const useRequestStore = defineStore('requests', {

    state: () => ({
        pipeline: null,
        draggedRequest: null, // 👈 текущая перетаскиваемая карточка
    }),

    actions: {

        /**
         * Загрузка всей воронки (канбан)
         */
        async loadPipeline() {
            const res = await requestApi.getPipeline(1)
            this.pipeline = res.data
        },

        /**
         * Перемещение заявки
         */
        async moveRequest(requestId, stageId) {
            await requestApi.moveRequest(requestId, stageId)

            /**
             * После перемещения полностью обновляем канбан,
             * чтобы получить новые allowed_transitions
             */
            await this.loadPipeline()
        },

        /**
         * Создание заявки
         */
        async createRequest(title, stageId) {
            await requestApi.createRequest(title, stageId)
            await this.loadPipeline()
        },

        /**
         * Устанавливаем текущую перетаскиваемую карточку
         */
        setDraggedRequest(request) {
            this.draggedRequest = request
        },

        /**
         * Очищаем после завершения drag
         */
        clearDraggedRequest() {
            this.draggedRequest = null
        }

    }

})
