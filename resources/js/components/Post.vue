<template>
    <div class="mt-2 bg-white overflow-hidden shadow sm:rounded-lg divide-y">
        <div>
            <div class="p-6">
                <div class="text-slate-500 flex justify-between">
                    <div>
                        <div class="flex space-x-4 items-center">
                            <div
                                class="bg-contain w-10 h-10 min-h-2 rounded-full"
                                :style="{ backgroundImage: `url(${avatar})` }"
                            ></div>
                            <div>
                                <h3>username:</h3>
                                <h2>
                                    {{ post.author }}
                                </h2>
                            </div>
                        </div>
                        <h2 class="mt-2" v-if="post.parent_post_id">
                            reply to: {{ author }}
                        </h2>
                    </div>
                    <h2>{{ createdAt }}</h2>
                </div>
                <div class="mt-4">
                    <h1>{{ post.content }}</h1>
                </div>
            </div>
        </div>
        <div class="mb-4 px-4 pt-2">
            <div class="flex justify-between items-baseline">
                <Button :onClick="toggleForm" title="Reply" color="cyan" />
                <Button :onClick="deletePost" title="Delete" color="red" />
            </div>
            <PostForm
                v-show="showForm"
                :parent_post_id="post.id"
                :parent_author="post.author"
                :onClick="toggleForm"
                :user="user"
            />
        </div>
    </div>
    <div class="pl-2 border-l-2 border-blue-500">
        <post-component
            v-for="descendant in post.descendants"
            :key="descendant.id"
            :post="descendant"
            :author="post.author"
            :user="user"
        />
    </div>
</template>

<script>
import dayjs from "dayjs";
import gravatar from "gravatar";

import dayjsTwitter from "dayjs-twitter";
import PostForm from "./PostForm.vue";
import Button from "../ui/Button.vue";

dayjs.extend(dayjsTwitter);

export default {
    components: {
        PostForm,
        Button,
    },
    name: "post-component",
    props: ["post", "author", "user"],
    data() {
        return {
            showForm: false,
            createdAt: dayjs(this.post.created_at).twitter(),
            avatar: gravatar.url(
                this.post.author,
                { s: "100", r: "x", d: "retro" },
                true
            ),
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
