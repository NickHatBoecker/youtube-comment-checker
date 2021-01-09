<template>
    <div>
        <router-link class="d-block mb-3" :to="{ name: 'videosOverview' }">
            <b-icon icon="arrow-left"></b-icon>
            Back to Videos
        </router-link>

        <template v-if="currentVideo">
            <h1 class="mb-1">{{ currentVideo.title }}</h1>
            <p>{{ currentVideo.owner }}</p>
        </template>
        <h1 v-else>VIDEO DETAIL: {{ videoId }}</h1>

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
export default {
    name: 'VideoDetail',

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
