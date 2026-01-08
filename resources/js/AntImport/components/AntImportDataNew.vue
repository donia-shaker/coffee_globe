<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable vue/component-api-style -->
<template>
  <div>
    <!-- 错误显示表 -->
    <template v-if="errorTableData.length">
      <h1 style="color: #f56c6c;margin-top: 20px;">
        عرض رسالة خطأ
      </h1>

      <ATable
        :data-source="errorTableData"
        bordered
        class="import-error-table"
        style="width: 100%"
        row-key="row"
      >
        <ATableColumn
          title="رقم سطر الخطأ"
          key="row"
          data-index="row"
          width="180"
        />
        <ATableColumn
          title="سبب الخطأ"
          key="reason"
          data-index="reason"
        />
      </ATable>
    </template>
    <!-- 数据列表 -->
    <h1 style="margin-top: 20px;">
      قائمة البيانات
    </h1>
    <ATable
      :cell-class-name="checkCell"
      :data-source="tableData"
      :pagination="dataPagination"
      bordered
      style="width: 100%"
      :loading="isLoading"
      row-key="key"
    >
      <ATableColumn
        align="center"
        title="行号"
        key="key"
        data-index="key"
        width="50"
      />

      <ATableColumn
        :key="field"
        :title="label"
        :data-index="field"
        align="left"
        header-align="center"
        v-for="(label, field) of fields"
        :custom-render="(text,record,index) => text"
        :custom-cell="checkCells"
      >
        <!-- 自定义错误显示 -->
        <template #default="scope">
          <ATooltip
            :content="errorData[scope.$index][field]"
            class="item"
            effect="dark"
            placement="top"
            v-if="errorData[scope.$index] && errorData[scope.$index][field]"
          >
            <div>{{ scope.row[field] || "&nbsp;" }}</div>
          </ATooltip>
          <template v-else>
            {{ scope.row[field] }}
          </template>
        </template>
      </ATableColumn>
    </ATable>

    <div class="ant-import-action">
      <ASpace>
        <AButton
          @click="handlePre"
          size="large"
          type="primary"
        >
          إعادة التحميل
        </AButton>
        <AButton
          type="primary"
          size="large"
          :loading="isLoading"
          @click="handleRequest"
        >
          الخطوة التالية
        </AButton>
      </ASpace>
    </div>
  </div>
</template>

<!-- eslint-disable no-unused-vars -->
<!-- eslint-disable vue/component-api-style -->
<script>
import Schema from 'async-validator'
import cloneDeep from 'lodash.clonedeep'

export default {
  name: 'AntImportData',
  inject: ['goNext', 'goPre'],
  props: {
    rules: {
      type: Object,
      default () {
        return {}
      },
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
      default () {
        return []
      },
    },
    formatter: Object,
    append: Object,
  },
  emits: ['pre'],
  data () {
    return {
      isLoading: false,
      errorData: {},
      dataPagination: {
        defaultCurrent: 1,
        defaultPageSize: 10,
        hideOnSinglePage: true,
      },
    }
  },
  computed: {
    errorTableData () {
      const errorData = this.errorData
      const errorTableData = []
      for (const index in errorData) {
        var messageArr = []
        for (const field in errorData[index]) {
          messageArr.push(errorData[index][field])
        }
        errorTableData.push({
          row: Number(index) + 1,
          reason: messageArr.join('、'),
        })
      }

      return errorTableData
    },
  },
  created () {
    this.validateData()
  },
  methods: {
    // 检查单元格是否错误
    checkCell ({ row, column, rowIndex }) {
      if (this.errorData[rowIndex] && this.errorData[rowIndex][column.property]) {
        return 'ant-import-error-cell'
      }
    },
    checkCells (record, rowIndex) {
      // window.console.log('单元格：', this.errorData[record.key-1][column.property])
      return {
        style: this.errorData[record.key - 1] ? {
          'background-color': '#f56c6c',
        } : {},
      }
    },

    // 校检数据
    validateData () {
      if (this.rules) {
        var validator = new Schema(this.rules)
        const errorData = []

        this.tableData.forEach((item, index) => {
          // 添加key
          this.tableData[index].key = index + 1
          validator.validate(item, (errors, fileds) => {
            if (errors) {
              errorData[index] = []
              errors.forEach(error => {
                errorData[index][error.field] = error.message
              })
            }
          })
        })

        this.errorData = errorData
      }
    },

    handlePre () {
      this.$emit('pre')
    },

    //Find key by value
    findKey (obj, value, compare = (a, b) => a === b) {
      let key = Object.keys(obj).find(k => compare(obj[k], value))
      if (!isNaN(Number(key))) {
        key = Number(key)
      }
      
      return key
    },

    //Change the value according to the formatter
    changeData (tableData) {
      const formatter = this.formatter
      if (formatter) {
        tableData = tableData.map(item => {
          for (const key in item) {
            if (formatter[key]) {
              if (typeof formatter[key] === 'function') {
                item[key] = formatter[key](item[key], item)
              } else {
                item[key] = this.findKey(formatter[key], item[key])
              }
            }
          }
          
          return item
        })
      }

      return tableData
    },

    // Send Request
    async handleRequest () {
      if (this.isLoading) return

      if (this.errorTableData.length) {
        this.$notification.error({
          title: 'تَلمِيح',
          message: 'يرجى التحميل مرة أخرى بعد معالجة الخطأ',
        })
        
        return
      }

      this.isLoading = true
      let tableData = cloneDeep(this.tableData)

      // Changing Values
      tableData = this.changeData(tableData)


      // Add additional data
      const appendData = this.append
      if (appendData) {
        tableData = tableData.map(item => {
          return Object.assign({}, item, appendData)
        })
      }
      try {
        await this.requestFn(tableData)
        this.$message.success('Import completed, please check the import details!')
        this.goNext()
      } catch (error) {
        this.errorData = error
        this.$message.error('Import failed, please try again!')
      } finally {
        this.isLoading = false
      }
    },
  },
}
</script>

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
