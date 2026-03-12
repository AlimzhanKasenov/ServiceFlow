import { createApp } from 'vue';
import { createPinia } from 'pinia';
import KanbanBoard from './components/KanbanBoard.vue';

console.log('Vue loaded')
const app = createApp({});

app.use(createPinia());

app.component('kanban-board', KanbanBoard);

app.mount('#app');
