<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2"
         v-for="post in posts" :key="post.id">
        <div class="p-6 bg-white border-b border-gray-200">
            {{ post.user.id }}
            <a @mouseover="showUserTemplate" :href="route('users.index', post.user.id)">{{ post.user.name }}</a>
            <follow @mouseleave="hideUserTemplate" v-if="showFlag" :post-user="post.user"/>
            <img v-for="image in post.images" :src="'storage/' + image.image_url" class="w-1/4" :alt="post.content"/>
            {{ post.content }}
            <comment :post-id="post.id"/>
        </div>
    </div>
</template>

<script>
import Follow from '@/Components/Follow';
export default {
    name: 'LoadMore',
    components: {
        Follow
    },
    props: {
        posts: Object
    },
    data () {
        return {
            showFlag: false,
        }
    },
    methods: {
        showUserTemplate () {
            this.showFlag = true;
        },
        hideUserTemplate () {
            this.showFlag = false;
        }
    }
};
</script>
