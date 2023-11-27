<template>
    <form @submit.prevent="submitReply" class="mt-4">
        <div v-if="parent_author">
            <label for="reply_content">You Reply to: {{ parent_author }}</label>
        </div>
        <div class="flex space-x-4 mt-4">
            <InputGroup>
                <InputGroupAddon>
                    <i class="pi pi-user"></i>
                </InputGroupAddon>
                <InputText
                    v-model="user.name"
                    type="text"
                    name="author"
                    id="username"
                    pattern="[a-zA-Z0-9]+"
                    required
                />
            </InputGroup>
            <InputGroup>
                <InputGroupAddon for="author_email"
                    ><i class="pi pi-user"></i
                ></InputGroupAddon>
                <InputText
                    v-model="user.email"
                    type="email"
                    name="email"
                    id="email"
                    required
                />
            </InputGroup>
        </div>
        <label for="content">Your Post:</label>
        <Splitter style="height: 300px" class="mb-5">
            <SplitterPanel
                class="flex align-items-center justify-content-center"
            >
                <InputEditor :reply="reply" />
            </SplitterPanel>
            <SplitterPanel
                class="flex align-items-center justify-content-center"
            >
                <div class="output p-2" v-html="output"></div>
            </SplitterPanel>
        </Splitter>
        <input
            v-if="parent_post_id"
            type="hidden"
            :value="parent_post_id"
            name="parent_post_id"
        />
        <Button type="submit" label="Submit Reply" />
    </form>
</template>

<script>
import Splitter from "primevue/splitter";
import SplitterPanel from "primevue/splitterpanel";
import Button from "primevue/button";
import InputGroup from "primevue/inputgroup";
import InputGroupAddon from "primevue/inputgroupaddon";
import InputText from "primevue/inputtext";

import { marked } from "marked";
import DOMPurify from "dompurify";
import InputEditor from "./InputEditor.vue";

export default {
    components: {
        Button,
        InputGroup,
        InputGroupAddon,
        InputText,
        SplitterPanel,
        Splitter,
        InputEditor,
    },
    props: ["parent_post_id", "parent_author", "user", "onClick"],
    data() {
        return {
            reply: {
                content: "",
            },
        };
    },
    computed: {
        output() {
            return DOMPurify.sanitize(marked(this.reply.content));
        },
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
