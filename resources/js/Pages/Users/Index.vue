<template>
    <div>
        {{ user.name }}
    </div>
</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import BreezeButton from '@/Components/Button';
import BreezeInput from '@/Components/Input';
import BreezeLabel from '@/Components/Label';
import BreezeValidationErrors from '@/Components/ValidationErrors';

export default {
    name: 'Index',
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
    }
};
</script>

<style scoped>

</style>
