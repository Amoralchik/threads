import "./bootstrap";
import { createApp } from "vue/dist/vue.esm-bundler.js";

import PostList from "./components/PostList.vue";

const app = createApp({
    components: {
        PostList,
    },
});
app.mount("#app");
