<template>
    <Head title="Search User" />
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <form @submit.prevent="search">
                            <label for="keywords">Keywords: </label>
                            <input id="keywords" type="text" v-model="form.keywords"/>
                            <button type="submit">Search</button>
                        </form>
                    </div>
                </div>
                <h3>Filter: </h3>
                <div v-if="keywords !== null">{{ keywords }}</div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4"
                     v-for="user in users.data" :key="user.id">
                    <div class="p-4 bg-white border-b border-gray-200 grid grid-cols-3 gap-1">
                        <div class="col-span-1">
                            <img class="mx-auto" :src="'/images/user.svg'" style="width: 60%"/>
                        </div>
                        <div class="relative col-span-2">
                            <button class="absolute top-0 right-0" @click="addFriend(user.id)"><i class="fas fa-user-plus"></i></button>
                            <p><strong>{{ user.name }}</strong></p>
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
                    </div>
                </div>
                <pagination :links="users.links"/>
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
    name: 'User',
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
        users: Object,
        keywords: String,
    },
    data () {
        return {
            form: this.$inertia.form({
                keywords: '',
            })
        }
    },
    methods: {
        search () {
            this.form.get(this.route('search.index', 'user'), {
                onSuccess: () => this.form.keywords = this.keywords
            })
        },
        addFriend(id) {
            axios.post(this.route('users.friend.request'), {
                userId: id,
            }).then((response) => {
                console.log(response.data);
            })
        }
    }
};
</script>

<style scoped>

</style>
