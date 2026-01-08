<script setup>
import { ref } from "vue";
import { useSortTable } from "@/Composables/useSortTable";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link } from "@inertiajs/vue3";
import DataTableData from "@/Components/DataTableData.vue";
import TableStatus from "@/Components/TableStatus.vue";
import ActiveAction from "@/Components/ActiveAction.vue";
import EditAction from "@/Components/EditAction.vue";
import { useModal } from "@/Composables/useModal";
import FormModel from "@/Components/FormModel.vue";

const { openModal } = useModal();

// استيراد البيانات عبر الـ props
const props = defineProps({
    about_page_infos: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("about_page_infos", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="about_page_infos"
            tableName="about_pages"
            :tableLink="route('about_page_infos.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">من نحن</h4>
            </template>

            <template v-slot:thead>
                <tr>
                    <th class="sortable" data-column="id"># ⬍</th>
                    <th data-column="id">الصورة</th>
                    <th
                        v-for="lang in langs"
                        :key="lang.code"
                        class="sortable"
                        :data-column="`name_${lang.code}`"
                    >
                        {{ `الاسم ${lang.code}` }} ⬍
                    </th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr
                    v-for="about_page_info in about_page_infos.data"
                    :key="about_page_info.id"
                >
                    <td>{{ about_page_info.id }}</td>
                    <td>
                        <img
                            v-if="about_page_info.media"
                            class="img-fluid rounded"
                            :src="about_page_info.media.thumb_url"
                            height="60"
                            width="60"
                        />
                        <p v-else>لايوجد صورة</p>
                    </td>

                    <td v-for="lang in langs" :key="lang.code">
                        {{ about_page_info.name[lang.code] }}
                    </td>
                    <td>
                        <Link
                            :href="
                                route(
                                    'about_page_infos.edit',
                                    about_page_info.id
                                )
                            "
                        >
                            <EditAction />
                        </Link>
                    </td>
                </tr>
            </template>
        </DataTableData>
    </DashboardLayout>
</template>

<style scoped>
.sortable {
    cursor: pointer;
    user-select: none;
}
.sortable:hover {
    background-color: #f8f9fa;
}
</style>
