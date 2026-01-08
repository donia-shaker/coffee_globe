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
        acc[`address_${lang.code}`] = "";
        return acc;
    },
    {
        location: "",
        phone: "",
        is_active: "1",
    }
);

const form = useForm(formFields);
const submit = () => {
    form.post(route("branches.store"), {});
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">اضافة فرع</h4>

            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <Input
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="`الاسم  ${lang.code}`"
                                :model-value="form[`name_${lang.code}`]"
                                @update:model-value="
                                    (val) => (form[`name_${lang.code}`] = val)
                                "
                                :message="form.errors[`name_${lang.code}`]"
                            />
                            <Input
                                v-for="lang in langs"
                                :key="lang.code"
                                :label="` العنوان ${lang.code}`"
                                :model-value="form[`address_${lang.code}`]"
                                @update:model-value="
                                    (val) =>
                                        (form[`address_${lang.code}`] = val)
                                "
                                :message="form.errors[`address_${lang.code}`]"
                            />
                            <Input
                                v-model="form.location"
                                label="  رابط الموقع"
                                :message="form.errors.location"
                            ></Input>
                            <Input
                                v-model="form.phone"
                                label=" رقم الهاتف"
                                :message="form.errors.phone"
                            ></Input>
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
                                href="/branches"
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
