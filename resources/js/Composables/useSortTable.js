// 📁 src/composables/useSortTable.js
import { ref, onMounted } from "vue";
import { router } from "@inertiajs/vue3";

export function useSortTable(tableName, filters = {}) {
    const sortColumn = ref(filters.sort || "id");
    const sortDirection = ref(filters.direction || "desc");
    const searchQuery = ref(filters.search || "");
    const perPage = ref(filters.perPage || 10);

    // 🟢 ترتيب الجدول عند إضافة `sortable` لأي عنصر
    const sortBy = (column) => {
        sortDirection.value =
            sortColumn.value === column && sortDirection.value === "asc"
                ? "desc"
                : "asc";

        sortColumn.value = column;
        router.get(
            `/${tableName}`,
            {
                search: searchQuery.value,
                sort: sortColumn.value, // ← استخدم القيمة الحالية بعد التبديل
                direction: sortDirection.value,
                perPage: perPage.value,
                page: 1, // إعادة إلى الصفحة الأولى عند تغيير الترتيب
            },
            {
                preserveState: true,
                replace: true, // لمنع إضافة تاريخ جديد في المتصفح
            }
        );
    };

    // 📌 مراقبة العناصر التي تحمل `sortable` وإضافة الحدث تلقائيًا
    onMounted(() => {
        const observer = new MutationObserver(() => {
            document.querySelectorAll(".sortable").forEach((th) => {
                const column = th.getAttribute("data-column");
                if (!th.dataset.bound) {
                    th.dataset.bound = true; // منع التكرار
                    th.addEventListener("click", () => sortBy(column));
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    });

    return {
        sortColumn,
        sortDirection,
        sortBy,
    };
}
