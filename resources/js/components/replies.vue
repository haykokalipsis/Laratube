<template>
    <div>
        <!-- Add new reply END -->

        <!-- Reply block START -->

        <div class="media mt-3" v-for="reply in replies.data">
            <a class="mr-3">
                <avatar class="mr-3" :username="reply.user.name" :size="30"></avatar>
            </a>

            <!-- Reply body START-->
            <div class="media-body">
                <h6 class="mt-0">{{ reply.user.name }}</h6>
                <small>{{ reply.body }}</small>
            </div>
            <!-- Reply body END -->

            <!-- Votes Component START -->
            <votes
                    :default_votes="reply.votes"
                    entity_type="comment"
                    :entity_owner="reply.user.id"
                    :entity_id="reply.id">
            </votes>
            <!-- Votes Component END -->
        </div>
        <!-- Reply block END -->

        <div class="text-center my-2">
            <button
                    v-if="comment.repliesCount > 0 && replies.next_page_url"
                    @click="onFetchReplies" class="btn btn-sm btn-primary">
                Load Replies {{ comment.repliesCount }}
            </button>
        </div>
    </div>
</template>

<script>
    import Avatar from 'vue-avatar';

    export default {
        name: "replies",

        props: ['comment'],

        components: {
            Avatar
        },

        data() {
            return {
                replies: {
                    data: [],
                    next_page_url: `/comments/${this.comment.id}/replies`
                }
            }
        },
        
        methods: {
            onFetchReplies() {
                axios.get(this.replies.next_page_url)
                    .then( ({data}) => {
                        this.replies = {
                            ...data,
                            data: [
                                ...this.replies.data,
                                ...data.data
                            ]
                        }
                    })
            },

            addReply(reply) {

                let items = [
                    reply, // New comment that came from server (Our comment)
                    ...this.replies.data // Existing comments
                ];

                this.replies = {
                    ...this.replies,

                    data: items
                };

                // this.replies = {
                //     ...this.replies,
                //     data: [
                //         reply,
                //         ...this.replies.data
                //     ]
                //
                //
                // };
            }
        }
    }
</script>

<style scoped>

</style>