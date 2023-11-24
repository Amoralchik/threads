<template>
    <button
        @click="onClick"
        :class="`text-white rounded-lg p-2 bg-black hover:outline outline-${color}-300 outline-offset-1 outline-2`"
    >
        {{ title }}
    </button>
</template>

<script>
export default {
    props: ["onClick", "title", "color"],
};
</script>

<style></style>
