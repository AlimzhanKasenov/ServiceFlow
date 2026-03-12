<template>

    <div class="kanban">

        <div
            v-for="stage in stages"
            :key="stage.id"
            class="kanban-column"
        >

            <h3>{{ stage.name }}</h3>

            <draggable
                :list="requestsByStage(stage.id)"
                group="requests"
                @end="onMove($event, stage.id)"
            >

                <div
                    v-for="request in requestsByStage(stage.id)"
                    :key="request.id"
                    class="kanban-card"
                >
                    {{ request.title }}
                </div>

            </draggable>

        </div>

    </div>

</template>

<script>

import axios from 'axios'
import draggable from 'vuedraggable'

export default {

    components: {
        draggable
    },

    data() {
        return {

            stages: [],
            requests: []

        }
    },

    mounted() {

        this.loadStages()
        this.loadRequests()

    },

    methods: {

        async loadStages() {

            const res = await axios.get('/api/pipelines/1')

            this.stages = res.data.stages

        },

        async loadRequests() {

            const res = await axios.get('/api/requests')

            this.requests = res.data

        },

        requestsByStage(stageId) {

            return this.requests.filter(
                r => r.stage_id === stageId
            )

        },

        async onMove(event, stageId) {

            const requestId = event.item.__vueParentComponent.props.request?.id

            if (!requestId) return

            await axios.post(`/api/requests/${requestId}/move`, {
                stage_id: stageId
            })

        }

    }

}

</script>

<style>

.kanban {

    display: flex;
    gap: 20px;
    padding: 20px;

}

.kanban-column {

    width: 300px;
    background: #f5f5f5;
    padding: 10px;

}

.kanban-card {

    background: white;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
    cursor: grab;

}

</style>
