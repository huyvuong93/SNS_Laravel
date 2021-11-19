<template>
    <div :key="postUser.id">
        <div class="flex flex-grow">
            <img :src="'/images/user.svg'" style="width: 36px">
            <div class="ml-5">
                <a class="font-bold" :href="route('users.index', postUser.id)">
                    {{ postUser.name }}
                </a>
                <p>{{ postUser.city }}</p>
                <div class="flex flex-grow">
                    <form @submit.prevent="follow">
                        <input type="hidden" v-model="userId">
                        <button class="text-sm m-1 py-1 px-3 rounded-md bg-black text-white" type="submit">{{ followText }}</button>
                    </form>
                    <form @submit.prevent="sendFriendReq">
                        <input type="hidden" v-model="userId">
                        <button class="text-sm m-1 py-1 px-3 rounded-md bg-black text-white" type="submit">{{ friendText }}</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import Button from '@/Components/Button';
export default {
    name: 'Follow',
    components: {Button},
    props: {
        postUser: Object,
        followings: Array,
    },
    data () {
        return {
            userId: '',
            followText: 'Follow',
            friendText: 'Friend'
        }
    },
    methods: {
        follow() {
            axios.post(this.route('users.follow'), {
                userId: this.postUser.id
            }).then(() => {
                if (this.followText === 'Follow') {
                    this.followText = 'Unfollow';
                } else {
                    this.followText = 'Follow';
                }
            })
        },
        sendFriendReq() {
            axios.post(this.route('users.friend.request'), {
                userId: this.postUser.id
            }).then((response) => {
                console.log(response.data)
                if (this.followText === 'Friend') {
                    this.followText = 'Sent';
                } else {
                    this.followText = 'Friend';
                }
            })
        }
    },
    mounted() {
        if (this.followings.includes(this.postUser.id)) {
            this.followText = 'Unfollow';
        } else {
            this.followText = 'Follow';
        }
    }
};
</script>
