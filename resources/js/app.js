import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler.js";
import PrimeVue from "primevue/config";

import "primevue/resources/themes/lara-light-green/theme.css";
import "primeicons/primeicons.css";

import PostList from "./components/PostList.vue";

const app = createApp({
    components: {
        PostList,
    },
});
app.use(PrimeVue);

app.mount("#app");
