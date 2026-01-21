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
    client_reviews: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("client_reviews", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="client_reviews"
            tableName="client_reviews"
            tableAction="أضافة  رأي عميل"
            :tableActionLink="route('client_reviews.create')"
            :tableLink="route('client_reviews.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">آراء العملاء</h4>
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
                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr
                    v-for="client_review in client_reviews.data"
                    :key="client_review.id"
                >
                    <td>{{ client_review.id }}</td>
                    <td>
                        <img
                            v-if="client_review.media"
                            class="img-fluid rounded"
                            :src="client_review.media.thumb_url"
                            height="60"
                            width="60"
                        />
                        <p v-else>لايوجد صورة</p>
                    </td>
                    <td v-for="lang in langs" :key="lang.code">
                        {{ client_review.name[lang.code] }}
                    </td>

                    <td>{{ client_review.name["ar"] }}</td>

                    <td>{{ client_review.name["en"] }}</td>

                    <td>
                        <TableStatus :active="client_review.is_active" />
                    </td>
                    <td>
                        <ActiveAction
                            :active="client_review.is_active"
                            @click="
                                openModal(
                                    route(
                                        'client_reviews.active',
                                        client_review.id,
                                    ),
                                    'danger',
                                    client_review.id,
                                )
                            "
                        />
                        <Link
                            :href="
                                route('client_reviews.edit', client_review.id)
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
