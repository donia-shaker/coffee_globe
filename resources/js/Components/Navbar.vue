<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from "vue";
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;

// فتح القائمة الجانبية في الجوال
const toggleMenu = () => {
    const layoutMenu = document.getElementById("layout-menu");
    const overlay = document.querySelector(".layout-overlay");

    if (layoutMenu?.classList.contains("layout-menu-active")) {
        layoutMenu.classList.remove("layout-menu-active");
        overlay?.classList.remove("show");
    } else {
        layoutMenu?.classList.add("layout-menu-active");
        overlay?.classList.add("show");
    }
};

// قائمة المستخدم (الصورة)
const isUserDropdownOpen = ref(false);
const toggleUserDropdown = () => {
    isUserDropdownOpen.value = !isUserDropdownOpen.value;
};
const closeUserDropdown = () => {
    isUserDropdownOpen.value = false;
};

// إغلاق قائمة المستخدم عند الضغط خارجها
const handleClickOutside = (event: MouseEvent) => {
    const dropdown = document.getElementById("user-dropdown");
    if (dropdown && !dropdown.contains(event.target as Node)) {
        closeUserDropdown();
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <nav
        class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar"
    >
        <!-- زر القائمة الجانبية للموبايل -->
        <div
            class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none"
        >
            <a
                class="nav-item nav-link px-0 me-xl-4"
                href="javascript:void(0)"
                @click="toggleMenu"
            >
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <!-- عناصر النافبار -->
        <div
            class="navbar-nav-right d-flex align-items-center"
            id="navbar-collapse"
        >
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- تبديل الثيم -->
                <!-- <li class="nav-item me-2 me-xl-0">
                    <a class="nav-link style-switcher-toggle" href="javascript:void(0);">
                        <i class="bx bx-sm"></i>
                    </a>
                </li> -->

                <!-- الإشعارات -->
                <!-- <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
                    <a
                        class="nav-link dropdown-toggle hide-arrow"
                        href="javascript:void(0);"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                    >
                        <i class="bx bx-bell bx-sm"></i>
                        <span
                            id="notification-count"
                            class="badge bg-danger rounded-pill badge-notifications"
                            style="display: none"
                        ></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end py-0">
                        <li class="dropdown-menu-header border-bottom">
                            <div class="dropdown-header d-flex align-items-center py-3">
                                <h5 class="text-body mb-0 me-auto">الاشعارات</h5>
                            </div>
                        </li>
                        <li class="dropdown-notifications-list scrollable-container">
                            <ul class="list-group list-group-flush">
                                <div class="text-center p-2" id="notification-pane">
                                    جاري التحميل...
                                </div>
                            </ul>
                        </li>
                        <li class="dropdown-menu-footer border-top">
                            <a href="" class="dropdown-item d-flex justify-content-center p-3">
                                عرض الإشعارات
                                <span class="bx bx-arrow-back px-2 pt-1"></span>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <!-- المستخدم -->
                <li
                    class="nav-item navbar-dropdown dropdown-user dropdown position-relative"
                    id="user-dropdown"
                >
                    <a
                        class="nav-link dropdown-toggle hide-arrow"
                        href="javascript:void(0);"
                        @click.prevent="toggleUserDropdown"
                    >
                        <div class="avatar">
                            <img
                                src="/images/icon.svg"
                                class="w-px-30 h-auto rounded-circle"
                            />
                        </div>
                    </a>

                    <!-- قائمة المستخدم -->
                    <ul
                        v-show="isUserDropdownOpen"
                        class="dropdown-menu p-3 dropdown-menu-end show"
                        style="width: fit-content !important; left: 0"
                    >
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <img
                                            src="/images/icon.svg"
                                            class="w-px-3c:\Users\donia\Downloads\Mask group (2).svgmod0 h-auto rounded-circle"
                                        />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">
                                        {{ user.name }}
                                    </span>
                                    <small class="text-muted">
                                        {{ user.email }}
                                    </small>
                                </div>
                            </div>
                        </li>
                        <li><div class="dropdown-divider"></div></li>
                        <li>
                            <a href="/" class="dropdown-item">
                                <i class="bx bx-home me-2"></i> الرئيسية
                            </a>
                            <Link
                                :href="route('logout')"
                                method="post"
                                class="dropdown-item"
                            >
                                <i class="bx bx-power-off me-2"></i> تسجيل
                                الخروج
                            </Link>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</template>
