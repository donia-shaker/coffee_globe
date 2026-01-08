<!-- eslint-disable no-unused-vars -->
<script setup> 
import Schema from "async-validator"
import cloneDeep from "lodash.clonedeep"
import { message, notification, Input } from "ant-design-vue" 
  import { h, inject, reactive, ref, computed, watch, onMounted  } from "vue"
  import { isString, isEmpty, isNumber, isBoolean   } from "lodash"

// Props
const props = defineProps({
  rules: {
    type: Object,
    default: () => ({}),
  },
  fields: {
    type: Object,
    required: true,
  },
  requestFn: {
    type: Function,
    required: true,
  },
  tableData: {
    type: Array,
    default: () => [],
  },
  formatter: Object,
  append: Object,
})


const emit = defineEmits(["save"])



const formRef = ref()

// Injected functions
const goNext = inject("goNext")
const goPre = inject("goPre")

// Reactive state
const isLoading = ref(false)
const errorData = ref({})
const formErors = ref({})


const form = reactive({
  data: props.tableData,
})

const dataPagination = reactive({
  defaultCurrent: 1,
  defaultPageSize: 10,
  hideOnSinglePage: true,
})

 

// Computed property for error table data
const errorTableData = computed(() => {
  const errorTableData = []
  for (const index in errorData.value) {
    const messageArr = Object.values(errorData.value[index])

    errorTableData.push({
      row: Number(index) + 1,
      reason: messageArr.join("、"),
    })
  }
  
  return errorTableData
})

const state = reactive({

  mappingTable: [],
  tableOptions: [],


})


// Generate columns dynamically from fields
// const columns = computed(() => [
//   {
//     title: "#",
//     dataIndex: "key",
//     key: "key",
//     width: "50",
//     align: "left", // Optional: Set alignment or other properties here
//   },
//   ...Object.entries(props.fields).map(([dataIndex, title]) => ({
//     title,
//     dataIndex,
//     key: dataIndex,

//     // align: "left", // You can customize alignment or other column properties here
//   })),
//   {
//     title: "Actions",
//     dataIndex: "actions",
//     key: "action",
//     align: "center", // Optional: Customize alignment or other properties
//   },
// ])

const columns = computed(() =>
  Object.entries(props.fields).map(([dataIndex, title]) => ({
    title,
    dataIndex,
    key: dataIndex,
    align: "left", // You can customize alignment or other column properties here
  })),
)


const checkCells =(record, rowIndex) =>{
  // window.console.log('单元格：', this.errorData[record.key-1][column.property])
  return {
    style: errorData.value[record.key - 1] ? {
      'background-color': '#f56c6c',
    } : {},
  }
}

const mappingHeaders = () => {

  // state.mappingTable = result.fields.map(field => {
  //   return {
  //     field: field[0],
  //     required: field[1],
  //     value: result.headers.indexOf(field[0]) === -1 ? "" : field[0],
  //   }
  // })
  // state.tableOptions = result.headers.map(item => {
  //   return {
  //     value: item,
  //     label: item,
  //   }
  // })
}


// Methods
function checkCell({ row, column, rowIndex }) {
  if (
    errorData.value[rowIndex] &&
    errorData.value[rowIndex][column.property]
  ) {
    return "ant-import-error-cell"
  }
}

function validateData() {

  


  if (props.rules) {
    const validator = new Schema(props.rules)
    const localErrorData = {}

    errorData.value={}

    // console.log('errorData', errorData, tableData.value)
    // console.log('errorData=================', errorData.value)

   

    form.data.forEach((item, index) => {
      item.key = index + 1
      validator.validate(item, errors => {
        // console.log('validator', item, errors)


        if (errors) {
          errorData.value[index] = {}
          errors.forEach(error => {
            errorData.value[index][error.field] = error.message
          })
        }
      })
    })

    
  }
}

// Validate a single field
function validateField(recordIndex, field, value) {

  errorData.value={}

  if (props.rules[field]) {
    const validator = new Schema({ [field]: props.rules[field] })

    validator.validate({ [field]: value }, errors => {
      if (errors) {
        if (! errorData.value[recordIndex]) {
          errorData.value[recordIndex] = {}
        }
        errorData.value[recordIndex][field] = errors[0].message
      } else {
        if ( errorData.value[recordIndex]) {
          delete  errorData.value[recordIndex][field]
          if (!Object.keys( errorData.value[recordIndex]).length) {
            delete  errorData.value[recordIndex]
          }
        }
      }
    })
  }
}

