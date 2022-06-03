<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-body">
                    <div id="edit-form">
                        <div class="text-right">
                            <button @click="$emit('showEditTemplate', post)"><i class="fas fa-window-close"></i></button>
                        </div>
                        <form @submit.prevent="edit">
                            <input type="hidden" v-model="form.del_image_ids"/>
                            <textarea type="text" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.content"/>
                            <div v-if="post.images !== null" class="flex mt-2">
                                <div v-for="image in post.images" :key="image.id" :id="`img_${image.id}`" class="relative mr-2" style="width: 60px; height: 60px">
                                    <img class="object-cover h-full w-full hover:scale-75 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" :src="'storage/' + image.image_url">
                                    <a class="absolute top-0 right-0" @click="delImages(image.id)"><i class="fas fa-window-close"></i></a>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <div>
                                    <input type="file" id="selectFiles" multiple="multiple" @input="form.images = $event.target.files" style="display: none;" />
                                    <button type="button" value="" onclick="document.getElementById('selectFiles').click();"><i class="fas fa-image"></i></button>
                                    <label for="status" class="ml-2"><i class="fas fa-eye"></i></label>
                                    <select id="status" class="border-0 appearance-none pr-0" v-model="form.status">
                                        <option v-for="item in status" :value="item">{{ item }}</option>
                                    </select>
                                </div>
                                <div class="text-right">
                                    <Button type="submit">Update</Button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Button from '@/Components/Button';
export default {
    name: 'Action',
    components: {Button},
    props: {
        post: Object,
        status: Object,
    },
    data () {
        return {
            form: this.$inertia.form({
                content: this.post.content,
                status: this.post.status,
                del_image_ids: [],
                images: [],
            }),
        }
    },
    methods: {
        edit() {
            this.form.post(this.route('post.edit', this.post.id), {
                forceFormData: true,
                onSuccess: () => {
                    this.form.reset();
                    this.$emit('showEditTemplate', this.post);
                    window.location.reload();
                }
            })
        },
        delImages(id) {
            let del_img = document.getElementById('img_' + id);
            this.form.del_image_ids.push(id);
            del_img.style.opacity = '0.2';
            console.log(this.form.del_image_ids);
        }
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
    width: 40%;
    margin: 0px auto;
    padding: 10px 25px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: #42b983;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

</style>

