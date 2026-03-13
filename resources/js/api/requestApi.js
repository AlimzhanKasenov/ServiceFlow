import axios from 'axios'

export default {

    getPipeline(pipelineId) {
        return axios.get(`/api/pipelines/${pipelineId}`)
    },

    moveRequest(requestId, stageId) {
        return axios.post(`/api/requests/${requestId}/move`, {
            stage_id: stageId
        })
    },

    createRequest(title, stageId) {
        return axios.post(`/api/requests`, {
            title: title,
            stage_id: stageId
        })
    }

}
