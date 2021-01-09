import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const STORE_VIDEO_IDS = 'nhb_youtube_comments_videos'

const Store = new Vuex.Store({
    state: {
        lastCheck: null,
        videoIds: [],
        currentVideo: null,
    },

    mutations: {
        setLastCheck (state, payload) {
            state.lastCheck = payload
        },

        setVideoIds (state, payload) {
            state.videoIds = payload
        },

        setCurrentVideo (state, payload) {
            state.currentVideo = payload
        },
    },

    actions: {
        initialiseStore ({ dispatch }) {
            dispatch('loadVideoIds')
        },

        saveVideoIds ({ commit }, payload) {
            localStorage.setItem(STORE_VIDEO_IDS, JSON.stringify(payload))
            commit('setVideoIds', payload)
        },

        loadVideoIds ({ commit }) {
            commit('setVideoIds',  JSON.parse(localStorage.getItem(STORE_VIDEO_IDS)) || [])
        },
    },

    getters: {
        lastCheck: s => s.lastCheck,
        videoIds: s => s.videoIds,
        currentVideo: s => s.currentVideo,
    },
})

export default Store
