<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

// استخدام ref لجعل الرسائل قابلة للتعديل
const successMessage = ref(page.props.flash.success);
const errorMessage = ref(page.props.flash.error);

// تحديث الرسائل عند تغيير `page.props.flash`
watch(
    () => page.props.flash.success,
    (newVal) => {
        successMessage.value = newVal;
    }
);
watch(
    () => page.props.flash.error,
    (newVal) => {
        errorMessage.value = newVal;
    }
);

// دالة لإغلاق الرسالة
const closeSuccess = () => {
    successMessage.value = null;
};
const closeError = () => {
    errorMessage.value = null;
};
</script>

<template>
    <div v-if="successMessage" class="alert alert-success alert-dismissible" role="alert">
        <!-- <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">
            Well done :)
        </h6> -->
        <p class="mb-0">{{ successMessage }}</p>
        <button type="button" class="btn-close" @click="closeSuccess" aria-label="Close"></button>
    </div>

    <div v-if="errorMessage" class="alert alert-danger alert-dismissible" role="alert">
        <!-- <h6 class="alert-heading d-flex align-items-center fw-bold mb-1">
            Error!!
        </h6> -->
        <p class="mb-0">{{ errorMessage }}</p>
        <button type="button" class="btn-close" @click="closeError" aria-label="Close"></button>
    </div>
</template>
