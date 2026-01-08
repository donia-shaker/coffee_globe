<template>
  <div>
    <!-- Tip list -->
    <div v-if="tips && tips.length">
      <AAlert
        v-for="(item, index) of tips"
        :key="index"
        closable
        show-icon
        type="warning"
        :message="index + 1 + '、' + item"
      />
    </div>

    <!-- Upload component -->
    <AUploadDragger
      accept=".xls,.xlsx"
      list-type="picture-card"
      :before-upload="beforeUpload"
      :file-list="fileList"
      :custom-request="fakeRequeset"
      :show-upload-list="false"
      action="/"
      :disabled="isLoading"
      class="ant-import-upload-uploader"
    >
      <div class="p-5">
        <CloudUploadOutlined style="color: #3399ff; font-size: 60px" />
        <p>انقر هنا أو اسحب الملف لتحميله</p>
      </div>


      <!--
        <CloudUploadOutlined class="ant-icon-upload" />
      
        <div class="ant-upload__text">
        Drag your completed document here, or
        <em>Click to upload</em>
        </div> 
      -->
    </AUploadDragger>

    <!-- operate -->
    <div class="ant-import-action">
      <AButton
        type="primary"
        size="large"
        @click="goPre"
      >
        {{ $t('pagination.previous') }}
      </AButton>
      <AButton
        type="primary"
        size="large"
        :loading="isLoading"
        @click="handleGoNext"
      >
        {{ $t('pagination.next') }}
      </AButton>
    </div>
  </div>
</template>

<script setup> 
import { notification } from "ant-design-vue" // Assuming Ant Design Vue is used
// eslint-disable-next-line import/no-unresolved
import { importFileExcel } from "../useExcel.js"
  import { h, inject, reactive, ref, computed, watch, onMounted  } from "vue" 
// Props
const props = defineProps({
  tips: Array,
  fields: {
    type: Object,
    required: true,
  },
})

// Emits
const emit = defineEmits(["upload"])

// Injected functions
const goNext = inject("goNext")
const goPre = inject("goPre")

// Reactive data
const fileList = ref([])
const isLoading = ref(false)

// Methods
// eslint-disable-next-line no-unused-vars
function fakeRequeset() {
  fileList.value = []
}

function checkType(file) {
  const fileExt = file.name.split(".").pop().toLocaleLowerCase()
  const extArr = ["xlsx", "xls", "csv"]
  
  return extArr.includes(fileExt)
}

function uploadError(message) {
  notification.error({
    message: "There was an error in uploading",
    description: message,
  })
}

function checkTableTitle(columns) {
  const titles = Object.values(props.fields)

  titles.forEach(item => {
    if (!columns.includes(item)) {
      notification.error({
        message: "The data is wrong",
        description: `${item} Column not found`,
      })
    }
  })
}

function handleGoNext() {
  notification.error({
    message: "Hint",
    description: "Please upload data first",
  })
}

// function changeDatakeyAndFilter(arr) {
//   return arr.map(item => {
//     const res = {}
//     for (const key in props.fields) {
//       res[key] = item[props.fields[key]]
//     }
    
//     return res
//   })
// }

function changeDatakeyAndFilter(arr) {
  return arr
    .map(item => {
      const res = {}
      let allNullOrUndefined = true

      for (const key in props.fields) {
        const value = item[props.fields[key]]

        // Replace null/undefined with an empty string
        res[key] = value == null ? "" : value

        // Check if the value is not null/undefined
        if (value != null) {
          allNullOrUndefined = false
        }
      }

      // Only return the item if not all values are null/undefined
      return allNullOrUndefined ? null : res
    })
    .filter(item => item !== null) // Remove items that are entirely null/undefined
}


async function beforeUpload(file) {
  if (isLoading.value) return false

  // Detect file type
  if (!checkType(file)) {
    uploadError(
      `Document: ${file.name} The file type is wrong. Please modify the template file before uploading.`,
    )
    
    return false
  }

  isLoading.value = true
  try {
    const { columns, tableData } = await importFileExcel(file)

    if (!(columns.length && tableData.length)) {
      uploadError("Please open the template file and fill in the data")
    } else {
      checkTableTitle(columns)
      emit("upload", columns, changeDatakeyAndFilter(tableData))
      goNext()
    }
  } catch (e) {
    console.error(e)
    uploadError(
      "An error occurred while reading the file, please upload again",
    )
  } finally {
    isLoading.value = false
  }

  return false
}
</script> 

 


<style>
.ant-import-upload-tips {
    padding: 0 20px;
    line-height: 20px;
    list-style-type: decimal;
}

.ant-import-upload-uploader {
    margin-top: 20px;
    text-align: center;
}
.ant-icon-upload {
    font-size: 50px;
    line-height: 1.2;
    align: center;
}
</style>
