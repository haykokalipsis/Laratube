<template>
    <div class="card mt-5 p-5">
        <!-- Add new comment Start -->
        <div v-if="authenticated" class="form-inline my-4 f-full">
            <input v-model="newComment" type="text" class="form-control form-control-sm w-80">

            <button @click="onAddComment" class="btn btn-sm btn-primary">
                <small>Add comment</small>
            </button>
        </div>
        <!-- Add new comment END -->

        <!-- Comment component START -->
        <comment
                v-for="comment in comments.data" :key="comment.id"
                :comment="comment"
                :video="video">
        </comment>
        <!-- Comment component END -->

        <!-- Load more comments button START -->
        <div class="text-center">
            <button v-if="comments.next_page_url" @click="onFetchComments" class="btn btn-success">
                Load more
            </button>

            <span v-else>No more comments</span>
        </div>
        <!-- END Load more comments button END -->
    </div>
</template>

<script>
    import Comment from './comment';

    export default {
        name: "comments",

        components: {
            Comment
        },

        props: {
            video: {
                required: true,
                type: Object,
                default: () => ({})
            }
        },

        data() {
            return {
                comments: {
                    data: [],
                    next_page_url: `/videos/${this.video.id}/comments`
                },
                newComment: ''
            }
        },

        computed: {
            authenticated() {
                return __auth();
            }
        },

        mounted() {
            this.onFetchComments();
        },

        methods: {
            onFetchComments() {
                axios.get(this.comments.next_page_url)
                    .then( ({data}) => {
                        let distinctData = this.comments.data;

                        data.data.forEach( (item) => {
                            let found = this.comments.data.find( (c) => {
                                    return c.id === item.id;
                                });

                            if ( !found)
                                distinctData.push(item);
                        });

                        this.comments = {
                            // All properties like current page, previous_page_url, next_page_url, from, to, etc and data array with records/objects/models
                            ...data,

                            // data property of response.data, contains array with records/objects/models.
                            data: [
                                // ...this.comments.data, // existing records/objects/models
                                // ...data // new records/objects/models from response.data.data

                                ...distinctData // New version
                            ]

                            // Внизу скрипта я закоментил пример получаемого объекта comments, шоб понять чё мы получаем тут.

                        };
                    })
            },

            onAddComment() {
                if ( ! this.newComment) return;

                axios.post(`/comments/${this.video.id}`, {
                    body: this.newComment,
                })
                    .then( ({data}) => {
                        this.comments = {
                            ...this.comments,

                            data: [
                                data, // New comment that came from server (Our comment)
                                ...this.comments.data // Existing comments
                            ]
                        };

                        /*
                        TODO come out with some idea to fix this problem:
                         After adding new comment/reply, we return it from the server and push in comments/replies array
                         But when we click button to fetch more comments/replies, we fetch (with paginate) the newly created comments/replies too.
                        */
                        // console.log(this.comments);
                    });
            }
        }
    }

    // <!-- onFetchComments method-->
    // <!-- Не важно на сервере метод возвращяет просто коллекцию или коллекцию с paginate(), axios получает объект response, в котором кажется всё одинаково кроме свойства data -->
    // <!-- Если на сервере возвращяется просто коллекция, то в axios, объекте response, свойстве data будет просто массив из моделей/объектов-->
    // <!-- Если на сервере возвращяется коллекция с paginate(), то в axios, объекте response, свойстве data будут свойства пагинации, и объект data (да, в объекте response свойство data в котором ещё одно свойство data) в котором уже массив из моделей/объектов-->
    // <!-- response.data returns object which looks like this-->
    
    // Объект response
    // let response = {
    //     config: {}, // Мне не интересно, не буду описывать
    //
    //     // If server returns simple collection. records/objects/models
    //     data: [
    //         {
    //             id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //             body: "comment 1",
    //             comment_id: null,
    //             created_at: "2019-10-13 14:05:55",
    //             replies: [],
    //             repliesCount: 0,
    //             user: {
    //                 created_at: "2019-10-13 14:05:50",
    //                 email: "kirlin.meta@example.org",
    //                 email_verified_at: "2019-10-13 14:05:50",
    //                 id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 name: "Deangelo Kovacek",
    //                 updated_at: "2019-10-13 14:05:50"
    //             },
    //             user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //             votes: []
    //         },
    //         {
    //             id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //             body: "comment 2",
    //             comment_id: null,
    //             created_at: "2019-10-13 14:05:55",
    //             replies: [],
    //             repliesCount: 0,
    //             user: {
    //                 created_at: "2019-10-13 14:05:50",
    //                 email: "kirlin.meta@example.org",
    //                 email_verified_at: "2019-10-13 14:05:50",
    //                 id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 name: "Deangelo Kovacek",
    //                 updated_at: "2019-10-13 14:05:50"
    //             },
    //             user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //             votes: []
    //         },
    //         {
    //             id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //             body: "comment 3",
    //             comment_id: null,
    //             created_at: "2019-10-13 14:05:55",
    //             replies: [],
    //             repliesCount: 0,
    //             user: {
    //                 created_at: "2019-10-13 14:05:50",
    //                 email: "kirlin.meta@example.org",
    //                 email_verified_at: "2019-10-13 14:05:50",
    //                 id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 name: "Deangelo Kovacek",
    //                 updated_at: "2019-10-13 14:05:50"
    //             },
    //             user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //             votes: []
    //         },
    //     ],
    //
    //     // If server returns paginated data. Pagination data and records/objects/models
    //     data: {
    //         current_page: 1,
    //         last_page: 4,
    //         prev_page_url: null,
    //         next_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=2",
    //         first_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=1",
    //         last_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=4",
    //         path: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments",
    //         from: 1,
    //         to: 15,
    //         per_page: 15,
    //         total: 50,
    //
    //         // records/objects/models
    //         data: [
    //             {
    //                 id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //                 body: "comment 1",
    //                 comment_id: null,
    //                 created_at: "2019-10-13 14:05:55",
    //                 replies: [],
    //                 repliesCount: 0,
    //                 user: {
    //                     created_at: "2019-10-13 14:05:50",
    //                     email: "kirlin.meta@example.org",
    //                     email_verified_at: "2019-10-13 14:05:50",
    //                     id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                     name: "Deangelo Kovacek",
    //                     updated_at: "2019-10-13 14:05:50"
    //                 },
    //                 user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 votes: []
    //             },
    //             {
    //                 id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //                 body: "comment 2",
    //                 comment_id: null,
    //                 created_at: "2019-10-13 14:05:55",
    //                 replies: [],
    //                 repliesCount: 0,
    //                 user: {
    //                     created_at: "2019-10-13 14:05:50",
    //                     email: "kirlin.meta@example.org",
    //                     email_verified_at: "2019-10-13 14:05:50",
    //                     id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                     name: "Deangelo Kovacek",
    //                     updated_at: "2019-10-13 14:05:50"
    //                 },
    //                 user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 votes: []
    //             },
    //             {
    //                 id: "8bee812f-65e2-4481-8512-12a6138bbc54",
    //                 body: "comment 3",
    //                 comment_id: null,
    //                 created_at: "2019-10-13 14:05:55",
    //                 replies: [],
    //                 repliesCount: 0,
    //                 user: {
    //                     created_at: "2019-10-13 14:05:50",
    //                     email: "kirlin.meta@example.org",
    //                     email_verified_at: "2019-10-13 14:05:50",
    //                     id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                     name: "Deangelo Kovacek",
    //                     updated_at: "2019-10-13 14:05:50"
    //                 },
    //                 user_id: "0ae01e74-5fc2-458d-b315-910ffbd6eed5",
    //                 votes: []
    //             },
    //         ],
    //     },
    //
    //     headers: {
    //         "cache-control": "no-cache, private",
    //         "connection": "Keep-Alive",
    //         "content-type": "application/json",
    //         "date": "Mon, 14 Oct 2019 12:49:20 GMT",
    //         "keep-alive": "timeout=5, max=97",
    //         "server": "Apache/2.4.39 (Win64) OpenSSL/1.1.1c PHP/7.3.7",
    //         "transfer-encoding": "chunked",
    //         "x-powered-by": "PHP/7.3.7"
    //     },
    //     request: {}, // Мне не интересно, не буду описывать
    //     status: 200,
    //     statusText: "OK"
    // };

    // Объект this.comments
    // let comments = {
    //     current_page: 1,
    //
    //     // Comment model/object/record. Я не стал записывать.
    //     data: [
    //         {},
    //         {},
    //         {},
    //         {},
    //     ],
    //
    //     // Pagination data
    //     first_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=1",
    //     from: 1,
    //     last_page: 4,
    //     last_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=4",
    //     next_page_url: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments?page=2",
    //     path: "http://laratube.test/videos/a2eb8a29-56ca-47f0-b091-7b8be706e624/comments",
    //     per_page: 15,
    //     prev_page_url: null,
    //     to: 15,
    //     total: 50
    // }

</script>




<style scoped>

</style>