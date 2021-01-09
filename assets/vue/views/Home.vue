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
                <p class="h4 mt-3">{{ video.hasNewComments ? 'New comments available' : 'No new comments' }}</p>
            </b-col>
        </b-row>
    </div>
</template>

<script>
import { clone, isNil } from 'ramda'
import AddUrlForm from '../components/AddUrlForm'

export default {
    name: 'Home',

    components: { AddUrlForm },

    computed: {
        videos () {
            return this.$store.getters.videos
        },
    },

    mounted () {
    },

    methods: {
        async getVideo (videoId) {
            const { data } = await this.$http.get('/api/get-videos/', { params: { videoIds: [ videoId ] } })

            return isNil(data[0]) ? null : data[0]
        },

        async addVideoId (videoId) {
            if (this.videos.find(x => x.id === videoId)) {
                alert('You already added this video')
                return
            }

            try {
                const video = await this.getVideo(videoId)
                if (!video) {
                    alert(`Could not find video with id "${ videoId }"`)
                    return
                }

                const videos = clone(this.videos)
                videos.push(video)

                this.$store.dispatch('saveVideos', videos)
            } catch (e) {
                alert(e.message)
            }
        },

        openDetails (video) {
            this.$store.commit('setCurrentVideo', video)
            this.$router.push({ name: 'videoDetail', params: { videoId: video.id } })
        },
    },
}
</script>
