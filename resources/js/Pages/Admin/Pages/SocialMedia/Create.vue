<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Input from "@/Components/Input.vue";
import Active from "@/Components/Active.vue";
import SelectField from "@/Components/SelectField.vue";
const formFields = {
    name: '',
    url: '',
    icon: '',
    is_active: "1",
};

const form = useForm(formFields);



const submit = () => {
    form.post(route("social_media_infos.store"), {
    });
};
</script>

<template>
    <DashboardLayout>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-2 mb-3 fs-2">اضافة حساب تواصل</h4>

            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <div class="card-body row">
                    <form @submit.prevent="submit">
                        <div class="row">
                            <input type="hidden" name="route" value="add">
                            <Input
                                v-model="form.name"
                                label="  الاسم"
                                :message="form.errors.name"
                            ></Input>
                            <Input
                                v-model="form.url"
                                label="  الرابط"
                                :message="form.errors.url"
                            ></Input>
                            
                            <SelectField
                                v-model="form.icon"
                                field_name="icon"
                                label="الايقونة"
                                :errorMessage="form.errors.icon"
                            >
                                <template #options>
                                    <option value='fab fa-facebook-f' class="fa">&#xf39e (Facebook)</option>
                                    <option value='fab fa-instagram' class="fa">&#xf16d (Instagram)</option>
                                    <option value='fab fa-linkedin-in' class="fa">&#xf0e1 (Linkedin)</option>
                                    <option value='fab fa-whatsapp' class="fa">&#xf232 (Whatsapp)</option>
                                    <option value='fas fa-x' class="fa">&#x58 (X)</option>
                                    <option value='fab fa-youtube' class="fa">&#xf167 (youtube)</option>
                                    <option value="fab fa-snapchat-ghost" class="fa">&#xf2ac (Snapchat)</option>
                                    <option value="fab fa-tiktok" class="fa">&#xe07b (TikTok)</option>
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
                                href="/social_media_infos"
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
