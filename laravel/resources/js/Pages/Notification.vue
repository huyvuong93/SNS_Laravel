<template>
    <Head title="Notifications" />
    <BreezeAuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4"
                     v-for="noti in notifications" :key="noti.key">
                    <div class="p-4 bg-white border-b border-gray-200" v-html="noti.notification_content"></div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
import BreezeLabel from '@/Components/Label';
import {Head} from '@inertiajs/inertia-vue3';
import Button from '@/Components/Button';
export default {
    name: 'Notification',
    components: {
        BreezeAuthenticatedLayout,
        BreezeLabel,
        Head,
        Button
    },
    props: {
        notifications: Object
    },
};
</script>
