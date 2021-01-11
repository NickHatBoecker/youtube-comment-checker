<template>
    <!-- eslint-disable vue/no-v-html -->
    <component
        :is="tag"
        class="js-dynamic-content"
        @click="$emit('click', $event)"
        v-html="html"
    />
    <!-- eslint-enable vue/no-v-html -->
</template>

<script>
export default {
    props: {
        html: {
            type: String,
            required: true,
        },
        tag: {
            type: String,
            default: 'div',
        },
    },
}
</script>
