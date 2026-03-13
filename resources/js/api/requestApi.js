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
    },

    async getRequest(id) {
        const { data } = await axios.get(`/api/requests/${id}`)
        return data
    },

    getComments(requestId) {
        return axios.get(`/api/requests/${requestId}/comments`)
    },

    addComment(requestId, message) {
        return axios.post(`/api/requests/${requestId}/comments`, {
            message
        })
    }
}
