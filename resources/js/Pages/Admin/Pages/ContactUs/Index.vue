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
    contact_us_infos: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("contact_uss", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="contact_us_infos"
            tableName="contact_us_infos"
            tableAction="أضافة  وسيلة تواصل جديدة"
            :tableActionLink="route('contact_us_infos.create')"
            :tableLink="route('contact_us_infos.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">وسائل التواصل</h4>
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
                    <th
                        v-for="lang in langs"
                        :key="lang.code"
                        class="sortable"
                        :data-column="`name_${lang.code}`"
                    >
                        {{ `القيمة ${lang.code}` }} ⬍
                    </th>

                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr
                    v-for="contact_us_info in contact_us_infos.data"
                    :key="contact_us_info.id"
                >
                    <td>{{ contact_us_info.id }}</td>
                    <td>
                        <i :class="contact_us_info.icon"></i>
                    </td>
                    <td v-for="lang in langs" :key="lang.code">
                        {{ contact_us_info.name[lang.code] }}
                    </td>
                    <td v-for="lang in langs" :key="lang.code">
                        {{ contact_us_info.value[lang.code] }}
                    </td>
                    <td>
                        <TableStatus :active="contact_us_info.is_active" />
                    </td>
                    <td>
                        <ActiveAction
                            :active="contact_us_info.is_active"
                            @click="
                                openModal(
                                    route(
                                        'contact_us_infos.active',
                                        contact_us_info.id
                                    ),
                                    'danger',
                                    contact_us_info.id
                                )
                            "
                        />
                        <Link
                            :href="
                                route(
                                    'contact_us_infos.edit',
                                    contact_us_info.id
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
