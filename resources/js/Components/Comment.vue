<template>
    <div>
        <form @submit.prevent="comment">
            <textarea id="comment" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" type="text" v-model="content"/>
            <div class="text-right">
                <Button class="mt-2" type="submit">Comment</Button>
            </div>
        </form>
        <new-comment :comment="newComment"/>
    </div>
</template>

<script>
import axios from 'axios';
import Label from '@/Components/Label';
import Button from '@/Components/Button';
import NewComment from '@/Components/NewComment';
export default {
    name: 'Comment',
    components: {NewComment, Button, Label},
    props: {
        postId: Number,
    },
    data () {
        return {
            content: '',
            newComment: {},
        }
    },
    methods: {
        comment() {
            axios.post(this.route('post.comment', this.postId), {
                content: this.content,
            }).then(response => {
                console.log(response);
                this.newComment = response.data;
            })
        }
    }
};
</script>
