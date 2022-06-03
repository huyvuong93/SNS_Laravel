<template>
    <div>
        {{ comment.content }}
    </div>
</template>

<script>
export default {
    name: 'NewComment',
    props: {
        comment: Object,
    }
};
</script>

<style scoped>

</style>
