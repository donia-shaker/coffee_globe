<template>
  <div class="ant-import-finish">
    <div> 
      <CheckCircleOutlined class="ant-icon-success" />
    </div>
    <div>Import successful</div>
    <div class="ant-import-action">
      <AButton
        type="primary"
        size="large"
        @click="handleFinish"
      >
        check the details
      </AButton>
    </div>
  </div>
</template>

<script setup> 
const emit = defineEmits(["finish"])

// Methods
function handleFinish() {
  emit("finish")
}
</script> 

<style>
.ant-import-finish {
    text-align: center;
}

.ant-import-finish .ant-icon-success {
    font-size: 150px;
    line-height: 1.2;
    color: #67c23a;
}
</style>
