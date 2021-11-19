<template>
    <select class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md">
        <option v-for="item in items" :value="item">{{ item }}</option>
    </select>
</template>

<script>
export default {
    name: 'Select',
    props: {
        items: Object
    }
};
</script>
