<template>
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="p-4 bg-white border-b border-gray-200">
                <div class="relative">
                    <img class="mx-auto" :src="'/images/user.svg'" style="width: 80px"/>
                    <button class="absolute top-0 right-0" @click="open = ! open"><i class="fas fa-user-edit"></i></button>
                </div>
                <div class="text-center"><p><strong>{{ user.name }}</strong></p></div>
                <div class="text-center"><p>{{ user.self_introduce }}</p></div>
                <table>
                    <tr>
                        <td><i class="fas fa-city"></i></td>
                        <td><span class="ml-4"><strong>{{ user.city }}</strong></span></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-pin"></i></td>
                        <td><span class="ml-4"><strong>{{ user.address }}</strong></span></td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-shopping-bag"></i></td>
                        <td><span class="ml-4"><strong>{{ user.job }}</strong></span></td>
                    </tr>
                </table>
            </div>
            <transition v-if="open === true">
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-container">
                            <div class="modal-body">
                                <div id="edit-form">
                                    <div class="text-right" @click="open = ! open"><span><i class="fas fa-window-close"></i></span></div>
                                    <form @submit.prevent="edit">
                                        <div>
                                            <BreezeLabel for="name" value="Name" />
                                            <BreezeInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                                        </div>

                                        <div>
                                            <BreezeLabel for="city" value="City" />
                                            <BreezeInput id="city" type="text" class="mt-1 block w-full" v-model="form.city" required autofocus autocomplete="city" />
                                        </div>

                                        <div>
                                            <BreezeLabel for="address" value="Address" />
                                            <BreezeInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" autofocus autocomplete="address" />
                                        </div>

                                        <div>
                                            <BreezeLabel for="job" value="Job" />
                                            <BreezeInput id="job" type="text" class="mt-1 block w-full" v-model="form.job" autofocus autocomplete="job" />
                                        </div>
                                        <div class="mt-4">
                                            <BreezeLabel for="self_introduce" value="Self Introduce" />
                                            <textarea id="self_introduce" type="text" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.self_introduce" autocomplete="self_introduce"/>
                                        </div>

                                        <div class="flex items-center justify-end mt-4">
                                            <BreezeButton>
                                                Update
                                            </BreezeButton>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import BreezeButton from '@/Components/Button.vue'
import BreezeInput from '@/Components/Input.vue'
import BreezeLabel from '@/Components/Label.vue'
import BreezeValidationErrors from '@/Components/ValidationErrors.vue'
import {Head, Link} from '@inertiajs/inertia-vue3';
export default {
    name: 'MyProfile',
    components: {
        BreezeButton,
        BreezeInput,
        BreezeLabel,
        BreezeValidationErrors,
        Head,
        Link,
    },
    props: {
        user: Object,
    },
    data() {
        return {
            open: false,
            form: this.$inertia.form({
                name: this.user.name,
                city: this.user.city,
                address: this.user.address,
                job: this.user.job,
                self_introduce: this.user.self_introduce,
            })
        }
    },
    methods: {
        edit() {
            this.form.post(this.route('profile.edit'), {
                onSuccess: () => {
                    this.form.reset();
                    this.open = false;
                }
            })
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
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 5px;
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