function handlePre() {
  goPre()
}


watch(
  () => form.data,
  () => {
    validateData()
  },
  { deep: true },
)

function findKey(obj, value, compare = (a, b) => a === b) {
  let key = Object.keys(obj).find(k => compare(obj[k], value))
  
  return !isNaN(Number(key)) ? Number(key) : key
}

function changeData(tableData) {
  const formatter = props.formatter
  if (formatter) {
    tableData = tableData.map(item => {
      for (const key in item) {
        if (formatter[key]) {
          if (typeof formatter[key] === "function") {
            item[key] = formatter[key](item[key], item)
          } else {
            item[key] = findKey(formatter[key], item[key])
          }
        }
      }
      
      return item
    })
  }
  
  return tableData
}

async function nextStep() {

  try {
    await formRef.value?.validateFields()
    await handleRequest()
  }
  catch (errorInfo) {
    console.log('Failed:', errorInfo)
    formErors.value=errorInfo
  }


  // formRef.value?.validateFields().then(async () => {
   
  // })
}


async function handleRequest() {
  if (isLoading.value) return


  validateData()

  if (errorTableData.value.length) {
    var Index=errorTableData.value[0].row
    var reson=errorTableData.value[0].reason
    notification.error({
      title: 'تَلمِيح',
      message: `${reson}  رقم السطر (${Index})  او  يمكنك التحميل مرة أخرى بعد معالجة الخطأ`,
      // message: 'يرجى التحميل مرة أخرى بعد معالجة الخطأ',
    })
    
    return
  }

  isLoading.value = true
  let tableData = cloneDeep(form.data)

  // Change value
  tableData = changeData(tableData)

  // Add additional data
  if (props.append) {
    tableData = tableData.map(item => ({
      ...item,
      ...props.append,
    }))
  }

  form.data = [
    ...tableData,
  ]

  emit('save', form.data)

  try {
    await props.requestFn(form.data)
    // message.success("تم الاستيراد بنجاح")
    goNext()
  } catch (error) {
    Object.assign(errorData, error)
    message.error("فشل الاستيراد، يرجى المحاولة مرة أخرى")
  } finally {
    isLoading.value = false
  }
}

// Lifecycle hook
onMounted(() => {
  validateData()
})


// function customCell(record, rowIndex, column) {
 

//   return {
//     children: column.editable
//       ? h(Input, {
//         'v-model:value': record[column.dataIndex], // Use v-model equivalent in render function
//         onBlur: () => handleEditBlur(record, column.dataIndex), // Event handling
//       })
//       : record[column.dataIndex], // Default value if not editable
//   }
// }

 

function deleteRow(record) {
  // console.log('errorData================= deleteRow', record, form.data)

  // var newdata = form.data.filter(row => row.key != record.key)

  // console.log('newdata', newdata)
  
  form.data = form.data.filter(row => row.key != record.key)
 


  // // Check if the row exists in errorData, and if so, delete it
  if (Object.prototype.hasOwnProperty.call( errorData.value, record.key - 1)) {
    delete  errorData.value[record.key - 1]
  }
 
  
  // Revalidate data after deletion
  validateData()
}

const isInput = val => {
  // if (val) {
  //   console.log('val:', val, val.length)
  // }

  var formatVal=`${val}`
 
  return isString(val) || isEmpty(val) || (isNumber(val) && formatVal.length>6)
}


const isNumberOnly = val => {
  // if (val) {
  //   console.log('val:', val, val.length)
  // }

  var formatVal=`${val}`
 
  return isNumber(val) && formatVal.length<6
}

// Handle blur for input validation
function handleEditBlur(record, field) {
  const recordIndex = form.data.findIndex(item => item.key === record.key)
  if (recordIndex >= 0) {
    validateField(recordIndex, field, record[field])
  }
}
</script>


