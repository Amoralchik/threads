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
        <div class="flex space-x-4 items-center mt-4">
            <Button
                :onClick="goToPreviousPage"
                title="Previous"
                color="cyan"
                :disabled="currentPage === 1"
            />
            <span>Page {{ currentPage }}</span>
            <Button :onClick="goToNextPage" title="Next" color="cyan" />
        </div>
    </div>
</template>

<script>
import PostComponent from "./Post.vue";
import PostForm from "./PostForm.vue";
import Button from "../ui/Button.vue";

export default {
    components: {
        PostComponent,
        PostForm,
        Button,
    },
    props: {
        posts: {
            type: Array,
            required: true,
        },
    },
    computed: {
        displayedPosts() {
            const startIndex = (this.currentPage - 1) * this.postsPerPage;
            const endIndex = startIndex + this.postsPerPage;
            return this.postsList.slice(startIndex, endIndex);
        },
    },
    methods: {
        goToPreviousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        goToNextPage() {
            const lastPage = Math.ceil(
                this.postsList.length / this.postsPerPage
            );
            if (this.currentPage < lastPage) {
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
            postsPerPage: 5,
            user: {
                name: "",
                email: "",
            },
        };
    },
};
</script>
