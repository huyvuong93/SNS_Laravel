<template>
    <div class="h-full w-full">
        <div class="chat-template overflow-hidden">
            <div class="p-6 overflow-y-scroll">
                <div v-for="friend in chatList">
                    <a :href="route('users.private_chat.show', friend.roomId)"><strong>{{ friend.user.name }}</strong></a>
                </div>
            </div>
            <div ref="mesBox" class="message-template bg-white shadow-sm overflow-y-scroll" v-if="privateChat !== null">
                <div class="messages" v-for="message in messages" :key="message.id">
                    <div class="px-6 py-1" :class="messageStyling(message.sender_id)">
                        <p v-if="this.currentUserId !== message.sender_id"><strong>{{ message.name }}</strong></p>
                        <div class="message_body">
                            <p><span>{{ message.content }}</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div></div>
            <form @submit.prevent="sendMessage(roomId)">
                <div class="send-box flex">
                    <textarea class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-bl-md rounded-tl-md shadow-sm w-full" v-model="new_message"/>
                    <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-br-md rounded-tr-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150" type="submit">Send</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import BreezeLabel from '@/Components/Label';
import {Head} from '@inertiajs/inertia-vue3';
import Button from '@/Components/Button';
import ChatLayout from '@/Layouts/ChatLayout';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated';
export default {
    name: 'PrivateChat',
    components: {
        ChatLayout,
        Button,
        BreezeLabel,
        BreezeAuthenticatedLayout,
        Head
    },
    props: {
        user: Object,
        chatList: Object,
        privateChat: Object,
        roomId: String,
    },
    data() {
        return {
            messages: this.privateChat,
            currentUserId : this.user.id,
            new_message: '',
        }
    },
    created() {
        this.fetchMessages();
        window.Echo.private(`chat.${this.roomId}`)
        .listen('ChatEvent', (e) => {
            this.messages.push({
                content: e.message.message,
                sender_id: e.user.id,
                name: e.user.name,
            });
        });
    },
    mounted() {
        this.$nextTick(() => this.scrollToEnd());
    },
    methods: {
        fetchMessages() {
            axios.get(this.route('users.private_chat.retrieve', this.roomId)).then((response) => {
                this.messages = response.data;
                console.log(response.data)
            })
        },
        messageStyling(id) {
            if (this.currentUserId === id) {
                return 'my_message'
            } else {
                return 'not_my_message'
            }
        },
        sendMessage(id) {
            let message = {};
            message = {
                content: this.new_message,
                sender_id: this.currentUserId,
                name: this.user.name,
            }
            this.messages.push(message);
            this.$nextTick(() => this.scrollToEnd());
            axios.post(this.route('users.private_chat.send'), {
                roomId: id,
                message: this.new_message,
            }).then((response) => {
                this.new_message = '';
                console.log(response.data);
            })
        },
        scrollToEnd: function() {
            let mesBox = this.$refs.mesBox;
            mesBox.scrollTop = mesBox.scrollHeight;
        }
    }
};
</script>

<style scoped>
.chat-template {
    height: 100%;
    display: grid;
    grid-template-columns: 1fr 2fr;
    grid-gap: 5px;
}
.message-template {
    height: 100%;
}
.my_message {
    text-align: end;
}
.my_message .message_body {
    display: inline-block;
    padding: 5px;
    border: none;
    border-radius: 10px;
    background-color: #2dc96f;
    color: white;
}
</style>
