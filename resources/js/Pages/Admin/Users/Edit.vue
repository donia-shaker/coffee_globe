<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";

const page = usePage();
const user = page.props.user; // استقبال بيانات المستخدم من Laravel

const form = useForm({
    name: user.name || "",
    phone: user.phone || "",
    email: user.email || "",
    password: "",
    is_active: user.is_active ? "1" : "0", // تحويل الحالة لقيمة نصية
    password_confirmation: "",
});

const submit = () => {
    form.put(route("user.update", user.id), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">تعديل مستخدم</h4>

            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
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
