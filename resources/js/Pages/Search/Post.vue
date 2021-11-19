<template>
    <div>
        <div>
            <form @submit.prevent="search">
                <label for="keywords">Keywords: </label>
                <input id="keywords" type="text" v-model="form.keywords"/>
                <button type="submit">Search</button>
            </form>
        </div>
        <h3>Filter: </h3>
        <div v-if="keywords !== null">{{ keywords }}</div>
        <div v-for="post in posts.data" :key="post.id">
            <div>
                {{ post.user.name }}
                {{ post.content }}
            </div>
        </div>
        <pagination :links="posts.links"/>
    </div>
</template>

<script>
import BreezeGuestLayout from '@/Layouts/Guest';
import Input from '@/Components/Input';
import Label from '@/Components/Label';
import Button from '@/Components/Button';
import Pagination from '@/Components/Pagination';
export default {
    name: 'Post',
    components: {
        Pagination,
        Button,
        Label,
        Input,
        BreezeGuestLayout
    },
    props: {
        posts: Object,
        keywords: String,
    },
    mounted() {
        console.log(this.posts)
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
            this.form.get(this.route('search.index', 'post'), {
                onSuccess: () => this.form.keywords = this.keywords
            })
        }
    }
};
</script>

<style scoped>

</style>
