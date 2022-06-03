<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-body">
                    <div id="relative">
                        <button class="absolute top-0 right-0 pr-4" @click="$emit('showImageSlider', post)"><i class="fas fa-window-close"></i></button>
                        <div v-if="post.images !== null" class="relative flex mt-2">
<!--                            <div v-for="image in post.images" :key="image.id" :id="`img_${image.id}`" class="relative mr-2">-->
<!--                                <img class="object-cover h-full w-full hover:scale-75 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-full slider-image" :src="'storage/' + image.image_url">-->
<!--                            </div>-->
<!--                            <span id="prev" class="absolute left-0 top-1/2 -mt-3"><i class="fas fa-arrow-left"></i></span>-->
<!--                            <span id="next" class="absolute right-0 top-1/2 -mt-3"><i class="fas fa-arrow-right"></i></span>-->
                            <Carousel class="w-full">
                                <Slide class="align-items-center w-8/12 h-5/6" v-for="image in post.images" :key="image.id">
                                    <img class="object-fit slider-image" :src="'storage/' + image.image_url">
                                </Slide>
                                <template #addons>
                                    <Navigation />
                                </template>
                            </Carousel>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Button from '@/Components/Button';
import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Navigation } from 'vue3-carousel';
export default {
    name: 'ImageSlider',
    components: {
        Button,
        Carousel,
        Slide,
        Navigation,
    },
    props: {
        post: Object,
    },
    data () {
        return {
            images: this.post.images,
        }
    },
    methods: {

    }
};
</script>

<style scoped>
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.1);
    display: table;
    transition: opacity 0.3s ease;
}

.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
}

.modal-container {
    width: 98vw;
    margin: 0px auto;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

.slider-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>

