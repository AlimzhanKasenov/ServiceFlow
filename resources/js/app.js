import '../css/app.css'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import KanbanBoard from './components/KanbanBoard.vue'

const app = createApp(KanbanBoard)

app.use(createPinia())

app.mount('#app')
