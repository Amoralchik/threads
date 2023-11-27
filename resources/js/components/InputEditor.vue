<template>
    <Editor v-model="reply.content" name="content" id="content" required />
</template>

<script>
import Editor from "primevue/editor";

export default {
    components: {
        Editor,
    },
    props: ["reply"],
};
</script>

<style></style>
