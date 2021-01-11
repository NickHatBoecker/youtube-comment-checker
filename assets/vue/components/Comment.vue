<template>
    <div class="d-flex mb-3">
        <div class="mr-3">
            <img :src="thumbnail" alt="" title="" />
        </div>
        <div>
            <p class="m-0">
                <span class="font-weight-bold">{{ owner }}</span>
                <b-badge v-if="isNew" class="new-label" variant="danger">New</b-badge>
            </p>
            <dynamic-content tag="p" :html="text" />
        </div>
    </div>
</template>

<script>
import DynamicContent from '~/components/DynamicContent'

export default {
    name: 'Comment',

    components: { DynamicContent },

    props: {
        isNew: { type: Boolean, required: true },
        thumbnail: { type: String, required: true },
        owner: { type: String, required: true },
        text: { type: String, required: true },
        published: { type: Number, required: true },
    },
}
</script>
