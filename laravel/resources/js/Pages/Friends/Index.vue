<template>
    <Head title="Friends" />
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <form @submit.prevent="search">
                            <div class="flex">
                                <input id="filter" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md w-full" type="text" v-model="form.filter" placeholder="Search in Friends"/>
                                <button class="mx-2" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4"
                     v-for="friend in friends.data" :key="friend.id">
                    <div class="p-4 bg-white border-b border-gray-200 grid grid-cols-3 gap-1">
                        <div class="col-span-1">
                            <img class="mx-auto" :src="'/images/user.svg'" style="width: 60%"/>
                        </div>
                        <div class="relative col-span-2">
                            <button class="absolute top-0 right-0" @click="unfriend(friend.id)"><i class="fas fa-user-slash"></i></button>
                            <a :href="route('users.show', friend.id)">
                                <p><strong>{{ friend.name }}</strong></p>
                            </a>
                            <table>
                                <tr>
                                    <td><i class="fas fa-city"></i></td>
                                    <td><span class="ml-4"><strong>{{ friend.city }}</strong></span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-map-pin"></i></td>
                                    <td><span class="ml-4"><strong>{{ friend.address }}</strong></span></td>
                                </tr>
                                <tr>
                                    <td><i class="fas fa-shopping-bag"></i></td>
                                    <td><span class="ml-4"><strong>{{ friend.job }}</strong></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <pagination :links="friends.links"/>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import axios from 'axios';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
import BreezeLabel from '@/Components/Label';
import {Head} from '@inertiajs/inertia-vue3';
import Follow from '@/Components/Follow';
import BreezeDropdown from '@/Components/Dropdown.vue';
import BreezeDropdownLink from '@/Components/DropdownLink.vue';
import Input from '@/Components/Input';
import Label from '@/Components/Label';
import Button from '@/Components/Button';
import Pagination from '@/Components/Pagination';
export default {
    name: 'Index',
    components: {
        Pagination,
        Button,
        Label,
        Input,
        BreezeDropdown,
        BreezeAuthenticatedLayout,
        BreezeDropdownLink,
        Follow,
        BreezeLabel,
        Head
    },
    props: {
        friends: Object,
        filter_data: String,
    },
    data () {
        return {
            form: this.$inertia.form({
                filter: this.filter_data,
            })
        }
    },
    methods: {
        search () {
            this.form.get(this.route('friend_list.index'), {
                onSuccess: () => this.form.reset()
            })
        },
        unfriend(id) {
            axios.post(this.route('users.friend.request'), {
                userId: id,
            }).then((response) => {
                console.log(response.data);
            })
        },
    }
};
</script>

<style scoped>

</style>
