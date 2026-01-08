<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import SelectField from "@/Components/SelectField.vue";

const props = defineProps({
    langs: Array, // تأكدنا من أن اللغات هي مصفوفة (Array)
    branch: Object,
});

const branch = props.branch; // استقبال بيانات الـ branch من Laravel

const initialForm = {
    phone: branch.phone || "",
    location: branch.location || "",
    is_active: branch.is_active ? "1" : "0",
};

props.langs.forEach((lang) => {
    initialForm[`name_${lang.code}`] = branch.name?.[lang.code] || "";
    initialForm[`address_${lang.code}`] = branch.address?.[lang.code] || "";
});

const form = useForm(initialForm);

const submit = () => {
    form.put(route("branches.update", branch.id), {});
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">تعديل الفرع</h4>

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
