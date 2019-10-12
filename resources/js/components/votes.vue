<template>
    <div>
        <i :class="['far fa-thumbs-up','thumbs-up', {'thumbs-up-active' : upVoted}]" @click="onVote('up')"></i>
            <!-- <i class="fas fa-thumbs-up"></i> -->
        {{ upVotesCount }}

        <i :class="['far fa-thumbs-down','thumbs-down', {'thumbs-down-active' : upVoted}]" @click="onVote('down')"></i>
        <!-- svg-thumbs-down -->
        {{ downVotesCount }}
    </div>
</template>

<script>
import numeral from 'numeral';

export default {
    props: {
        default_votes: {
            required: true,
            type: Array,
            default: () => []
        },

        entity_owner: {
            type: String,
            require: true,
            default: ''
        },

        entity_id: {
            required: true,
            type: String,
            default: ''
        }
    },

    data() {
        return {
            votes: this.default_votes
        };
    },

    computed: {
        upVotes() {
            return this.votes.filter( (v) => v.type === 'up');
        },

        downVotes() {
            return this.votes.filter( (v) => v.type === 'down');
        },

        upVotesCount() {
            return numeral(this.upVotes.length).format('0a');
        },

        downVotesCount() {
            return numeral(this.downVotes.length).format('0a');
        },

        upVoted() {
            if ( ! __auth() )
                return false;

            return !!this.upVotes.find( (v) => v.user_id === __auth().id);
        },

        downVoted() {
            if ( ! __auth() )
                return false;

            return !!this.downVotes.find( (v) => v.user_id === __auth().id);
        }
    },

     methods: {
        onVote(type) {
            if ( ! __auth())
                return alert('Please login to vote');

            if (__auth().id === this.entity_owner.user_id)
                return alert('You can not vote this item');

            if (type === 'up' && this.upVoted)
                return;

            if (type === 'down' && this.downVoted)
                return;

            axios.post(`/votes/${this.entity_id}/${type}`)
                .then( ({data}) => {
                    // Has this entity been voted by this user before?
                    if (this.upVoted || this.downVoted) {
                        // If yes, map will find the vote by this user and replace it with the fresh one from the server
                        this.votes = this.votes.map( (v) => {
                            if (v.user_id === __auth().id) {
                                return data;
                            }

                            return v;
                        })
                        // Else if the user has never voted on this entity
                    } else {
                        this.votes.push(data);
                    }
                });
        }
    }
}
</script>

<style>

</style>
