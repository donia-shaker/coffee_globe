import * as XLSX from 'xlsx/xlsx.mjs'

import allTrim from 'all-trim'

// Get the title row
function getHeaderRow(sheet) {
  const headers = []
  const range = XLSX.utils.decode_range(sheet['!ref'])
  let C
  const R = range.s.r
  for (C = range.s.c; C <= range.e.c; ++C) {
    var cell = sheet[XLSX.utils.encode_cell({ c: C, r: R })]
    var hdr = 'UNKNOWN ' + C
    if (cell && cell.t) hdr = XLSX.utils.format_cell(cell)
    headers.push(hdr)
  }
  
  return headers
}


export const generateExcelTemplate = (tempName, fields, data=[]) => {
  // Define the data for the Excel file
  // const data = [
  //   [...fields], // Headers
  //   [], // Sample data row
  // ]

  // Convert JSON structure to a 2D array
  // const headers = Object.entries(fields) // [["contact_group_id", "contact_group_id"], ["name", "الاسم"], ...]
  // const keys = Object.keys(fields)

  // const headers = Object.values(fields) // [["contact_group_id", "contact_group_id"], ["name", "الاسم"], ...]
  // var datafromTest = []
  
  // console.log('headers', headers, keys)

  // Extract header labels from the mapping object
  const headerLabels = Object.values(fields)


  const extractValue = (item, field) => {
    return field.split(".").reduce((acc, key) => acc && acc[key], item) || ""
  }


  const rows = data.map(item =>
    Object.keys(fields).map(field => extractValue(item, field)),
  )


  // Map data fields based on the mapping object
  // const rows = data.map(item => {
  //   return Object.keys(fields).map(field => item[field] || "") // Use empty string if field is missing
  // })



  
 
  // Combine headers and data rows
  const excelData = [headerLabels, ...rows]



   

  // const data = [headers, datafromTest]

  // const data = [["Key (English)", "Field (Arabic)"], ...headers] // Add column headers
  // Create a worksheet from the data
  const worksheet = XLSX.utils.aoa_to_sheet(excelData)

  // Create a workbook and append the worksheet
  const workbook = XLSX.utils.book_new()

  XLSX.utils.book_append_sheet(workbook, worksheet, "Template")

  // Generate Excel file and trigger download
  XLSX.writeFile(workbook, `${tempName}.xlsx`)
}


// Get array
const  getArrData=excelData=> {
  const workbook = XLSX.read(excelData, { type: 'array' })
  const firstSheetName = workbook.SheetNames[0]
  const worksheet = workbook.Sheets[firstSheetName]
  const columns = getHeaderRow(worksheet)
  const tableData = XLSX.utils.sheet_to_json(worksheet)

  return {
    columns: allTrim(columns, true),
    tableData: allTrim(tableData, true),
  }
}


export const  importFileExcel=file=> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()

    reader.readAsArrayBuffer(file)
    reader.onerror = e => {
      reject(e)
    }
    reader.onload = e => {
      const excelData = e.target.result
      const arrData = getArrData(excelData)

      resolve(arrData)
    }
  })
  
}
 


// export const useImportExcel = () => {
    



  
    
//   return {

//     importExcel,
//   }

// }