<template>
    <!-- Modal -->
    <div v-if="isOpen">
        <!-- Overlay (الخلفية الداكنة) -->
        <div class="modal-backdrop fade show"></div>

        <!-- Modal Window -->
        <div
            class="modal fade show d-block"
            tabindex="-1"
            data-bs-backdrop="static"
        >
            <div class="modal-dialog">
                <form class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ title }}</h5>
                        <button
                            type="button"
                            class="btn-close"
                            @click="closeModal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="text-center">{{ message }}</h4>
                    </div>
                    <div class="modal-footer">
                        <button
                            v-if="!hideCancel"
                            type="button"
                            class="btn btn-label-secondary"
                            @click="closeModal"
                        >
                            إلغاء
                        </button>
                        <a :href="link" class="btn btn-primary">{{ action }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        isOpen: Boolean,
        title: String,
        message: String,
        link: String,
        action: String,
        hideCancel: Boolean,
    },
    emits: ["close"],
    methods: {
        closeModal() {
            this.$emit("close");
        },
    },
};
</script>

<style>
/* إصلاحات لإظهار الـ Overlay */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 9);
    z-index: 10584;
}

.modal {
    z-index: 10585;
}
</style>
