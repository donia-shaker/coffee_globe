import { createI18n } from "vue-i18n";

const i18n = createI18n({
    locale: "ar", // Default language
    messages: {
        en: {
            home: "Home",
            about: "About us",
            services: "Services",
            products: "Products",
            categories: "Categories",
            contact: "Contact Us",
            admin: 'Admin ',
            supplier: 'Supplier ',
        },
        ar: {
            home: "الرئيسية",
            about: "من نحن",
            services: "خدماتنا",
            products: "منتجاتنا",
            categories: "الاقسام",
            contact: "تواصل بنا",
            
            admin: 'مدير النظام',
            supplier: 'مورد',
        },
    },
});

export default i18n;
