<template>
    <div>
        <ValidationErrors/>

        <form @submit.prevent="update">
            <Label for="name">Name:</Label>
            <Input id="name" type="text" v-model="form.name" :error="form.errors.name"/>
            <Button type="submit">Update</Button>
        </form>
    </div>
</template>

<script>
import ValidationErrors from '@/Components/ValidationErrors';
import Input from '@/Components/Input';
import Button from '@/Components/Button';
import Label from '@/Components/Label';
export default {
    name: 'Edit',
    components: {Label, Button, Input, ValidationErrors},
    props: {
      user: Object,
    },
    data() {
        return {
            form : this.$inertia.form({
                _method: 'put',
                name: this.user.name,
            })
        }
    },
    methods: {
        update() {
            this.form.post(this.route('users.update', this.user.id), {
                onSuccess: () => this.form.reset('name')
            })
        }
    }
};
</script>

<style scoped>

</style>
