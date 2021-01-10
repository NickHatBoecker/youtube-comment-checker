<template>
    <form @submit.prevent="onSubmit">
        <b-form-input type="text" v-model="url" placeholder="Youtube URL (e.g. https://www.youtube.com/watch?v=XIwyh-QJuRc)" />
        <b-button variant="primary" block type="submit">ADD VIDEO</b-button>
    </form>
</template>

<script>
export default {
    name: 'AddUrlForm',

    data: () => ({ url: null }),

    methods: {
        onSubmit () {
            if (!this.url) {
                alert('Please enter a valid url')
                return
            }

            this.$emit('add', this.getIdFromUrl())
        },

        getIdFromUrl () {
            const regexp = /(?:\?|&)v=(.*?)(?:&|$)/
            const result = this.url.match(regexp)

            return result[1]
        },
    },
}
</script>

<style lang="scss" scoped>
</style>
