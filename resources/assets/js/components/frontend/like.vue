<template>
        <a  href="javascript:void(0);"  v-if="isLiked" @click.prevent="unLike(post)" class="btn text-grey hoverRed paddingUnset">
          <i class="fa fa-heart post-like-click"></i>
        </a>

        <a  href="javascript:void(0);"  v-else @click.prevent="like(post)" class="btn text-grey hoverRed paddingUnset">
          <i class="fa fa-heart-o post-like-click"></i>
        </a>
</template>

<script>
    export default {
        props: ['post', 'liked'],

        data: function() {
            return {
                isLiked: '',
            }
        },

        mounted() {
            this.isLiked = this.isLike ? true : false;
        },

        computed: {
            isLike() {
                return this.liked;
            },
        },

        methods: {
            like(post) {
                this.isLiked = true;

                axios.post('/like/'+post)
                    .then(function (response) {
                        response => this.isLiked = true
                    })
                    .catch(response => console.log(response.data));
            },

            unLike(post) {
                this.isLiked = false;
                
                axios.post('/unlike/'+post)
                    .then(response => this.isLiked = false)
                    .catch(response => console.log(response.data));
            }
        }
    }
</script>
