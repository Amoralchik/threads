<template>
    <div class="max-w-5xl mx-auto">
        <div class="mt-2">
            <div
                class="p-6 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg"
            >
                <PostForm />
            </div>
        </div>
        <PostComponent
            v-for="post in postsList"
            :key="post.id"
            :post="post"
            :author="post.author"
        />
    </div>
</template>

<script>
import PostComponent from "./Post.vue";
import PostForm from "./PostForm.vue";

export default {
    components: {
        PostComponent,
        PostForm,
    },
    props: {
        posts: {
            type: Array,
            required: true,
        },
    },
    methods: {
        processPost(posts, newPost) {
            const addToParent = (parent, post) => {
                if (parent.id === post.parent_post_id) {
                    parent.descendants.unshift(post);
                    return true;
                }
                for (const child of parent.descendants) {
                    if (addToParent(child, post)) {
                        return true;
                    }
                }
                return false;
            };
            for (const post of posts) {
                if (addToParent(post, newPost)) {
                    return;
                }
            }
            posts.unshift(newPost);
        },
    },
    mounted() {
        Echo.channel("posts-socket").listen("PostCreated", (event) => {
            const newPost = event.post;
            if (newPost.parent_post_id) {
                this.processPost(this.postsList, newPost);
            } else {
                this.postsList.unshift(newPost);
            }
        });
    },
    data() {
        return {
            postsList: this.posts,
        };
    },
};
</script>
