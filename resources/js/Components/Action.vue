<template>
    <div class="modal-mask" v-show="open" @click="open = false">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-body">
                    <div id="edit-form">
                        <form @submit.prevent="edit">
                            <textarea type="text" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.content"/>
                            <label for="status">Who can see this ?</label>
                            <select id="status" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md" v-model="form.status">
                                <option v-for="item in status" :value="item">{{ item }}</option>
                            </select>
                            <div class="text-right">
                                <Button type="submit">Update</Button>
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
            }),
            open: true,
        }
    },
    methods: {
        edit() {
            this.form.post(this.route('post.edit', this.post.id), {
                onSuccess: () => this.form.reset()
            })
        },
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
    padding: 20px 30px;
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

.modal-body {
    margin: 20px 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

</style>

