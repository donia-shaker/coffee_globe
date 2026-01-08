<template>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <Sidebar />
            <div class="layout-page">
                <Navbar />
                <div class="content-wrapper">
                    <div class="content-backdrop fade"></div>
                    <slot></slot>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
        <div class="drag-target"></div>

        <!-- ✅ إضافة المودال العام -->
        <FormModel
            :isOpen="showModal"
            title="تأكيد العملية"
            content="هل أنت متأكد من رغبتك في تنفيذ هذا الإجراء؟"
            :route="selectedRoute"
            :type="selectedType"
            @close="closeModal"
        />
    </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from "vue";
import Sidebar from "@/Components/Sidebar.vue";
import Navbar from "@/Components/Navbar.vue";
import FormModel from "@/Components/FormModel.vue"; // ✅ استيراد المودال
import { useModal } from "@/Composables/useModal"; // ✅ استيراد `useModal.js`

const { showModal, selectedRoute, selectedType, openModal, closeModal } =
    useModal();


const styles = [
    "/assets/vendor/fonts/boxiconsc4a7.css",
    "/assets/vendor/fonts/fontawesome5cae.css",
    "/assets/vendor/fonts/flag-icons5883.css",
    "/assets/css/democb2e.css",
    "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbarb440.css",
    "/assets/vendor/libs/typeahead-js/typeahead3881.css",
    "/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css",
    "/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css",
    "/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css",
    "/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css",
    "/assets/vendor/libs/flatpickr/flatpickr.css",
    "/assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css",
    "/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css",
    "/css/modify.css",
    "/css/cairo.css",
];

const scripts = [
    "/assets/vendor/libs/jquery/jquery8853.js",
    "/assets/vendor/libs/popper/popper5751.js",
    "/assets/vendor/js/bootstrape305.js",
    "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar6188.js",
    "/assets/vendor/libs/hammer/hammera90c.js",
    "/assets/vendor/libs/i18n/i18nbcd7.js",
    "/assets/vendor/libs/typeahead-js/typeahead60e7.js",
    "/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js",
    "/assets/vendor/libs/moment/moment.js",
    "/assets/vendor/libs/flatpickr/flatpickr.js",
    "/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js",
    "/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js",
    "/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js",
    "/assets/js/datatable-with-buttons.js",
    "/assets/js/forms-selects.js",
    "/assets/vendor/js/menuf635.js",
];

onMounted(() => {
    // Add CSS
    styles.forEach((href) => {
        if (!document.querySelector(`link[href="${href}"]`)) {
            const link = document.createElement("link");
            link.rel = "stylesheet";
            link.href = href;
            document.head.appendChild(link);
        }
    });

    // Add core and theme CSS
    const coreCss = document.createElement("link");
    coreCss.rel = "stylesheet";
    coreCss.href = "/assets/vendor/css/rtl/coref43c.css";
    coreCss.className = "template-customizer-core-css";
    document.head.appendChild(coreCss);

    const themeCss = document.createElement("link");
    themeCss.rel = "stylesheet";
    themeCss.href = "/assets/vendor/css/rtl/theme-default56b8.css";
    themeCss.className = "template-customizer-theme-css";
    document.head.appendChild(themeCss);

    // Add JS scripts
    scripts.forEach((src) => {
        const script = document.createElement("script");
        script.src = src;
        script.async = false;
        document.body.appendChild(script);
    });

    // Add the main script after all others
    setTimeout(() => {
        const mainScript = document.createElement("script");
        mainScript.src = "/assets/js/maincf4d.js";
        document.body.appendChild(mainScript);
    }, 1000);
});

// Cleanup when component is unmounted or route is left
// onBeforeUnmount(() => {
//     // Remove dynamically added CSS and JS to prevent them from staying on the page
//     styles.forEach((href) => {
//         const link = document.querySelector(`link[href="${href}"]`);
//         if (link) {
//             link.remove();
//         }
//     });

//     // Remove core and theme CSS
//     const coreCss = document.querySelector('.template-customizer-core-css');
//     const themeCss = document.querySelector('.template-customizer-theme-css');
//     if (coreCss) coreCss.remove();
//     if (themeCss) themeCss.remove();

//     // Remove JS scripts
//     scripts.forEach((src) => {
//         const script = document.querySelector(`script[src="${src}"]`);
//         if (script) {
//             script.remove();
//         }
//     });

//     // Remove main script
//     const mainScript = document.querySelector('script[src="/assets/js/maincf4d.js"]');
//     if (mainScript) {
//         mainScript.remove();
//     }
// });
</script>
