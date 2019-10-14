<template>
    <div class="media my-3">
        <!-- <img width="30" height="30" class="rounded-circle mr-3" src="https://piscum.photos/id/42/200/200">-->

        <avatar class="mr-3" :username="comment.user.name" :size="30"></avatar>

        <div class="media-body">
            <h6 class="mt-0">{{ comment.user.name }}</h6>
            <small>{{ comment.body }}</small>

            <!-- Votes Component START -->
            <div class="d-flex">
                <votes
                        :default_votes="comment.votes"
                        entity_type="comment"
                        :entity_owner="comment.user.id"
                        :entity_id="comment.id">
                </votes>

                <button
                        @click="addingReply = !addingReply"
                        :class="['btn-sm', 'ml-2', {'btn-default' : ! addingReply, 'btn-danger' : addingReply}]" >
                    {{ addingReply ? 'Cancel' : 'Add Reply' }}
                </button>
            </div>
            <!-- Votes Component END -->

            <!-- Add new reply Start -->
            <div v-if="addingReply" class="form-inline my-4 w-full">
                <input v-model="body" type="text" class="form-control form-control-sm w-80">

                <button @click="onAddReply" class="btn btn-sm btn-primary">
                    <small>Add reply</small>
                </button>
            </div>
            <!-- Add new reply Start -->

            <!-- Replies Component START -->
            <replies ref="replies" :comment="comment"></replies>
            <!-- Replies Component END -->
        </div>
    </div>
</template>

<script>
    import Avatar from 'vue-avatar';
    import Replies from './replies';

    export default {
        name: "comment",
        
        components: {
            Avatar,
            Replies
        },

        props: {
            comment: {
                required: true,
                default: () => ({})
            },

            video: {
                required: true,
                default: () => ({})
            }
        },

        data() {
            return {
                body: '',
                addingReply: false
            };
        },

        methods: {
            onAddReply() {
                if ( ! this.body) return;

                axios.post(`/comments/${this.video.id}`, {
                    comment_id: this.comment.id,
                    body: this.body,
                })
                    .then( ({data}) => {
                        this.body = '';
                        this.addingReply = false;
                        this.$refs.replies.addReply(data)
                    })
            }
        }

    }
</script>

<style scoped>

</style>