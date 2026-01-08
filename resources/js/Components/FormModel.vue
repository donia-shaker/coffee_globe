<template>
    <div v-if="isOpen">
        <!-- Overlay -->
        <div class="modal-backdrop fade show"></div>

        <!-- Modal -->
        <div class="modal fade show d-block">
            <div class="modal-dialog modal-dialog-centered modal-simple">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <!-- زر الإغلاق -->
                        <button
                            type="button"
                            class="btn-close btn-pinned"
                            @click="closeModal"
                            aria-label="Close"
                        ></button>

                        <!-- عنوان + محتوى -->
                        <div class="text-center mb-4">
                            <h3>{{ title }}</h3>
                            <p>{{ content }}</p>
                        </div>

                        <!-- النموذج -->
                        <form
                            @submit.prevent="submit"
                            :action="route"
                            method="POST"
                        >
                            <input type="hidden" name="id" :value="id" />

                            <slot name="formInput"></slot>
                            <!-- لاستقبال الحقول الإضافية -->

                            <div
                                class="col-12 text-center demo-vertical-spacing"
                            >
                                <button
                                    :disabled="form.processing"
                                    :class="
                                        `btn btn-${type} me-sm-3 me-1` +
                                        { 'opacity-25': form.processing }
                                    "
                                >
                                    إرسال
                                </button>
                                <button
                                    type="reset"
                                    class="btn btn-label-secondary"
                                    @click="closeModal"
                                >
                                    إلغاء
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, defineEmits, computed } from "vue";
import { usePage } from "@inertiajs/vue3"; // ✅ استيراد usePage
import { Head, Link, useForm } from "@inertiajs/vue3";

const props = defineProps({
    isOpen: Boolean,
    title: String,
    type: String,
    content: String,
    route: String, // المسار المرسل إلى المودال
    id: [String, Number], // معرف العنصر
});

const emit = defineEmits(["close"]);

const page = usePage(); // ✅ الحصول على بيانات Inertia
const csrfToken = computed(() => page.props.csrf_token); // ✅ استخراج CSRF Token

const form = useForm({
    id: "",
});

const closeModal = () => {
    emit("close");
};

const submit = () => {
    form.id = props.id; // ← ربط الـ id قبل الإرسال

    form.post(props.route, {
        onFinish: () => {
            form.reset("id");
            closeModal();
        },
    });
};
</script>

<style scoped>
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5); /* تم تقليل التعتيم */
    z-index: 10544;
}

.modal {
    z-index: 10545;
}
</style>
