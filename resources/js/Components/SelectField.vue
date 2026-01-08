<script setup>
import { defineProps, ref, onMounted, watch, nextTick } from "vue";

// Define the props
const props = defineProps({
    modelValue: {
        type: [String, Number],
        required: true, // الحقل يجب أن يكون موجودًا
    },
    field_name: {
        type: String,
        required: true,
    },
    label: {
        type: String,
        default: "", // افتراضيًا يكون فارغًا
    },
    options: {
        type: Array,
        required: false, // بدل required: true
        default: () => [],
    },
    errorMessage: {
        type: String,
        default: null,
    },
    attribute: {
        type: Object,
        default: () => ({}),
    },
    col: {
        type: String,
        default: "col-md-6", // إذا لم يتم توفير قيمة، سيتم استخدام 'col-md-6'
    },
});

// 🎯 Create a ref for the selected value and initialize
const selectedValue = ref(props.modelValue);

// Define the emit function
const emit = defineEmits(["update:modelValue"]);

// Watch for changes in selectedValue and emit it back
watch(selectedValue, (newValue) => {
    emit("update:modelValue", newValue);
});

// Initialize select2 when the component is mounted
onMounted(() => {
    nextTick(() => {
        $(`#${props.field_name}`).select2({
            allowClear: true,
        });
    });
});
</script>

<template>
    <div :class="col + ' my-3'">
        <!-- 🏷️ تسمية الحقل -->
        <label :for="field_name" class="form-label fs-6">
            <span v-if="label">{{ label }}</span>
            <span v-else><slot name="label" /></span>
        </label>

        <!-- 📝 حقل الإدخال -->
        <select
            v-model="selectedValue"
            :id="field_name"
            :name="field_name"
            class="form-select rounded border-gray-300"
            v-bind="attribute"
        >
            <option value="">Select an option</option>
            <!-- 🧩 Use slot for dynamic options -->
            <slot name="options"></slot>
        </select>

        <!-- 🛑 Show error message -->
        <div v-if="errorMessage">
            <span class="text-end text-danger">{{ errorMessage }}</span>
        </div>
    </div>
</template>
