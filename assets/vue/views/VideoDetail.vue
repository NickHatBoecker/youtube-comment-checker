<template>
    <div v-if="currentVideo" id="to-pdf">
        <div class="d-flex u-flex--space-between u-flex--center-vertically mb-3">
            <router-link class="d-block" data-html2canvas-ignore :to="{ name: 'videosOverview' }">
                <b-icon icon="arrow-left"></b-icon>
                Back to Videos
            </router-link>

            <button class="btn btn-primary" data-html2canvas-ignore @click="exportToPdf">Export</button>
        </div>

        <h1 class="mb-1">{{ currentVideo.title }}</h1>
        <p>{{ currentVideo.owner }}</p>

        <div data-html2canvas-ignore>
            <iframe
                v-if="currentVideo"
                width="560"
                height="315"
                :src="`https://www.youtube-nocookie.com/embed/${currentVideo.id}`"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
            />
        </div>

        <h2 class="h5 mt-2 mb-5" v-if="currentVideo">{{ currentVideo.numComments }} Comment(s)</h2>

        <comment-thread
            v-for="(commentThread, threadIndex) in currentVideo.threads"
            :key="`commentThread-${threadIndex}`"
            v-bind="commentThread"
            class="comment-thread"
        />
    </div>
</template>

<script>
import { clone } from 'ramda'
import html2pdf from 'html2pdf.js'
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

    methods: {
        exportToPdf () {
            let filename = this.currentVideo.title.replace(' ', '_')
            filename = filename.replace(/[^a-zA-Z0-9_-]/g, '')

            html2pdf(document.getElementById('to-pdf'), {
                enableLinks: false,
                margin: 10,
                pagebreak: {
                    mode: 'avoid-all',
                    avoid: '.comment-thread',
                },
                filename,
            })
        },
    },
}
</script>
