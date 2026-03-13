import { createApp } from 'vue'
import { createPinia } from 'pinia'
import KanbanBoard from './components/KanbanBoard.vue'

const app = createApp({})

app.use(createPinia())

app.component('kanban-board', KanbanBoard)

app.mount('#app')
