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
        <div class="relative bg-bg_green border-t border-background_two">
            <!-- الحقوق -->
            <div
                class="copy-right py-4 pt-6 text-background text-center text-md md:text-md font-bold"
            >
                <p>
                    <span class="text-background">
                        {{ $t("coffee_globe") }}
                    </span>
                    -
                    {{ $t("all_right_reserved") }}
                    © {{ new Date().getFullYear() }}
                </p>
            </div>
        </div>
    </footer>
</template>
