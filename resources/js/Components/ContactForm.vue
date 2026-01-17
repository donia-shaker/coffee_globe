<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import ContactCards from "./ContactCards.vue";
import Button from "./Button.vue";

const showModel = ref(false);
const message_text = ref("");

const form = useForm({
    name: "",
    phone: "",
    address: "",
    message: "",
});

const page = usePage();

const successMessage = ref(page.props.flash?.success);
const errorMessage = ref(page.props.flash?.error);

watch(
    () => page.props.flash,
    (newVal) => {
        if (newVal?.success) {
            successMessage.value = newVal.success;
            message_text.value = "success";
            showModel.value = true;
        } else if (newVal?.error) {
            errorMessage.value = newVal.error;
            message_text.value = "error";
            showModel.value = true;
        }
    }
);

defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
});

const submitForm = () => {
    form.post(route("contact.send"), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

const closeModel = () => {
    showModel.value = false;
};
</script>

<template>
    <div
        class="fixed bg-[#00000099] top-0 left-0 w-[100vw] h-[100vh] z-50 flex items-center justify-center"
        v-if="showModel"
    >
        <div
            class="p-10 bg-white w-full m-4 text-center rounded-xl max-w-[500px]"
        >
            <h2 class="text-2xl font-bold mb-4">
                {{ $t(message_text) }}
            </h2>
            <Button
                @click="closeModel"
                :disabled="form.processing"
                :type="'primary'"
                :text="$t('close')"
            />
        </div>
    </div>
    <div class="relative bg-bg_green text-background z-10 bg-cover bg-center bg-no-repeat overflow-hidden"
        style="background-image: url('/images/mask_2.png')"
    >
        
        <div class="container flex py-[80px] flex-col md:flex-row gap-10">
            <div class="w-full md:w-1/2 max-w-[600px] lg:px-14">
                <h2 class="text-2xl md:text-5xl font-bold  pb-4">
                    {{ $t("contact_us") }}
                </h2>
                <div
                    class="flex flex-col gap-x-10 gap-y-4  flex-wrap mt-14"
                >
                    <div
                        class="flex items-center  font-bold text-xl md:text-2xl gap-4 pb-3"
                        v-for="contact_us_info in contact_us_infos"
                    >
                        <div
                            class=" rounded-md  flex items-center justify-center"
                        >
                            <i
                                class="text-white"
                                :class="contact_us_info.icon"
                            ></i>
                        </div>
                        <span
                            class=""
                            >{{ $tt(contact_us_info.value) }}</span
                        >
                    </div>
                </div>
                <div
                    class="icons  justify-center md:justify-start mt-8 text-xl md:text-xl flex gap-x-6"
                >
                    <a
                        v-for="social_media_info in social_media_infos"
                        :href="social_media_info.url"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i :class="social_media_info.icon"></i
                    ></a>
                </div>
            </div>
            <div class="">
                <img src="/images/map.svg" alt="">
            </div>
            <!-- <div
                class="flex justify-between w-full md:w-1/2 mx-auto flex-col-reverse lg:flex-row"
            >
                <form
                    @submit.prevent="submitForm"
                    class="w-full flex flex-col pt-4 md:px-10"
                >
                    <div class="fields grid gap-2">
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-semibold  mb-1"
                                >{{ $t("name") }}</label
                            >
                            <input
                                v-model="form.name"
                                type="text"
                                id="name"
                                class="w-full bg-background bg-background border border-main rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-secondary"
                                required
                            />
                            <p
                                v-if="form.errors.name"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>
                        <div class="flex flex-column xl:flex-row gap-2">

                        <div class="w-full">
                            <label
                                for="phone"
                                class="block text-sm font-semibold  mb-1"
                            >
                                {{ $t("phone_number") }}</label
                            >
                            <input
                                v-model="form.phone"
                                type="tel"
                                id="phone"
                                class="w-full bg-background border border-main rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-secondary"
                            />
                            <p
                                v-if="form.errors.phone"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.phone }}
                            </p>
                        </div>

                        <div class="w-full">
                            <label
                                for="address"
                                class="block text-sm font-semibold  mb-1"
                                >{{ $t("address") }}</label
                            >
                            <input
                                v-model="form.address"
                                type="text"
                                id="address"
                                class="w-full bg-background border border-main rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-secondary"
                            />
                            <p
                                v-if="form.errors.address"
                                class="text-red-500 text-sm mt-1"
                            >
                                {{ form.errors.address }}
                            </p>
                        </div></div>
                    </div>
                    <div class="my-4">
                        <label
                            for="message"
                            class="block text-sm font-semibold  mb-1"
                            >{{ $t("message") }}</label
                        >
                        <textarea
                            v-model="form.message"
                            id="message"
                            rows="8"
                            minlength="10"
                            class="w-full bg-background border border-main rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-secondary"
                            required
                        ></textarea>
                        <p
                            v-if="form.errors.message"
                            class="text-red-500 text-sm mt-1"
                        >
                            {{ form.errors.message }}
                        </p>
                    </div>

                    <div class="w-[200px]">
                        <Button type="third" text="" class="w-[200px]">
                            {{ $t("send") }}
                            <i class="fas fa-arrow-left mx-2"></i>
                        </Button>
                    </div>
                </form>
            </div> -->
        </div>
    </div>
</template>
