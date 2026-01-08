<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable import/extensions -->
<script setup> 
import AntImportDownload from "./components/AntImportDownload.vue"
import AntImportUpload from "./components/AntImportUpload.vue"
import AntImportData from "./components/AntImportData.vue"
import AntImportFinish from "./components/AntImportFinish.vue"
import "./vue-ant-import.css"
  import { h, inject, reactive, ref, provide  } from "vue"

// Props definition
const props=defineProps({
  filepath: { type: String, required: false },
  mustdownload: { type: Boolean, required: false },
  showLastStep: { type: Boolean, default: true },
  requestFn: { type: Function,  default: () => {} },
  fields: { type: Object, required: true }, 
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
})

// Emits definition
const emit = defineEmits(['close', 'update:visible', 'finish', 'handleData'])

console.log('dataImport fields', props.fields)

// const visible = useVModel(props, "visible", emit)

// Reactive data
const tableData = ref([])
const columns = ref([])
const currentStep = ref(0)

const formRef = ref()

const formData = reactive({
  tableData: [],
  columns: [],
  fields: props.fields,
  form: {},
})

// Provide functions for child components
provide('goPre', preStep)
provide('goNext', nextStep)

// Methods
function handleUpload(newColumns, newTableData) {
  columns.value = newColumns
  formData.tableData = newTableData
}

function initData() {
  formData.tableData = []
  columns.value= []
  formData.form= {}
  formData.fields= {}
  currentStep.value = 0
}

function handlClose() {
  initData()
  emit('close') 
   

}

function handleFinish() {
  handlClose()
  emit('finish')
}

const handleFormData = async data => {

  formData.tableData = data

  // console.log('handleData', formData)

  await props.requestFn(data)

  emit('handleData', formData)

}
 

async  function nextStep() {


  try {
    await formRef.value?.validateFields()
    currentStep.value++
  }
  catch (errorInfo) {
    console.log('Failed:', errorInfo)
  }
}

function preStep() {
  currentStep.value--
}

function handleStep3Pre() {
  formData.tableData = []
  columns.value= []
  formData.form= {} 
  preStep()
}
</script>

<template>
  <div>
    <ASteps
      :current="currentStep"
      size="small"
      class="my-6"
    >  
      <!-- <slot name="first-step" /> -->
      <AStep title="تنزيل القالب" />
      <AStep title="تحميل الملفات" />
      <AStep title="ضبط قواعد المطابقة" />
      <!-- <AStep title="استيراد" /> -->
      <AStep
        v-if="showLastStep"
        title="اكتمل الاستيراد"
      />
      <!-- <slot name="last-step" /> -->
    </ASteps>
    <!-- Download template -->

 

    <div v-if="currentStep === 0">
      <AForm
        ref="formRef"
        :model="formData"
      >
        <slot
          name="form"
          :form="formData.form"
        />

        <AntImportDownload
      
          :filepath="filepath"
          :fields="fields"
          :title="title"
          :temp-data="tempData"
          :mustdownload="mustdownload"
        />
      </AForm>
    </div>
  

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
      :table-data="formData.tableData"
      @pre="handleStep3Pre"
      @save="handleFormData"
    />

    <!-- End of import -->
    <AntImportFinish
      v-if="currentStep === 3 && showLastStep"
      @finish="handleFinish"
    /> 
  </div>
</template>

 



 


<style>
.ant-import-action {
    margin-top: 20px;
    text-align: center;
}
</style>
