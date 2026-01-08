<!-- eslint-disable import/no-unresolved -->
<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable vue/no-unused-properties -->
<!-- eslint-disable vue/no-template-target-blank -->
 <!-- eslint-disable vue/no-unused-properties -->
<script setup> 
import { generateExcelTemplate } from "../useExcel.js"
  import { h, inject, reactive, ref, computed, watch, onMounted  } from "vue"
  import { isString, isEmpty, isNumber  } from "lodash"
// import { FileExcelOutlined,  } from "@ant-design/icons-vue"
// Props
const props = defineProps({
  tips: {
    type: Array,
    default: () => [],
  },
  fields: {
    type: Object, 
  },
  tempData: Array,
  title: { type: String, default: 'import' },
  filepath: {
    type: String,
    required: false,
  },
  mustdownload: {
    type: Boolean,
    required: true,
  },
})

// Injected functions
const goNext = inject("goNext")

// Reactive data
const hasDownload = ref(false)

// const message = useMessage()


// Methods
function handleDownload() {
  // Cookie.set(`ant-import-download-${props.filepath}`, true)
  // console.log('props.tempData', props.tempData)
  generateExcelTemplate(props.title, props.fields, props.tempData)
   
  // hasDownload.value = true
}

// function handleNext() {
//   if (hasDownload.value) {
//     goNext()
//   } else {
//     message.error("You have not downloaded the template file yet")
//   }
// }

// function checkHasDownload() {
//   if (props.mustdownload) {
//     hasDownload.value = !!Cookie.get(`ant-import-download-${props.filepath}`)
//   } else {
//     hasDownload.value = true
//   }
// }

// // Lifecycle hooks
// onMounted(() => {
//   checkHasDownload()
// })
</script> 

<template>
  <div class="ant-import-download mt-4 pl-6 py-4 border-solid border-left border-red-200">
    <ol class="">
      <li>
        يتم دعم ملفات EXCEL فقط.
        <a @click="handleDownload">&gt;&gt;انقر لتنزيل القالب&lt;&lt;</a>
      </li>

      <li>يرجى محاولة التأكد من أن أسماء أعمدة البيانات التي تم تحميلها متوافقة مع أسماء أعمدة البيانات المستهدفة. وسيطابق النظام تلقائيًا بناءً على أسماء الأعمدة.</li>

      <li
        v-for="(tip, index) in tips"
        :key="index"
      >
        {{ tip }}
      </li>
    </ol>

    <a 
      :underline="false" 
      @click="handleDownload"
    >
      <FileExcelOutlined class="ant-icon-document" /> 
    </a>
    <div>
      <!-- :href="filepath" -->
      <a
       
        :underline="false"
        target="_blank"
        @click="handleDownload"
      >انقر لتنزيل القالب</a>
    </div>

    <div class="ant-import-action">
      <!--
        <AButton
        type="success"
        style="background-color: #67c23a"
        size="large"
        @click="handleDownload"
        >
        <a 
        :underline="false"
        style="background-color: #67c23a; color: white"
        target="_blank"
        >انقر لتنزيل القالب</a>
        </AButton> 
      -->
      <AFlex
        gap="middle"
        justify="flex-end"
      >
        <AButton
          type="primary" 
          @click="goNext"
        >
          {{ $t('pagination.next') }}

          <ArrowLeftOutlined />
        </AButton>
      </AFlex>
    </div>
  </div>
</template>




<style scoped>
.ant-import-download {
    text-align: center;
}

.ant-import-download .ant-icon-document {
    font-size: 50px;
    line-height: 1.2;
    color: #67c23a;
}
</style>
