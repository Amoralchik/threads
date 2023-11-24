<template>
    <form @submit.prevent="submitReply" class="mt-4">
        <div v-if="parent_author">
            <label for="reply_content">You Reply to: {{ parent_author }}</label>
        </div>
        <div class="flex space-x-4 mt-4">
            <div>
                <label for="author">Your Username:</label>
                <input
                    v-model="user.name"
                    type="text"
                    name="author"
                    id="username"
                    class="w-full px-3 py-2 border rounded mb-2"
                />
            </div>
            <div>
                <label for="author_email">Your Email:</label>
                <input
                    v-model="user.email"
                    type="email"
                    name="email"
                    id="email"
                    class="w-full px-3 py-2 border rounded mb-2"
                    required
                />
            </div>
        </div>
        <label for="content">Your Post:</label>
        <textarea
            v-model="reply.content"
            name="content"
            id="content"
            class="w-full px-3 py-2 border rounded mb-2"
            required
        ></textarea>
        <input
            v-if="parent_post_id"
            type="hidden"
            :value="parent_post_id"
            name="parent_post_id"
        />
        <Button type="submit" title="Submit Reply" color="cyan" />
    </form>
</template>

<script>
import Button from "../ui/Button.vue";
export default {
    components: { Button },
    props: ["parent_post_id", "parent_author", "user", "onClick"],
    data() {
        return {
            reply: {
                content: "",
            },
        };
    },
    methods: {
        submitReply() {
            axios
                .post("/posts", {
                    author: this.user.name,
                    author_email: this.user.email,
                    content: this.reply.content,
                    parent_post_id: this.parent_post_id,
                })
                .then((response) => {
                    this.reply.content = "";
                    if (this.onClick) this.onClick();
                })
                .catch((error) => {
                    console.error("Error creating post:", error);
                });
        },
    },
};
</script>

<style></style>