<template>
  <div>
    <!-- Error display table -->
    <template v-if="errorTableData.length">
      <h1 style="color: #f56c6c">
        Error message display
      </h1>

      <ATable
        :data-source="errorTableData"
        bordered
        class="import-error-table"
        style="width: 100%"
        row-key="row"
      >
        <ATableColumn
          key="row"
          title="رقم سطر الخطأ"
          data-index="row"
          width="180"
        />
        <ATableColumn
          key="reason"
          title="سبب الخطأ"
          data-index="reason"
        />
      </ATable>
    </template>
    <!-- Data list --> 
    <h1> قائمة البيانات </h1>
    <!-- :columns="columns" -->
    <AForm
      ref="formRef"
      :model="form"
    >
      <ATable
        :cell-class-name="checkCell"
        :data-source="form.data"
        :pagination="dataPagination"
        bordered
        :loading="isLoading"
        row-key="key"
        style="width: 100%"
      >
        <!--
          <ATableColumn
          align="center"
          title="行号"
          key="key"
          data-index="key"
          width="50"
          /> 
        -->

        <ATableColumn
          key="key"
          title="#"
          data-index="key"
          width="50"
          align="center"
        >
          <template #default="{ record }">
            {{ record.key }}
          </template>
        </ATableColumn>

   


        <ATableColumn
          v-for="(column, index) in columns"
          :key="index"
          :title="column.title"
          align="center"
          :custom-cell="checkCells"
        >
          <template #default="{ record, index: scopeIndex }">
            <AFormItem 
              :name="['data',scopeIndex,column.key]"
              :rules="rules?.[column.key]"
              class="mb-0"
            >
              <ASwitch
                v-if="isBoolean(record?.[column.dataIndex])"
                v-model:checked="record[column.dataIndex]"
                :placeholder="column.title"
              /> 
              <!--
                <AppTelIField
                v-else-if="column.dataIndex=='phone' || column.dataIndex=='tel' || column.dataIndex=='mobile'"
                v-model="record[column.dataIndex]"  
                v-model:code="record.country_code" 
                v-model:tel="record.tel"
                v-model:number="record.uuid"
                :label="$t('lang.phone')"
                :placeholder="$t('lang.phone')"
                autocomplete="on"
                name="phone"   
                :only-countries="['SA','YE','ae','qa','kw','jo','eg']"
                /> 
              -->
              <AInputNumber
                v-else-if="isNumberOnly(record?.[column.dataIndex])"
                v-model:value="record[column.dataIndex]"
                :placeholder="column.title"
                :status="errorData?.[scopeIndex]?.[column.dataIndex] ? 'error' : ''"
                @blur="handleEditBlur(record, column.dataIndex)"
              /> 
              <AInput
                v-else
                v-model:value="record[column.dataIndex]"
                :placeholder="column.title"
                @blur="handleEditBlur(record, column.dataIndex)"
                :status="errorData?.[scopeIndex]?.[column.dataIndex] ? 'error' : ''"
              >
                <template #suffix>
                  <ATooltip :title="errorData?.[scopeIndex]?.[column.dataIndex] || ''">
                    <InfoCircleOutlined style="color: rgba(0, 0, 0, 0.45)" />
                  </ATooltip>
                </template>
              </AInput>
            </AFormItem>

         
          <!--
            <span>
            {{ record?.[column.dataIndex] || "&nbsp;" }}
            </span> 
          -->
          </template>
        </ATableColumn>
        <ATableColumn
          key="actions"
          title="Actions"
          align="center"
          width="80"
        >
          <template #default="{ record }">
            <DeleteOutlined @click="deleteRow(record)" /> 
          </template>
        </ATableColumn>
      </ATable>

   
      <div class="ant-import-action">
        <AButton
          size="large"
          type="primary"
          @click="handlePre"
        >
          اعادة تحميل 
        </AButton>
        <AButton
          type="primary"
          size="large"
          :loading="isLoading"
          @click="nextStep"
        >
          {{ $t('pagination.next') }}
        </AButton>
      </div>
    </AForm>
  </div>
</template>
 

<style>
.import-error-table {
    margin-bottom: 20px;
}

.ant-import-error-cell {
    color: white;
    background: #f56c6c !important;
}
.ant-import-error-cell:hover {
    background-color: #f56c6c !important;
    background: #f56c6c !important;
}
</style>
