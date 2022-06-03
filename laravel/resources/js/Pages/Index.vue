<template>
    <Head title="Newsfeed" />

    <BreezeAuthenticatedLayout>
<!--        <template #header>-->
<!--            <h2 class="font-semibold text-xl text-gray-800 leading-tight">-->
<!--                Freeze-->
<!--            </h2>-->
<!--        </template>-->

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <post-form :status="status"/>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4"
                     v-for="post in posts_data" :key="post.id">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <div>
                            <div class="flex justify-between">
                                <div class="flex">
                                    <img :src="'/images/user.svg'" style="width: 36px">
                                    <div class="ml-5" @mouseover="showUserTemplate(post)">
                                        <a class="font-bold" :href="route('users.show', post.user.id)">
                                            {{ post.user.name }}
                                        </a>
                                        <p>{{ post.createdTime }}</p>
                                    </div>
                                </div>
                                <BreezeDropdown>
                                    <template #trigger>
                                        <button class="z-10" v-if="post.user.id === user.id" @click="showMenu(post)">...</button>
                                    </template>
                                    <template #content>
                                        <div class="py-2">
                                            <div class="px-3 hover:bg-gray-100" @click="showEditTemplate(post)">Edit</div>
                                            <div class="px-3 hover:bg-gray-100" @click="del(post)">Delete</div>
                                        </div>
                                    </template>
                                </BreezeDropdown>
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
                        <div class="mt-2 mb-2">
                            <img @click="showImageSlider(post)" class="post-image" v-for="image in post.images" :src="'storage/' + image.image_url" :class="sizeClass(post.images)" :alt="post.content"/>
                        </div>
                        <image-slider @showImageSlider="showImageSlider(post)" v-show="post.showImageSliderFlag === true" :post="post"/>
                        {{ post.content }}
                        <post-like :liked-post-ids="likedPostIds" :id="post.id" :type="'post_like'" :likes-count="post.likes_count"/>
                        <transition name="action">
                            <action @showEditTemplate="showEditTemplate(post)" v-show="post.showEditFlag === true" :post="post" :status="status"/>
                        </transition>
                        <transition name="delete-form">
                            <delete-form @del="del(post)" v-show="post.showDelFlag === true" :post="post"/>
                        </transition>
                        <comment :post-id="post.id"/>
                        <div v-for="comment in post.comments" :key="comment.id">
                            {{ comment.content }}
                            <comment-like :liked-comment-ids="likedCommentIds" :id="comment.id" :type="'comment_like'" :likes-count="comment.likes_count"/>
                        </div>
                        <a v-if="post.comments.length >= 3" :href="route('post.show', post.id)">See more</a>
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
import Action from '@/Components/Action';
import PostForm from '@/Components/PostForm';
import {debounce} from 'lodash/function';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import CommentLike from '@/Components/CommentLike';
import PostLike from '@/Components/PostLike';
import DeleteForm from '@/Components/DeleteForm';
import {forEach} from 'lodash/fp/_util';
import ImageSlider from '@/Components/ImageSlider';
export default {
    components: {
        ImageSlider,
        DeleteForm,
        CommentLike,
        PostForm,
        Action,
        PostLike,
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
    name: 'Index',
    props: {
        status: Object,
        user: Object,
        followingIds: Array,
        posts: Object,
        width: {
            default: '60'
        },
        likedPostIds: Array,
        likedCommentIds: Array,
    },
    data() {
        return {
            showUserFlag: false,
            showEditFlag: false,
            showMenuFlag: false,
            showDelFlag: false,
            showImageSliderFlag: false,
            posts_data: this.posts.data,
            page: 2,
        }
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
    methods: {
        showUserTemplate(post) {
            post.showUserFlag = true;
        },
        hideUserTemplate(post) {
            post.showUserFlag = false;
        },
        showMenu(post) {
            post.showMenuFlag = !post.showMenuFlag;
        },
        showEditTemplate(post) {
            post.showEditFlag = !post.showEditFlag;
        },
        showImageSlider(post)
        {
            post.showImageSliderFlag = !post.showImageSliderFlag;
        },
        del(post) {
            post.showDelFlag = !post.showDelFlag;
        },
        sizeClass(images) {
            if (Number(images.length) > 1) {
                return "w-1/4 object-fit";
            } else if (Number(images.length) === 1) {
                return "w-full";
            }
        },
        // postImgLayout(images) {
        //     let post_images = document.querySelectorAll('.post-image');
        //     console.log(post_images);
        //     if (Number(images.length) > 2) {
        //         for (let i = 0; i < post_images.length; i++) {
        //             post_images[0].classList.add('row-span-3 col-span-2');
        //             post_images[1].classList.add('row-span-2');
        //         }
        //         return "grid grid-rows-3 grid-flow-col gap-4";
        //     }
        // }
    },

    computed: {
        widthClass() {
            return {
                '60': 'w-60',
            }[this.width.toString()]
        },
    },
}
</script>

<style scoped>
</style>
