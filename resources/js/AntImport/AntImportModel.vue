<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup> 
import AntImportDownload from "./components/AntImportDownload.vue"
import AntImportUpload from "./components/AntImportUpload.vue"
import AntImportData from "./components/AntImportData.vue"
import AntImportFinish from "./components/AntImportFinish.vue"
import "./vue-ant-import.css"
  import { h, inject, reactive, ref  } from "vue"
// Props definition
const props=defineProps({
  filepath: { type: String, required: false },
  mustdownload: { type: Boolean, required: false },
  requestFn: { type: Function, required: true },
  fields: { type: Object, required: true },
  visible: { type: Boolean, required: true },
  title: { type: String, default: 'import' },
  append: Object,
  tips: Array,
  tempData: Array,
  rules: Object,
  formatter: {
    type: Object,
    validator(formatter) {
      for (const key in formatter) {
        if (!(formatter[key] instanceof Object)) {
          console.error(`${key}The value must be an object or a function`)
          
          return false
        }
      }
      
      return true
    },
  },
  dialogWidth: { type: String, default: '80%' },
})

// Emits definition
const emit = defineEmits(['close', 'update:visible', 'finish'])

console.log('dataImport fields', props.fields)

// const visible = useVModel(props, "visible", emit)

// Reactive data
const tableData = ref([])
const columns = ref([])
const currentStep = ref(0)

// Provide functions for child components
provide('goPre', preStep)
provide('goNext', nextStep)

// Methods
function handleUpload(newColumns, newTableData) {
  columns.value = newColumns
  tableData.value = newTableData
}

function initData() {
  tableData.value = []
  columns.value = []
  currentStep.value = 0
}

function handlClose() {
  initData()
  emit('close')
  console.log('ss')
  
  // visible.value=false
  emit('update:visible', false)

}

function handleFinish() {
  handlClose()
  emit('finish')
}

function nextStep() {
  currentStep.value++
}

function preStep() {
  currentStep.value--
}

function handleStep3Pre() {
  tableData.value = []
  columns.value = []
  preStep()
}

// Watchers or lifecycle hooks (if needed)
watch(
  () => props.visible,
  newVal => {
    if (!newVal) {
      initData()
    }
  },
)
</script>

<template>
  <div>
    <AModal
      v-if="visible"
      :title="title"
      :open="visible"
      :width="dialogWidth"
      append-to-body
      :footer="null"
      @cancel="handlClose"
    >
      <ASteps
        :current="currentStep"
        size="small"
        class="my-6"
      > 
        <!--
          <AStep title="Download template" />
          <AStep title="upload files" />
          <AStep title="Confirm data" />
          <AStep title="Finish" /> 
        -->

        <AStep title="تنزيل القالب" />
        <AStep title="تحميل الملفات" />
        <AStep title="ضبط قواعد المطابقة" />
        <!-- <AStep title="استيراد" /> -->
        <AStep title="اكتمل الاستيراد" />
      </ASteps>
      <!-- Download template -->
      <AntImportDownload
        v-if="currentStep === 0"
        :filepath="filepath"
        :fields="fields"
        :title="title"
        :temp-data="tempData"
        :mustdownload="mustdownload"
      />

      <!-- Upload Excel -->
      <AntImportUpload
        v-if="currentStep === 1"
        :fields="fields"
        :tips="tips"
        @upload="handleUpload"
      />

      <!-- Data display -->
      <AntImportData
        v-if="currentStep === 2"
        :append="append"
        :fields="fields"
        :formatter="formatter"
        :request-fn="requestFn"
        :rules="rules"
        :table-data="tableData"
        @pre="handleStep3Pre"
      />

      <!-- End of import -->
      <AntImportFinish
        v-if="currentStep === 3"
        @finish="handleFinish"
      />
    </AModal>
  </div>
</template>

 



 


<style>
.ant-import-action {
    margin-top: 20px;
    text-align: center;
}
</style>
