<template>
    <button @click.prevent="onToggleSubscription" class="btn btn-danger" :disabled="loading">
        <span v-if="! loading">
            {{ owner ? '' : subscribed ? 'Unsubscribe' : 'Subscribe' }}
            {{ subscriptions.length }}
            {{ owner ? 'Subscribers' : '' }}
        </span>

        <span v-else>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </span>
    </button>
</template>

<script>
    import numeral from 'numeral';

    export default {
        props: {
            initialSubscriptions: {
                type: Array,
                required: true,
                default: () => []
            },

            channel: {
                type: Object,
                required: true,
                default: () => ({})
            }
        },

        data: function() {
            return {
                subscriptions: this.initialSubscriptions,
                loading: false
            }
        },

        computed: {
            subscribed() {
                if ( ! __auth() || this.channel.user_id === __auth().id)
                    return false;

                return !!this.subscription;
            },

            subscription() {
                if ( ! __auth())
                    return null;

                return this.subscriptions.find( (subscription) => subscription.user_id === __auth().id);
            },

            owner() {
                if (__auth() && this.channel.user_id === __auth().id)
                    return true;

                return false;
            },

            count() {
                return numeral(this.subscriptions.length).format('0a');
            }
        },

        methods: {
            onToggleSubscription() {
                if ( ! __auth())
                    return alert('Please login to subscribe.');

                if (this.owner)
                    return alert('You cannot subscribe to your channel');

                this.loading = true;

                if (this.subscribed) {
                    axios.delete(`/channels/${this.channel.id}/subscriptions/${this.subscription.id}`)
                        .then( () => {
                            this.subscriptions = this.subscriptions.filter( (s) => s.id !== this.subscription.id);
                        })
                        .finally( () => this.loading = false);
                } else {
                    axios.post(`/channels/${this.channel.id}/subscriptions`)
                        .then ( (response) => {
                            console.log(response.data);
                            this.subscriptions = [
                                ...this.subscriptions,
                                response.data
                            ];
                        })
                        .finally( () => this.loading = false);
                }

            }
        }
    };
</script>


