import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home'
import VideoDetail from '../views/VideoDetail'

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
        { name: 'videosOverview', path: "/videos", component: Home },
        { name: 'videoDetail', path: "/:videoId", component: VideoDetail, props: true },
        { path: "*", redirect: "/videos" },
    ]
});
