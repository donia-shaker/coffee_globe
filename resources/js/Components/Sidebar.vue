<template>
    <aside
        id="layout-menu"
        class="layout-menu menu-vertical menu bg-menu-theme"
    >
        <div class="app-brand demo">
            <a href="" class="app-brand-link">
                <span class="app-brand-logo demo">
                    <img src="/images/logo2.svg" width="140" alt="" />
                </span>
            </a>
            <a
                href="javascript:void(0);"
                class="layout-menu-toggle menu-link text-large ms-auto"
            >
                <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
            <li
                :class="{ active: route().current() === 'dashboard' }"
                class="menu-item"
            >
                <Link :href="route('dashboard')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>لوحة التحكم</div>
                </Link>
            </li>

            <!-- <li :class="{ active: route().current() === 'users.index' }" class="menu-item">
                <Link :href="route('users.index')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>المستخدمين</div>
                </Link>
            </li> -->
            <li class="menu-item">
                <div class="menu-link menu-toggle" @click="toggleMenu('users')">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>المستخدمين</div>
                </div>
                <ul v-if="openMenus.includes('users')" class="list mx-3">
                    <li
                        :class="{ active: route().current() === 'users.index' }"
                        class="menu-item"
                    >
                        <Link
                            :href="route('users.index', { role: 'admin' })"
                            class="menu-link {{ route().current() === 'users.index' ? 'active' : '' }}"
                        >
                            <i
                                class="menu-icon tf-icons bx bxs-circle"
                                style="font-size: 10px"
                            >
                            </i>
                            <div>مديرين النظام</div>
                        </Link>
                    </li>
                </ul>
            </li>
            <li
                :class="{ active: route().current() === 'services.index' }"
                class="menu-item"
            >
                <Link :href="route('services.index')" class="menu-link">
                    <i
                        class="menu-icon tf-icons bx bx-objects-horizontal-left"
                    ></i>
                    <div>منهجيتنا</div>
                </Link>
            </li>
            <li
                :class="{
                    active: route().current() === 'service_companies.index',
                }"
                class="menu-item"
            >
                <Link
                    :href="route('service_companies.index')"
                    class="menu-link"
                >
                    <i class="menu-icon tf-icons bx bx-circle-three-quarter"></i>
                    <div>خدمات الشركات</div>
                </Link>
            </li>
            <li
                :class="{ active: route().current() === 'admin_blogs.index' }"
                class="menu-item"
            >
                <Link :href="route('admin_blogs.index')" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-notepad' ></i>
                    <div>المدونة</div>
                </Link>
            </li>
            <li
                :class="{ active: route().current() === 'experts.index' }"
                class="menu-item"
            >
                <Link :href="route('experts.index')" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-group"></i>
                    <div>خبرائنا</div>
                </Link>
            </li>
            <li
                :class="{ active: route().current() === 'values.index' }"
                class="menu-item"
            >
                <Link :href="route('values.index')" class="menu-link">
                    <i class='menu-icon tf-icons bx bxl-gitlab' ></i>
                    <div>قيمنا</div>
                </Link>
            </li>
            <li
                :class="{
                    active: route().current() === 'client_reviews.index',
                }"
                class="menu-item"
            >
                <Link :href="route('client_reviews.index')" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-message-rounded'></i>
                    <div>اراء العملاء</div>
                </Link>
            </li>
            <li
                :class="{ active: route().current() === 'admin_fqs.index' }"
                class="menu-item"
            >
                <Link :href="route('admin_fqs.index')" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-question-mark' ></i>
                    <div>الاسئلة الشائعة</div>
                </Link>
            </li>
            <li
                :class="{ active: route().current() === 'why_uss.index' }"
                class="menu-item"
            >
                <Link :href="route('why_uss.index')" class="menu-link">
                    <i class='menu-icon tf-icons bx bx-check-square' ></i>
                    <div>لماذا نحن</div>
                </Link>
            </li>
            <li class="menu-item">
                <div class="menu-link menu-toggle" @click="toggleMenu('pages')">
                    <i class="menu-icon tf-icons bx bx-category"></i>
                    <div>الصفحات</div>
                </div>
                <ul v-if="openMenus.includes('pages')" class="list mx-3">
                    <li
                        :class="{
                            active: route().current() === 'sliders.index',
                        }"
                    >
                        <Link :href="route('sliders.index')" class="menu-link">
                            <i
                                class="menu-icon tf-icons bx bxs-circle"
                                style="font-size: 10px"
                            >
                            </i>
                            <div>السلايدر</div>
                        </Link>
                    </li>
                    <li
                        :class="{
                            active:
                                route().current() === 'about_page_infos.index',
                        }"
                    >
                        <Link
                            :href="route('about_page_infos.index')"
                            class="menu-link"
                        >
                            <i
                                class="menu-icon tf-icons bx bxs-circle"
                                style="font-size: 10px"
                            >
                            </i>
                            <div>من نحن</div>
                        </Link>
                    </li>
                    <li
                        :class="{
                            active:
                                route().current() === 'contact_us_infos.index',
                        }"
                    >
                        <Link
                            :href="route('contact_us_infos.index')"
                            class="menu-link"
                        >
                            <i
                                class="menu-icon tf-icons bx bxs-circle"
                                style="font-size: 10px"
                            >
                            </i>
                            <div>وسائل التواصل</div>
                        </Link>
                    </li>
                    <li
                        :class="{
                            active: route().current() === 'sliders.index',
                        }"
                    >
                        <Link
                            :href="route('social_media_infos.index')"
                            class="menu-link"
                        >
                            <i
                                class="menu-icon tf-icons bx bxs-circle"
                                style="font-size: 10px"
                            >
                            </i>
                            <div>حسابات التواصل</div>
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</template>

<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import FormModel from "./FormModel.vue";

const openMenus = ref([]);

const toggleMenu = (menu) => {
    if (openMenus.value.includes(menu)) {
        openMenus.value = openMenus.value.filter((item) => item !== menu);
    } else {
        openMenus.value.push(menu);
    }
};
</script>
