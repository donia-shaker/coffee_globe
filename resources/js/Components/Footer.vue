<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import ContactForm from "./ContactForm.vue";

defineProps({
    sections: Object,
    brands: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
});
</script>

<template>
    <footer>
        <div class="relative bg-[#d6c7c0]">
            <div
                class="absolute top-0 w-full z-[2] h-full"
                :style="{
                    backgroundImage: `url('/images/logo_white.png')`,
                    backgroundSize: 'cover',
                    backgroundPosition: 'center',
                    backgroundRepeat: 'repeat',
                }"
            ></div>
            <div class="relative pt-[60px] overflow-hidden bg-[#602f15]">
                <div
                    class="absolute top-0 w-full z-[2] h-[110%]"
                    :style="{
                        backgroundImage: `url('/images/footer.svg')`,
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                        backgroundRepeat: 'repeat',
                    }"
                ></div>
                <div
                    class="container relative flex flex-col md:flex-row px-[20px] z-10"
                >
                    <div class="pb-[40px]">
                        <div
                            class="content flex justify-between gap-[40px] text-start flex-wrap"
                        >
                            <!-- وصف الشركة -->
                            <div class="col max-w-[400px]">
                                <div
                                    class="logo relative flex justify-center h-[160px] items-center"
                                >
                                    <img
                                        src="/images/logo_2.png"
                                        class="h-full w-[400px] object-contain"
                                        alt="logo"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="border-b border-icon_background">
                            <ul
                                class="flex justify-between lg:px-32 lg:w-[750px] py-[20px] text-icon_background text-center text-md md:text-md font-bold"
                            >
                                <li class="transition">
                                    <Link href="/">{{ $t("home") }}</Link>
                                </li>
                                <li class="transition">
                                    <Link href="#about">{{ $t("about") }}</Link>
                                </li>
                                <li class="transition">
                                    <Link href="#products">{{
                                        $t("products")
                                    }}</Link>
                                </li>
                                <li class="transition">
                                    <Link href="#contact">{{
                                        $t("contact")
                                    }}</Link>
                                </li>
                            </ul>
                        </div>

                        <!-- الحقوق -->
                        <div
                            class="copy-right py-[32px] text-icon_background text-center text-md md:text-md font-bold"
                        >
                            <p>
                                <span class="text-icon_background">
                                    {{ $t("yemen_caffee") }}
                                </span>
                                -
                                {{ $t("all_right_reserved") }}
                                © {{ new Date().getFullYear() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
