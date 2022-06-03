<template>
    <Head title="Messenger" />
    <ChatLayout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="height: 100%">
            <div class="chat-template bg-white shadow-sm overflow-hidden h-full">
                <div class="p-6 overflow-y-scroll border-r">
                    <div class="mb-2" v-for="friend in chatList">
                        <a class="inline-flex items-center" :href="route('users.private_chat.show', friend.roomId)">
                            <img :src="'images/user.svg'" style="width: 37px"/>
                            <span class="ml-1">
                                <strong>{{ friend.user.name }}</strong><br>
                                <span v-if="friend.user.is_online === true" class="text-green-400">Online</span>
                                <span v-else>Offline</span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="message-template overflow-y-scroll">
                    <p class="text-center mt-5">Choose who you want to chat</p>
                </div>
            </div>
        </div>
    </ChatLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
import ChatLayout from '@/Layouts/ChatLayout';
import BreezeLabel from '@/Components/Label';
import {Head} from '@inertiajs/inertia-vue3';
import Button from '@/Components/Button';
export default {
    name: 'Chat',
    components: {
        Button,
        BreezeAuthenticatedLayout,
        ChatLayout,
        BreezeLabel,
        Head
    },
    props: {
        user: Object,
        chatList: Object,
    },
};
</script>

<style scoped>
    .chat-template {
        display: grid;
        grid-template-columns: 1fr 2fr;
        grid-gap: 5px;
    }
    .message-template {
        position: relative;
        display: grid;
        grid-template-columns: 1fr;
    }
    .my_message {
        text-align: end;
    }
</style>
