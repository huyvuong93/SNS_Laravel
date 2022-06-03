<template>
    <Head title="Post detail" />

    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <div>
                            <div class="flex justify-between" @mouseover="showUserTemplate(post)">
                                <div class="flex">
                                    <img :src="'/images/user.svg'" style="width: 36px">
                                    <div class="ml-5">
                                        <a class="font-bold" :href="route('users.index', post.user.id)">
                                            {{ post.user.name }}
                                        </a>
                                        <p>{{ post.created_at }}</p>
                                    </div>
                                </div>
                                <div @click="showMenu(post)">...</div>
                            </div>
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95">
                                <div v-if="user.id !== post.user.id" v-show="post.showUserFlag === true"
                                     class="absolute z-50 mt-2 rounded-md shadow-lg"
                                     :class="[widthClass]"
                                     style="display: none"
                                     @click="post.showUserFlag = false">
                                    <div class="rounded-md ring-1 ring-black ring-opacity-5">
                                        <div>
                                            <follow class="bg-white p-2" @mouseleave="hideUserTemplate(post)" v-show="post.showUserFlag" :post-user="post.user" :followings="followingIds"/>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                        <img v-for="image in post.images" :src="'storage/' + image.image_url" class="w-1/4" :alt="post.content"/>
                        {{ post.content }}
                        <like :id="post.id" :type="'post_like'" :likes-count="post.likes_count"/>
                        <div v-if="post.showMenuFlag">
                            <div @click="showEditTemplate(post)">Edit</div>
                            <div @click="del(post)">Delete</div>
                        </div>
                        <transition name="action">
                            <action v-if="post.showEditFlag" :post="post" :status="status"/>
                        </transition>
                        <comment :post-id="post.id"/>
                        <div v-for="comment in post.comments" :key="comment.id">
                            {{ comment.content }}
                            <like :id="comment.id" :type="'comment_like'" :likes-count="comment.likes_count"/>
                        </div>
                        <a :href="route('post.comment.more', post.id)">See more</a>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
import BreezeLabel from '@/Components/Label';
import {Head} from '@inertiajs/inertia-vue3';
import Button from '@/Components/Button';
import Comment from '@/Components/Comment';
import Follow from '@/Components/Follow';
import NewComment from '@/Components/NewComment';
import Like from '@/Components/PostLike';
import Action from '@/Components/Action';
import PostForm from '@/Components/PostForm';
import {debounce} from 'lodash/function';
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
export default {
    components: {
        PostForm,
        Action,
        Like,
        NewComment,
        Follow,
        Comment,
        Button,
        BreezeAuthenticatedLayout,
        BreezeLabel,
        BreezeDropdown,
        BreezeDropdownLink,
        Head,
    },
    name: 'Post',
    props: {
        status: Object,
        user: Object,
        post: Object,
        width: {
            default: '60'
        },
    },
    data() {
        return {
            showUserFlag: false,
            showEditFlag: false,
            showMenuFlag: false,
            post_data: this.post.data,
            page: 2,
        }
    },
    methods: {
        showUserTemplate(post) {
            post.showUserFlag = true;
        },
        hideUserTemplate(post) {
            post.showUserFlag = false;
        },
        showEditTemplate(post) {
            post.showEditFlag = !post.showEditFlag
        },
        showMenu(post) {
            post.showMenuFlag = !post.showMenuFlag
        },
    },
    mounted() {
        window.addEventListener('scroll', debounce((e) => {
            if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
                axios.get(`/?page=${this.page}`).then(response => {
                    this.posts_data.push(...response.data.data);
                    this.page++;
                });
            }
        }, 100));
        console.log(this.posts_data);
    },

    computed: {
        widthClass() {
            return {
                '60': 'w-60',
            }[this.width.toString()]
        },
    }
}
</script>

<style scoped>
</style>
