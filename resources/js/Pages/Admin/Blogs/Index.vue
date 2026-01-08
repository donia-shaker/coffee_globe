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
    blogs: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("blogs", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="blogs"
            tableName="blogs"
            tableAction="أضافة  مدونة"
            :tableActionLink="route('blogs.create')"
            :tableLink="route('blogs.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">المدونة </h4>
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
                <tr v-for="blog in blogs.data" :key="blog.id">
                    <td>{{ blog.id }}</td>
                    <td>
                        <img
                            v-if="blog.media"
                            class="img-fluid rounded"
                            :src="blog.media.thumb_url"
                            height="60"
                            width="60"
                        />
                        <p v-else>لايوجد صورة</p>
                    </td>
                    <td>{{ blog.name['ar'] }}</td>

                    <td>{{ blog.name['en'] }}</td>

                    <td>
                        <TableStatus :active="blog.is_active" />
                    </td>
                    <td>
                        <ActiveAction :active="blog.is_active" @click="openModal(route('blogs.active', blog.id), 'danger', blog.id)"/>
                        <Link :href="route('blogs.edit', blog.id)">
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
