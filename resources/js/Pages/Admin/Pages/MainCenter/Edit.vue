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
    main_center: Object,
});

// البيانات الأولية للنموذج
const main_center = props.main_center;

const initialForm = {
    location: main_center.location || "",
    phone: main_center.phone || "",
    email: main_center.email || "",
    website: main_center.website || "",
    support: main_center.support || "",
    is_active: main_center.is_active ? "1" : "0",
};

// تعبئة أسماء اللغات
props.langs.forEach((lang) => {
    initialForm[`name_${lang.code}`] = main_center.name?.[lang.code] || "";
    initialForm[`address_${lang.code}`] =
        main_center.address?.[lang.code] || "";
});

// useForm
const form = useForm(initialForm);

// ارسال النموذج
const submit = () => {
    form.put(route("main_centers.update", main_center.id), {});
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">تعديل بيانات المركز الرئيسي</h4>
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <!-- العنوان -->

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
                            <Input
                                v-model="form.email"
                                label=" الايميل "
                                :message="form.errors.email"
                            ></Input>

                            <Input
                                v-model="form.support"
                                label="  رقم الدعم"
                                :message="form.errors.support"
                            ></Input>

                            <Input
                                v-model="form.website"
                                label="  موقع ال web"
                                :message="form.errors.website"
                            ></Input>

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
                                href="/main_centers"
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
