import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import 'font-awesome/css/font-awesome.min.css';


import { createApp } from 'vue';
import router from './router'

import app from './components/app.vue'

createApp(app).use(router).mount('#app')
