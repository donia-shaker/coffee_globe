<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import FileInput from "@/Components/FileInput.vue";
import Textarea from "@/Components/Textarea.vue";

const props = defineProps({
    langs: Array,
    expert: Object, // <-- تم التعديل هنا
});

const expert = props.expert;

const initialForm = {
    image: "",
    imagePreview: expert.text?.url || "",
    is_active: expert.is_active ? "1" : "0",
    _method: "put",
};

// تعبئة أسماء اللغات
props.langs.forEach((lang) => {
    initialForm[`name_${lang.code}`] = expert.name?.[lang.code] || "";
    initialForm[`text_${lang.code}`] = expert.text?.[lang.code] || "";
});

// هنا نستخدم useForm
const form = useForm(initialForm);

const submit = () => {
    form.post(route("experts.update", expert.id), {
        forceFormData: true,
        onSuccess: () => {
            form.reset("image"); // مسح الصورة فقط بعد الإرسال
        },
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">تعديل خبرائنا</h4>

            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <Input
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="` الاسم ${lang.code}`"
                                :model-value="form[`name_${lang.code}`]"
                                @update:model-value="
                                    (val) => (form[`name_${lang.code}`] = val)
                                "
                                :message="form.errors[`name_${lang.code}`]"
                            />
                            <Textarea
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="` النص ${lang.code}`"
                                :model-value="form[`text_${lang.code}`]"
                                @update:model-value="
                                    (val) => (form[`text_${lang.code}`] = val)
                                "
                                :message="form.errors[`text_${lang.code}`]"
                            />

                            <FileInput
                                v-model="form.image"
                                fieldName="image"
                                label="الصورة"
                                previewId="imagePreview"
                                :src="form.imagePreview"
                                :message="form.errors.image"
                            />

                            <Active
                                v-model="form.is_active"
                                label="الحالة "
                            ></Active>
                        </div>
                        <div class="pt-4">
                            <button
                                class="btn btn-primary text-white me-sm-3 me-1"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                حفظ
                            </button>
                            <a
                                href="/experts"
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
