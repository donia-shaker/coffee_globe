<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import SelectField from "@/Components/SelectField.vue";

const props = defineProps({
    role: String, // ✅ أضفنا تعريف role هنا
    suppliers: Object, // ✅ أضفنا تعريف role هنا
});

const form = useForm({
    role: props.role,
    name: "",
    phone: "",
    email: "",
    password: "",
    is_active: "1",
    password_confirmation: "",
    supplier_id: "",
});

const submit = () => {
    form.post(route("user.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">اضافة {{ $t(role) }}</h4>

            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <Input
                                v-model="form.role"
                                type="hidden"
                                style="display: none"
                                :message="form.errors.role"
                            ></Input>
                            <Input
                                v-model="form.name"
                                label="اسم المستخدم"
                                :message="form.errors.name"
                            ></Input>
                            <Input
                                v-model="form.email"
                                label="الايميل "
                                type="email"
                                :message="form.errors.email"
                            ></Input>
                            <Input
                                v-model="form.phone"
                                type="number"
                                label="رقم الجوال  "
                                :message="form.errors.phone"
                            ></Input>
                            <Input
                                v-model="form.password"
                                type="password"
                                label="كلمة المرور "
                                :message="form.errors.password"
                            ></Input>

                            <Input
                                v-model="form.password_confirmation"
                                type="password"
                                label="تاكيد كلمة المرور "
                                :message="form.errors.password_confirmation"
                            ></Input>
                            <SelectField
                                v-if="role == 'supplier'"
                                v-model="form.supplier_id"
                                field_name="supplier_id"
                                label="المورد"
                                :errorMessage="form.errors.supplier_id"
                            >
                                <template #options>
                                    <option
                                        v-for="supplier in suppliers"
                                        :key="supplier.id"
                                        :value="supplier.id"
                                    >
                                        {{ supplier.name.ar }}
                                    </option>
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
                                href="/users/Admin"
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
