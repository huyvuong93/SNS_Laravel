<template>
    <div>
        <form @submit.prevent="post">
            <div class="mt-4">
                <label for="content">Let's share some awesome stories</label>
                <textarea id="content" type="text" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-full" v-model="form.content"/>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <input type="file" id="selectedFiles" multiple="multiple" @input="form.images = $event.target.files" style="display: none;" />
                        <button type="button" value="" onclick="document.getElementById('selectedFiles').click();"><i class="fas fa-image"></i></button>
                        <label for="status" class="ml-2"><i class="fas fa-eye"></i></label>
                        <select id="status" class="border-none appearance-none pr-0" v-model="form.status">
                            <option v-for="item in status" :value="item">{{ item }}</option>
                        </select>
                    </div>
                    <div class="text-right mt-2">
                        <Button type="submit">Post</Button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import Button from '@/Components/Button';
export default {
    name: 'PostForm',
    components: {
        Button,
    },
    props: {
        status: Object,
    },
    data() {
        return {
            form: this.$inertia.form({
                content: null,
                status: 'all',
                images: [],
            }),
            search: '',
        }
    },
    methods: {
        post() {
            this.form.post(this.route('post.upload'), {
                onSuccess: () => {
                    this.form.reset();
                    window.location.reload();
                }
            })
        },
    }
};
</script>
