function dataTableWithButtons(
    url,
    columns,
    printCols,
    addUrl = null,
    addBtnName = null,
    filters = null,
    canExport = true,
    columnDefs = null
) {
    const table = $(".datatables-basic").DataTable({
        // data: data,
        processing: true,
        order: [[0, "desc"]],
        serverSide: true,
        ajax: url,
        columns: columns,
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        buttons: canExport
            ? [
                  {
                      extend: "collection",
                      className: "btn btn-label-primary dropdown-toggle me-2",
                      text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                      buttons: [
                          {
                              extend: "print",
                              text: '<i class="bx bx-printer me-1"></i>Print',
                              className: "dropdown-item",
                              exportOptions: {
                                  columns: printCols,
                                  scrollX: true, // Enable horizontal scrolling
                                  scrollCollapse: true,
                                  modifier: {
                                      page: "all", // Prints all pages, not just the current page
                                  },
                                  format: {
                                      body: function (data) {
                                          var parsed = $.parseHTML(data);
                                          var result = "";
                                          $.each(parsed, function (idx, el) {
                                              if (
                                                  el.classList &&
                                                  el.classList.contains(
                                                      "user-name"
                                                  )
                                              ) {
                                                  result +=
                                                      el.lastChild.firstChild
                                                          .textContent;
                                              } else {
                                                  result +=
                                                      el.innerText ||
                                                      el.textContent ||
                                                      "";
                                              }
                                          });
                                          return result;
                                      },
                                  },
                              },
                              action: function (e, dt, button, config) {
                                  var oldPage = dt.page.len(); // Store current page length
                                  dt.page.len(-1).draw(); // Show all rows before printing
                                  $.fn.DataTable.ext.buttons.print.action.call(
                                      this,
                                      e,
                                      dt,
                                      button,
                                      config
                                  );
                                  dt.page.len(oldPage).draw(); // Reset to original pagination after printing
                              },
                              customize: function (win) {
                                  $(win.document.body)
                                      .css("color", config.colors.headingColor)
                                      .css(
                                          "border-color",
                                          config.colors.borderColor
                                      )
                                      .css("direction", "rtl")
                                      .css("text-align", "right")
                                      .css(
                                          "background-color",
                                          config.colors.bodyBg
                                      );
                                  $(win.document.body)
                                      .find("table")
                                      .addClass("compact")
                                      .css("color", "inherit")
                                      .css("border-color", "inherit")
                                      .css("background-color", "inherit");
                              },
                          },
                          {
                              extend: "csv",
                              text: '<i class="bx bx-file me-1"></i>Csv',
                              className: "dropdown-item",
                              exportOptions: {
                                  columns: printCols,
                                  format: {
                                      body: function (data) {
                                          var parsed = $.parseHTML(data);
                                          var result = "";
                                          $.each(parsed, function (idx, el) {
                                              if (
                                                  el.classList &&
                                                  el.classList.contains(
                                                      "user-name"
                                                  )
                                              ) {
                                                  result +=
                                                      el.lastChild.firstChild
                                                          .textContent;
                                              } else {
                                                  result +=
                                                      el.innerText ||
                                                      el.textContent ||
                                                      "";
                                              }
                                          });
                                          return result;
                                      },
                                  },
                              },
                          },
                          {
                              extend: "excel",
                              text: '<i class="bx bxs-file-export me-1"></i>Excel',
                              className: "dropdown-item",
                              exportOptions: {
                                  columns: printCols,
                                  format: {
                                      body: function (data, row, column, node) {

                                          // If data is already plain text or number, return it directly
                                          if (
                                              typeof data === "number" ||
                                              !/<[a-z][\s\S]*>/i.test(data)
                                          ) {
                                              return data;
                                          }

                                          // If data is HTML, parse and extract text content
                                          var parsed = $.parseHTML(data);
                                          var result = "";
                                          $.each(parsed, function (idx, el) {
                                              if (
                                                  el.classList &&
                                                  el.classList.contains(
                                                      "user-name"
                                                  )
                                              ) {
                                                  result +=
                                                      el.lastChild?.firstChild
                                                          ?.textContent ?? "";
                                              } else {
                                                  result +=
                                                      el.innerText ||
                                                      el.textContent ||
                                                      "";
                                              }
                                          });

                                          return result.trim();
                                      },
                                  },
                              },
                          },

                          {
                              extend: "copy",
                              text: '<i class="bx bx-copy me-1"></i>Copy',
                              className: "dropdown-item",
                              exportOptions: {
                                  columns: printCols,
                                  format: {
                                      body: function (data) {
                                          var parsed = $.parseHTML(data);
                                          var result = "";
                                          $.each(parsed, function (idx, el) {
                                              if (
                                                  el.classList &&
                                                  el.classList.contains(
                                                      "user-name"
                                                  )
                                              ) {
                                                  result +=
                                                      el.lastChild.firstChild
                                                          .textContent;
                                              } else {
                                                  result +=
                                                      el.innerText ||
                                                      el.textContent ||
                                                      "";
                                              }
                                          });
                                          return result;
                                      },
                                  },
                              },
                          },
                      ],
                  },
              ]
            : [],
        columnDefs: [
            { targets: columnDefs ?? [], visible: false }, // Hide the first column
        ],
    });
    if (filters) {
        // Add the button HTML to the DataTable
        $(".dataTables_wrapper .head-label").append(filters);
    }
    // $('#example').DataTable().button('.buttons-print').action(function (e, dt, button, config) {
    //     dt.page.len(-1).draw(); // Show all rows before printing
    //     $.fn.DataTable.ext.buttons.print.action.call(this, e, dt, button, config);
    //     dt.page.len(10).draw(); // Reset pagination after printing
    // });

    if (addUrl) {
        // Add the button HTML to the DataTable
        $(".dt-buttons.btn-group").append(`
            <div class="create-new btn btn-primary rounded">
                <a href="${addUrl}" class="text-white">
                    <i class="bx bx-plus me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">${addBtnName}</span>
                </a>
            </div>
        `);
    }
    return table;
}

