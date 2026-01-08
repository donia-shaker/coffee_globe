<script setup>
defineProps({
    modelValue: {
        type: String,
        required: true, // الحقل يجب أن يكون موجودًا
    },
    message: {
        type: String,
    },
    label: {
        type: String,
        default: '', // افتراضيًا يكون فارغًا
    },
    attribute: {
        type: Object, // يجب أن يكون Object حتى تتمكن من تمريره لـ v-bind
        default: () => ({}),
    },
    col: {
        type: String,
        default: 'col-md-6', // إذا لم يتم توفير قيمة، سيتم استخدام 'col-md-6'
    },
    rows: {
        type: Number,
        default: 10, // عدد الصفوف الافتراضي
    },
    cols: {
        type: Number,
        default: 30, // عدد الأعمدة الافتراضي
    }
});

// 🎯 استخدام `emit` لإرسال تحديث عند إدخال بيانات
const emit = defineEmits(['update:modelValue']);
</script>

<template>
  <div :class="(col ?? 'col-md-6') + ' my-3'">
    <!-- 🏷️ تسمية الحقل -->
    <label :for="modelValue" class="form-label fs-6">
      <span v-if="label">{{ label }}</span>
      <span v-else><slot /></span>
    </label>

    <!-- 📝 حقل textarea -->
    <textarea
      class="form-control rounded border-gray-300"
      :value="modelValue"
      @input="emit('update:modelValue', $event.target.value)"
      ref="input"
      :rows="rows"
      :cols="cols"
      v-bind="attribute"
    ></textarea>

    <div v-show="message">
        <p class="text-sm text-red-600">
            {{ message }}
        </p>
    </div>
  </div>
</template>
