<template>
    <div>
        <h1>Videos</h1>
        <add-url-form @add="addVideoId" />
        <app-video
            v-for="(video, index) in videos"
            :key="index"
            v-bind="video"
            @open="openDetails(video)"
        />
    </div>
</template>

<script>
import AddUrlForm from '../components/AddUrlForm'
import AppVideo from '../components/Video'

export default {
    name: 'Home',

    components: { AddUrlForm, AppVideo },

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

        openDetails (video) {
            this.$store.commit('setCurrentVideo', video)
            this.$router.push({ name: 'videoDetail', params: { videoId: video.id } })
        },
    },
}
</script>

<style lang="scss" scoped>
</style>
