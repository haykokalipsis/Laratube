Vue.component('channel-uploads', {
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },

    data() {
        return {
            selected: false,
            videos: [], // At first videos uploaded from client, then they get replaced by the ones that come from server
            progress: {},
            uploads: [], // Temporary array for videos we get from server
            intervals: {} // After every upload is completed, set an interval that will make request to server every x seconds to fetch fresh copy of video, for percentage field
        }
    },

    methods: {
        onUpload() {
            this.selected = true;

            // Array.from converts any iterable to array. Fileslist is not an array but is iterable
            this.videos = Array.from(this.$refs.videos.files);
            const uploaders = this.videos.map( (video) => {
                this.progress[video.name] = 0;

                const formData = new FormData();
                formData.append('video', video);
                formData.append('title', video.name);
                formData.append('size', video.size);

                return axios.post(`/channels/${this.channel.id}/videos`, formData, {
                    onUploadProgress: (event) => {
                        this.progress[video.name] = Math.ceil((event.loaded / event.total) * 100);
                        console.log(this.progress[video.name]);

                        // Sometimes when you update nested objects, vue does`nt update correctly, we need this line
                        this.$forceUpdate();
                    }
                })
                    .then( (response) => {
                        this.uploads.push(response.data);
                    });

            });

            // axios.all(uploaders) means when all the uploads are done.
            axios.all(uploaders)
                .then( () => {
                    // We are replacing videos we got from client with the videos we got from server
                    this.videos = this.uploads;
                    
                    // Create interval for each video to get fresh copy of it every x second and get the percentage.  
                    this.videos.forEach( (video) => {
                        this.intervals[video.id] = setInterval( () => {
                            axios.get(`/videos/${video.id}`)
                                .then( ({data}) => {
                                    // Clear interval and stop fetching new copy, if fresh returned videos percentage is 100
                                    if (data.percentage === 100) {
                                        clearInterval(this.intervals[video.id]);
                                    }

                                    // We are mapping the videos to find the one we just got from the server and replace the matching one from videos array.
                                    this.videos = this.videos.map( (v) => {
                                        if (v.id === data.id) {
                                            return data;
                                        }

                                        return v;
                                    });
                                });
                        }, 3000);
                    });
                })
        }
    }
});