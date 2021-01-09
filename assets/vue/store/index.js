import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const Store = new Vuex.Store({
    state: {
        currentVideo: null,
    },

    mutations: {
        setCurrentVideo (state, payload) {
            state.currentVideo = payload
        },
    },

    getters: {
        currentVideo: s => s.currentVideo,
    },
})

export default Store
