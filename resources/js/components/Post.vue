<template>
    <div
        class="mt-2 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg"
    >
        <div>
            <div class="p-6">
                <div class="text-slate-500 flex justify-between">
                    <div>
                        <h2 class="">
                            {{ post.author }}
                        </h2>
                        <h2 v-if="post.parent_post_id">
                            reply to: {{ author }}
                        </h2>
                    </div>
                    <h2>{{ createdAt }}</h2>
                </div>
                <div class="text-slate-300">
                    <h1>{{ post.content }}</h1>
                </div>
            </div>
        </div>
        <div class="mb-4 px-4">
            <div class="flex space-x-4 items-baseline">
                <button @click="toggleForm">Open Reply Form</button>
                <button
                    @click="deletePost"
                    class="text-red-600 hover:underline"
                >
                    Delete Post
                </button>
            </div>
            <PostForm
                v-if="showForm"
                :parent_post_id="post.id"
                :author="post.author"
            />
        </div>
    </div>
    <div class="pl-2 border-l-2 border-blue-500">
        <post-component
            v-for="descendant in post.descendants"
            :key="descendant.id"
            :post="descendant"
            :author="post.author"
        ></post-component>
    </div>
</template>

<script>
import dayjs from "dayjs";
import dayjsTwitter from "dayjs-twitter";
import PostForm from "./PostForm.vue";

dayjs.extend(dayjsTwitter);

export default {
    components: {
        PostForm,
    },
    name: "post-component",
    props: ["post", "author"],
    data() {
        return {
            showForm: false,
            createdAt: dayjs(this.post.created_at)
                .subtract(1, "year")
                .twitter(),
        };
    },
    methods: {
        toggleForm() {
            this.showForm = !this.showForm;
        },
        deletePost() {
            console.log("Delete post with ID:", this.post.id);
        },
    },
};
</script>
