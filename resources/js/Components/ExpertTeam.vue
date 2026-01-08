<template>
    <section class="relative bg-white py-[80px] text-center overflow-hidden">
        <div class="container relative z-10">
            <div class="mb-10 text-center w-full">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold "
                    style="line-height: 1.5"
                >
                    {{ $t("latest_news") }}
                </h2>
                <p class="text-primary  mx-auto max-w-[700px]" style="line-height: 1.8;">فريق Coffee Globe يحمل شهادات دولية في تقييم البن والتحميص، وجاهز لتقديم الدعم والتدريب للشركات، الفنادق، وسلاسل المقاهي</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 ">
                <div
                    class="text-main bg-background rounded-xl w-full p-6"
                    v-for="feature in 6"
                >
                    <div class="text-center">
                        <h3 class="font-bold text-lg md:text-xl">الجودة</h3>
                        <p
                            class="text-sm text-primary py-2 h-[150px]  overflow-y-auto hide-scroll"
                            style="line-height: 2"
                        >
                            ·       نستخدم أدوات تحليل دقيقة ومنحنيات
                            تحميص(Roast Profiles) ونظام اختبار دوري (Cupping) في
                            كل مرة، وذلك لضمان أن النكهة والجودة التي تحبها
                            اليوم هي نفسها التي ستجدها كل يوم، هذا الالتزام هو
                            سر ثبات مذاق Coffee Globe
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
