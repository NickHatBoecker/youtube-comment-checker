import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const STORE_VIDEOS = 'nhb_youtube_comments_videos'

const Store = new Vuex.Store({
    state: {
        videos: [],
        currentVideo: null,
    },

    mutations: {
        setVideos (state, payload) {
            state.videos = payload
        },

        setCurrentVideo (state, payload) {
            state.currentVideo = payload
        },
    },

    actions: {
        initialiseStore ({ dispatch }) {
            dispatch('loadVideos')
        },

        saveVideos ({ commit }, payload) {
            localStorage.setItem(STORE_VIDEOS, JSON.stringify(payload))
            commit('setVideos', payload)
        },

        loadVideos ({ commit }) {
            commit('setVideos',  JSON.parse(localStorage.getItem(STORE_VIDEOS)) || [])
        },
    },

    getters: {
        videos: s => s.videos,
        currentVideo: s => s.currentVideo,
    },
})

export default Store
