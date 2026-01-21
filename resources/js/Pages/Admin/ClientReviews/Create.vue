<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import FileInput from "@/Components/FileInput.vue";
import Textarea from "@/Components/Textarea.vue";

let props = defineProps({
    langs: Object,
});

const formFields = props.langs.reduce(
    (acc, lang) => {
        acc[`name_${lang.code}`] = "";
        acc[`text_${lang.code}`] = "";
        return acc;
    },
    {
        image: null,
        is_active: "1",
        rate: null,
    },
);

const form = useForm(formFields);

const submit = () => {
    form.post(route("client_reviews.store"), {
        onFinish: () => form.reset("image"), // Only reset the image field
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">اضافة رأي عميل</h4>

            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <input type="hidden" name="route" value="add" />
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

                            <Input
                                v-model="form.rate"
                                label="   التقييم"
                                :message="form.errors.rate"
                            ></Input>
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
                                label="الصورة  "
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
                                href="/client_reviews"
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
