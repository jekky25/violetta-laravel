import '../js/jquery-1.9.0.min.js';
import '../js/frame_script.js';
import './bootstrap';
import '../css/reset.css';
import '../css/style.css';

import { createApp } from 'vue/dist/vue.esm-bundler';
import BestProfile from './components/Profiles/BestProfile.vue';
import ForumTop from './components/Forum/ForumTop.vue';
import Statistics from './components/Blocks/Statistics.vue';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({
    components: {
        BestProfile,
        ForumTop,
        Statistics
    },
});


app.mount('#app');
