<template>
    <form @submit.prevent="onSubmit">
        <input type="text" v-model="url" />
        <button type="submit">Add</button>
    </form>
</template>

<script>
export default {
    name: 'AddUrlForm',

    data: () => ({ url: null }),

    methods: {
        onSubmit () {
            const videoId = this.getIdFromUrl()

            // @TODO save in store

            this.$emit('add', videoId)
        },

        getIdFromUrl () {
            const regexp = /[?:\?|&]v=(.*)&/
            const result = this.url.match(regexp)

            return result[1]
        },
    },
}
</script>

<style lang="scss" scoped>
</style>
