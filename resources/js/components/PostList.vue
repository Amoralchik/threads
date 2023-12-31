<template>
    <div class="max-w-5xl mx-auto">
        <div class="mt-2">
            <div class="p-6 bg-white overflow-hidden shadow sm:rounded-lg">
                <PostForm :user="user" />
            </div>
        </div>
        <PostComponent
            v-for="post in displayedPosts"
            :key="post.id"
            :post="post"
            :author="post.author"
            :user="user"
        />
        <Paginator
            class="mt-4"
            v-model:first="first"
            v-model:rows="rows"
            :rows="10"
            :totalRecords="postsList.length"
            :rowsPerPageOptions="[10, 20, 30]"
        ></Paginator>
    </div>
</template>

<script>
import Paginator from "primevue/paginator";
import Button from "primevue/button";
import PostComponent from "./Post.vue";
import PostForm from "./PostForm.vue";

export default {
    components: {
        PostComponent,
        PostForm,
        Button,
        Paginator,
    },
    props: {
        posts: {
            type: Array,
            required: true,
        },
    },
    computed: {
        displayedPosts() {
            const count = this.first + this.rows;
            return this.postsList.slice(this.first, count);
        },
    },
    methods: {
        goToPreviousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        goToNextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
            }
        },
        processPost(posts, newPost) {
            const addToParent = (parent, post) => {
                parent.descendants = parent.descendants || [];
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
            currentPage: 1,
            first: 0,
            rows: 10,
            postsPerPage: 10,
            lastPage: Math.ceil(this.posts.length / 10),
            user: {
                name: "",
                email: "",
            },
        };
    },
};
</script>
