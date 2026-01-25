<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { useForm } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import FileInput from "@/Components/FileInput.vue";
import Textarea from "@/Components/Textarea.vue";

// Props
const props = defineProps({
    langs: Array,
    page: Object,
});

// البيانات الأولية للنموذج
const page = props.page;
const initialForm = {
    image: "",
    imagePreview: page?.media?.url ?? "",
    _method: "put",
};

// تعبئة أسماء اللغات
props.langs.forEach((lang) => {
    initialForm[`name_${lang.code}`] = page.name?.[lang.code] || "";
});

// useForm
const form = useForm(initialForm);

// ارسال النموذج
const submit = () => {
    form.post(route("pages.update", page.id), {
        forceFormData: true,
        onSuccess: () => form.reset("image"),
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">تعديل  الصفحات</h4>
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <!-- العنوان -->
                            <Input
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="` العنوان ${lang.code}`"
                                :model-value="form[`name_${lang.code}`]"
                                @update:model-value="
                                    (val) => (form[`name_${lang.code}`] = val)
                                "
                                :message="form.errors[`name_${lang.code}`]"
                                :disabled="true"
                            />
                            <!-- الصورة والحالة -->
                            <FileInput
                                v-model="form.image"
                                fieldName="image"
                                label="صورة"
                                previewId="imagePreview"
                                :src="form.imagePreview"
                                :message="form.errors.image"
                            />
                            <Active
                                v-model="form.is_active"
                                label="الحالة"
                            ></Active>
                        </div>

                        <!-- أزرار الإرسال -->
                        <div class="pt-4">
                            <button
                                class="btn btn-primary text-white me-sm-3 me-1"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                حفظ
                            </button>
                            <a
                                href="/pages"
                                class="btn btn-outline-secondary"
                                style="border-color: #aaa"
                                >إلغاء</a
                            >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
