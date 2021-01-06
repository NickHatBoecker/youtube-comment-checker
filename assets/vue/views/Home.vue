<template>
    <div>
        <h1>Videos</h1>
        <add-url-form @add="addVideoId" />
        <div v-for="(video, index) in videos" :key="index" @click="openDetails">
            <img :src="video.thumbnail.url" :width="video.thumbnail.width" alt="" title="" />
            <h2>{{ video.title }}</h2>
            <p>{{ video.owner }}</p>
            <p>{{ video.hasNewComments ? 'New comments available' : 'No new comments' }}</p>
        </div>
    </div>
</template>

<script>
import AddUrlForm from '../components/AddUrlForm'

export default {
    name: 'Home',

    components: { AddUrlForm },

    data () {
        return {
            videos: [],
            videoIds: [],
        }
    },

    mounted () {
        if (this.videoIds) {
            this.getVideos()
        }
    },

    methods: {
        async getVideos () {
            const { data } = await this.$http.get('/api/get-videos/', { params: { videoIds: this.videoIds } })

            this.videos = data
        },

        addVideoId (videoId) {
            this.videoIds.push(videoId)
            this.getVideos()
        },

        openDetails () {
            // @TODO store video data in store
            this.$router.push({ name: 'videoDetail', params: { videoId: videoId } })
        },
    },
}
</script>

<style lang="scss" scoped>
</style>
