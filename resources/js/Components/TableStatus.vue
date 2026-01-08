<script setup>
const props = defineProps({
    active: {
        type: Number,
        required: true,
    },
});
</script>
<template>
    <span :class="'badge bg-label-' + (active == 1 ? 'success' : 'danger')">
        {{ active == 1 ? "مفعل" : "غير مفعل" }}
    </span>
</template>
