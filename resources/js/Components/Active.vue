<script setup>
import { ref, watch } from "vue";

// استقبال `v-model` عبر `modelValue`
const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: "الحالة",
    },
});

// إنشاء قيمة محلية للمزامنة
const isActive = ref(props.modelValue === "1");

// متابعة أي تغيير في `props.modelValue`
watch(
    () => props.modelValue,
    (newVal) => {
        isActive.value = newVal === "1";
    }
);

// تعريف `emit` لإرسال البيانات للأب
const emit = defineEmits(["update:modelValue"]);
</script>

<template>
    <div class="col-md-6 my-2">
        <div class="form-password-toggle d-flex mt-5">
            <label class="form-label fs-6 mx-3">{{ label }}</label>
            <div class="input-group-merge">
                <label class="switch">
                    <!-- ✅ استخدام v-model بدلاً من :checked -->
                    <input
                        type="checkbox"
                        class="switch-input"
                        v-model="isActive"
                        @change="
                            emit('update:modelValue', isActive ? '1' : '0')
                        "
                    />
                    <span class="switch-toggle-slider">
                        <span class="switch-on bg-info"></span>
                        <span class="switch-off"></span>
                    </span>
                </label>
            </div>
        </div>
    </div>
</template>
