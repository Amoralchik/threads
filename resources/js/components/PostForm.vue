<template>
    <form @submit.prevent="submitReply" class="mt-4">
        <div v-if="author">
            <label for="reply_content">Your Reply: {{ author }}</label>
        </div>
        <label for="author">Your Username:</label>
        <input
            v-model="reply.author"
            type="text"
            name="author"
            id="username"
            class="w-full px-3 py-2 border rounded mb-2"
        />
        <label for="author_email">Your Email:</label>
        <input
            v-model="reply.email"
            type="email"
            name="email"
            id="email"
            class="w-full px-3 py-2 border rounded mb-2"
            required
        />
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
        <button
            type="submit"
            class="mt-2 bg-blue-500 text-white py-2 px-4 rounded"
        >
            Submit Reply
        </button>
    </form>
</template>

<script>
export default {
    props: ["parent_post_id", "author"],
    data() {
        return {
            reply: {
                author: "",
                email: "",
                content: "",
            },
        };
    },
    methods: {
        submitReply() {
            axios
                .post("/posts", {
                    author: this.reply.author,
                    author_email: this.reply.email,
                    content: this.reply.content,
                    parent_post_id: this.parent_post_id,
                })
                .then((response) => {
                    this.reply = {
                        author: "",
                        email: "",
                        content: "",
                    };
                    this.showForm = false;
                    this.$forceUpdate();
                })
                .catch((error) => {
                    console.error("Error creating post:", error);
                });
        },
    },
};
</script>

<style></style>
