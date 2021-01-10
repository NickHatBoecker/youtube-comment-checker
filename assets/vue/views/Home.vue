<template>
    <div>
        <h1 class="mb-4">Videos</h1>
        <add-url-form @add="addVideoId" />

        <b-row
            class="video-row mt-5"
            v-for="(video, index) in videos"
            :key="index"
            @open="openDetails(video)"
        >
            <b-col cols="12" sm="4" md="2" class="mb-3">
                <b-link @click="openDetails(video)">
                    <img
                        :src="video.thumbnail.url"
                        width="100%"
                        height="auto"
                        alt=""
                        title=""
                    />
                </b-link>
            </b-col>
            <b-col>
                <h2><b-link @click="openDetails(video)">{{ video.title }}</b-link></h2>
                <p class="h4 mt-3">{{ video.numNewComments > 0 ? `${video.numNewComments} new comment(s) available` : 'No new comments' }}</p>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import { clone } from 'ramda'
import AddUrlForm from '../components/AddUrlForm'

export default {
    name: 'Home',

    components: { AddUrlForm },

    data: () => ({ videos: [] }),

    computed: {
        videoIds () {
            return this.$store.getters.videoIds
        },

        lastCheck () {
            return this.$store.getters.lastCheck
        },
    },

    mounted () {
        this.getVideos()
    },

    methods: {
        async getVideos () {
            const { data } = await this.$http.get('/api/get-videos/', { params: { videoIds: JSON.stringify(this.videoIds) } })

            this.videos = data

            return this.videos
        },

        async addVideoId (videoId) {
            if (this.videoIds.find(x => x === videoId)) {
                alert('You already added this video')
                return
            }

            try {
                const videoIds = clone(this.videoIds)
                videoIds.push({ id: videoId, lastCheck: Date.now() })

                this.$store.dispatch('saveVideoIds', videoIds)
                this.getVideos()
            } catch (e) {
                alert(e.message)
            }
        },

        openDetails (video) {
            this.$store.commit('setCurrentVideo', video)
            this.$router.push({ name: 'videoDetail' })
        },
    },
}
</script>
