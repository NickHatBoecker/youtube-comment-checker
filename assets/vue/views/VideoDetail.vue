<template>
    <div>
        <h1>VIDEO DETAIL: {{ videoId }}</h1>

        <router-link :to="{ name: 'videosOverview' }">Back to videos</router-link>

        <app-video v-if="currentVideo" v-bind="currentVideo" :playable="true" />

        <h2>{{ currentVideo.numComments }} Comment(s)</h2>

        <div v-for="(commentThread, threadIndex) in commentThreads" :key="`commentThread-${threadIndex}`">
            <span v-if="commentThread.isNew">NEW</span>
            <img :src="commentThread.topLevelComment.thumbnail" alt="" title="" />
            <p>{{ commentThread.topLevelComment.owner }}</p>
            <p>{{ commentThread.topLevelComment.text }}</p>

            <template v-if="commentThread.replies">
                <div v-for="(reply, replyIndex) in commentThread.replies" :key="`commentThread-${threadIndex}-reply-${replyIndex}`">
                    <span v-if="reply.isNew">NEW</span>
                    <img :src="reply.thumbnail" alt="" title="" />
                    <p>{{ reply.owner }}</p>
                    <p>{{ reply.text }}</p>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
import AppVideo from '../components/Video'

export default {
    name: 'VideoDetail',

    components: { AppVideo },

    props: {
        videoId: { type: String, required: true },
    },

    data () {
        return {
            commentThreads: [],
        }
    },

    computed: {
        currentVideo () {
            return this.$store.getters.currentVideo
        },
    },

    mounted () {
        if (!this.currentVideo) {
            this.$router.push({ name: 'videosOverview' })
            return
        }

        this.getComments()
    },

    methods: {
        async getComments () {
            const { data } = await this.$http.get(`/api/get-comments/${this.videoId}/`)

            this.commentThreads = data
        },
    },
}
</script>

<style lang="scss" scoped>
</style>
