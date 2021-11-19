<template>
    <form @submit.prevent="like">
        <button class="border-none" id="likeBtn" type="submit">
            <span class="pr-1">{{ likes_count }}</span>
            <i :class="[likedClass_data]"></i>
        </button>
    </form>
</template>

<script>
import axios from 'axios';
import Button from '@/Components/Button';
export default {
    name: 'CommentLike',
    components: {Button},
    props: {
        id: Number,
        type: String,
        likesCount: Number,
        likedCommentIds: Array,
    },
    data() {
        return {
            likes_count: this.likesCount,
            likedClass_data: 'far fa-heart',
        }
    },
    methods: {
        like() {
            axios.post(this.route('like', this.type), {
                id: this.id
            }).then(response => {
                console.log(response.data);
                if (response.data.result['status'] === 'unlike') {
                    this.likedClass_data = 'far fa-heart';
                } else {
                    this.likedClass_data = 'fas fa-heart text-red-500';
                }
                this.likes_count = response.data.result['count'];
            })
        }
    },
    mounted() {
        if (this.likedCommentIds.includes(this.id)) {
            this.likedClass_data = 'fas fa-heart text-red-500';
        } else {
            this.likedClass_data = 'far fa-heart';
        }
    }
};
</script>

<style scoped>

</style>