function dataTableWithFilter(
    data,
    columns,
    printCols,
    addUrl = null,
    addBtnName = null,
    filters = null
) {
    const table = $(".datatables-basic").DataTable({
        data: data,
        // ajax: url,
        columns: columns,
        dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end pt-3 pt-md-0"B>><"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        displayLength: 10,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        buttons: [
            {
                extend: "collection",
                className: "btn btn-label-primary dropdown-toggle me-2",
                text: '<i class="bx bx-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                buttons: [
                    {
                        extend: "print",
                        text: '<i class="bx bx-printer me-1"></i>Print',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: printCols,
                            format: {
                                body: function (data) {
                                    var parsed = $.parseHTML(data);
                                    var result = "";
                                    $.each(parsed, function (idx, el) {
                                        if (
                                            el.classList &&
                                            el.classList.contains("user-name")
                                        ) {
                                            result +=
                                                el.lastChild.firstChild
                                                    .textContent;
                                        } else {
                                            result +=
                                                el.innerText ||
                                                el.textContent ||
                                                "";
                                        }
                                    });
                                    return result;
                                },
                            },
                        },
                        customize: function (win) {
                            $(win.document.body)
                                .css("color", config.colors.headingColor)
                                .css("border-color", config.colors.borderColor)
                                .css("direction", "rtl")
                                .css("text-align", "right")
                                .css("background-color", config.colors.bodyBg);
                            $(win.document.body)
                                .find("table")
                                .addClass("compact")
                                .css("color", "inherit")
                                .css("border-color", "inherit")
                                .css("background-color", "inherit");
                        },
                    },
                    {
                        extend: "csv",
                        text: '<i class="bx bx-file me-1"></i>Csv',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: printCols,
                            format: {
                                body: function (data) {
                                    var parsed = $.parseHTML(data);
                                    var result = "";
                                    $.each(parsed, function (idx, el) {
                                        if (
                                            el.classList &&
                                            el.classList.contains("user-name")
                                        ) {
                                            result +=
                                                el.lastChild.firstChild
                                                    .textContent;
                                        } else {
                                            result +=
                                                el.innerText ||
                                                el.textContent ||
                                                "";
                                        }
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                    {
                        extend: "excel",
                        text: '<i class="bx bxs-file-export me-1"></i>Excel',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: printCols,
                            format: {
                                body: function (data) {
                                    var parsed = $.parseHTML(data);
                                    var result = "";
                                    $.each(parsed, function (idx, el) {
                                        if (
                                            el.classList &&
                                            el.classList.contains("user-name")
                                        ) {
                                            result +=
                                                el.lastChild.firstChild
                                                    .textContent;
                                        } else {
                                            result +=
                                                el.innerText ||
                                                el.textContent ||
                                                "";
                                        }
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                    {
                        extend: "copy",
                        text: '<i class="bx bx-copy me-1"></i>Copy',
                        className: "dropdown-item",
                        exportOptions: {
                            columns: printCols,
                            format: {
                                body: function (data) {
                                    var parsed = $.parseHTML(data);
                                    var result = "";
                                    $.each(parsed, function (idx, el) {
                                        if (
                                            el.classList &&
                                            el.classList.contains("user-name")
                                        ) {
                                            result +=
                                                el.lastChild.firstChild
                                                    .textContent;
                                        } else {
                                            result +=
                                                el.innerText ||
                                                el.textContent ||
                                                "";
                                        }
                                    });
                                    return result;
                                },
                            },
                        },
                    },
                ],
            },
        ],
    });
    if (filters) {
        // Add the button HTML to the DataTable
        $(".dataTables_wrapper .head-label").append(filters);
    }

    if (addUrl) {
        // Add the button HTML to the DataTable
        $(".dt-buttons.btn-group").append(`
            <div class="create-new btn btn-primary rounded">
                <a href="${addUrl}" class="text-white">
                    <i class="bx bx-plus me-sm-1"></i>
                    <span class="d-none d-sm-inline-block">${addBtnName}</span>
                </a>
            </div>
        `);
    }
    return table;
}
