<template>
    <div v-if="currentVideo">
        <router-link class="d-block mb-3" :to="{ name: 'videosOverview' }">
            <b-icon icon="arrow-left"></b-icon>
            Back to Videos
        </router-link>

        <h1 class="mb-1">{{ currentVideo.title }}</h1>
        <p>{{ currentVideo.owner }}</p>

        <iframe
            v-if="currentVideo"
            width="560"
            height="315"
            :src="`https://www.youtube-nocookie.com/embed/${currentVideo.id}`"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
        />

        <h2 class="h5 mt-2 mb-5" v-if="currentVideo">{{ currentVideo.numComments }} Comment(s)</h2>

        <comment-thread
            v-for="(commentThread, threadIndex) in currentVideo.threads"
            :key="`commentThread-${threadIndex}`"
            v-bind="commentThread"
        />
    </div>
</template>

<script>
import { clone } from 'ramda'
import CommentThread from '~/components/CommentThread'

export default {
    name: 'VideoDetail',

    components: { CommentThread },

    computed: {
        currentVideo () {
            return this.$store.getters.currentVideo
        },

        videoIds () {
            return this.$store.getters.videoIds
        },
    },

    mounted () {
        if (!this.currentVideo) {
            this.$router.push({ name: 'videosOverview' })
        }

        const videoIds = clone(this.videoIds)
        const index = videoIds.findIndex(x => x.id === this.$store.getters.currentVideo.id)
        videoIds[index].lastCheck = Date.now()

        this.$store.dispatch('saveVideoIds', videoIds)
    },
}
</script>
