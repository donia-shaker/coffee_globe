<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import SelectField from "@/Components/SelectField.vue";

let props = defineProps({
    langs: Object,
});

const formFields = props.langs.reduce(
    (acc, lang) => {
        acc[`name_${lang.code}`] = "";
        acc[`value_${lang.code}`] = "";
        return acc;
    },
    {
        icon: "",
        is_active: "1",
    }
);

const form = useForm(formFields);


const submit = () => {
    form.post(route("contact_us_infos.store"), {
        onFinish: () => form.reset("image"), // Only reset the image field
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">اضافة وسيلة توصل جديدة</h4>

            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <input type="hidden" name="route" value="add">
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
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="` القيمة ${lang.code}`"
                                :model-value="form[`value_${lang.code}`]"
                                @update:model-value="
                                    (val) => (form[`value_${lang.code}`] = val)
                                "
                                :message="form.errors[`value_${lang.code}`]"
                            />
                            <SelectField
                                v-model="form.icon"
                                field_name="icon"
                                label="الايقونة"
                                :errorMessage="form.errors.icon"
                            >
                                <template #options>
                                    <option value='fas fa-house' class="fa">&#xf015 (Home)</option>
                                    <option value='fas fa-envelope-open-text' class="fa">&#xf2b6 (Email)</option>
                                    <option value='fas fa-envelope' class="fa">&#xf0e0 (Email)</option>
                                    <option value='fas fa-location-dot' class="fa">&#xf3c5 (Location)</option>
                                    <option value='fas fa-phone' class="fa">&#xf095 (Phone)</option>
                                    <option value='fab fa-facebook-f' class="fa">&#xf39e (Facebook)</option>
                                    <option value='fab fa-instagram' class="fa">&#xf16d (Instagram)</option>
                                    <option value='fab fa-linkedin-in' class="fa">&#xf0e1 (Linkedin)</option>
                                    <option value='fab fa-whatsapp' class="fa">&#xf232 (Whatsapp)</option>
                                    <option value='fas fa-x' class="fa">&#x58 (X)</option>
                                    <option value='fab fa-youtube' class="fa">&#xf167 (youtube)</option>
                                </template>
                            </SelectField>
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
                                href="/contact_us_infos"
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
