<template>
    <div class="modal-mask">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Delete Post</h3>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="del">
                        <div class="mt-4">
                            <h3 class="text-center">Are you sure ?</h3>
                            <div class="text-center mt-2">
                                <Button class="mr-4" type="submit">Delete</Button>
                                <div class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150" @click="cancel">Cancel</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Button from '@/Components/Button';
export default {
    name: 'DeleteForm',
    components: {
        Button
    },
    props: {
        post: Object,
        status: Object,
    },
    data() {
        return {
            //
        }
    },
    methods: {
        del() {
            this.$inertia.delete(this.route('post.delete', this.post.id), {
                onSuccess: () => {
                    this.$emit('del', this.post);
                    window.location.reload();
                }
            })
        },
        cancel() {
            this.$emit('del', this.post);
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
    padding: 0 0 15px 0;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    font-family: Helvetica, Arial, sans-serif;
}

.modal-header h3 {
    margin-top: 0;
    color: white;
    padding: 10px 0;
    border-radius: 2px 2px 0 0;
    text-align: center;
    --tw-bg-opacity: 1;
    background-color: rgba(31, 41, 55, var(--tw-bg-opacity));
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
}

</style>
