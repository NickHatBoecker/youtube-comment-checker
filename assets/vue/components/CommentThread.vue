<template>
    <div>
        <app-comment v-bind="topLevelComment" />

        <template v-if="replies">
            <app-comment
                v-for="(reply, replyIndex) in replies"
                :key="`commentThread-${threadIndex}-reply-${replyIndex}`"
                v-bind="reply"
            />
        </template>
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

<style lang="scss" scoped>
</style>
