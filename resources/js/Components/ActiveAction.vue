<script setup>
const props = defineProps({
    active: {
        type: Number,
        required: true,
    },
});
</script>
<template>
    <button
        type="button"
        :class="
            'btn btn-icon btn-outline-' +
            (active ? 'info' : 'danger') +
            ' text-' +
            (active ? 'info' : 'danger') +
            ' mx-1'
        "
        data-bs-toggle="tooltip"
        data-bs-placement="top"
        title="تفعيل"
    >
        <i :class="'bx bx-' + (active ? 'show' : 'hide')"></i>
    </button>
</template>
