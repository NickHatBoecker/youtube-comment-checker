<template>
    <div>
        <app-comment v-bind="topLevelComment" />

        <div v-if="replies" class="ml-5">
            <app-comment
                v-for="(reply, replyIndex) in replies"
                :key="`reply-${replyIndex}`"
                v-bind="reply"
            />
        </div>
    </div>
</template>

<script>
import AppComment from '~/components/Comment'

export default {
    name: 'CommentThread',

    components: { AppComment },

    props: {
        topLevelComment: { type: Object, required: true },
        replies: { type: Array, default: null },
        isNew: { type: Boolean, required: true },
    },
}
</script>
