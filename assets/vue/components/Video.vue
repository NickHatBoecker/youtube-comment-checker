<template>
    <div>
        <iframe
            v-if="playable"
            width="560"
            height="315"
            :src="`https://www.youtube-nocookie.com/embed/${id}`"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        />
        <img
            v-else
            :src="thumbnail.url"
            :width="thumbnail.width"
            alt=""
            title=""
            @click="$emit('open')"
        />
        <h2 @click="$emit('open')">{{ title }}</h2>
        <p>{{ owner }}</p>
        <p>{{ hasNewComments ? 'New comments available' : 'No new comments' }}</p>
    </div>
</template>

<script>
export default {
    name: 'Video',

    props: {
        id: { type: String, required: true },
        thumbnail: { type: Object, required: true },
        title: { type: String, required: true },
        owner: { type: String, required: true },
        hasNewComments: { type: Boolean, required: true },
        playable: { type: Boolean, default: false },
    },
}
</script>

<style lang="scss" scoped>
</style>
