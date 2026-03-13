import { defineStore } from 'pinia'
import requestApi from '../api/requestApi'

export const useRequestStore = defineStore('requests', {

    state: () => ({
        pipeline: null
    }),

    actions: {

        async loadPipeline() {
            const res = await requestApi.getPipeline(1)
            this.pipeline = res.data
        },

        async moveRequest(requestId, stageId) {
            await requestApi.moveRequest(requestId, stageId)
            await this.loadPipeline()
        },

        async createRequest(title, stageId) {
            await requestApi.createRequest(title, stageId)
            await this.loadPipeline()
        }

    }

})
