'use strict';
$(function () {
  var data_table = $('#datatable');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  //var columnDefs = [];
  var columnDefs = [{
    // Serial number column definition
    targets: 0, 
    title: '#', // Label for the serial number column
    render: function (data, type, full, meta) {
      // Return the row number (index + 1)
      return meta.row + meta.settings._iDisplayStart + 1;
    },
    orderable: false, // Make the serial number column not sortable
  }];
  Object.entries(tableHeaders).forEach(function([key, value], index) {
    var columnDef = {
      targets: index,
      sortable: true,
      render: function(data, type, full, meta) {
        return `<span>${full[key]}</span>`;
      }
    };
    columnDefs.push(columnDef);
  });

  if (data_table.length) {
    var dt_user = data_table.DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: ajaxUrl
      },
     columns: [ {data: ''}, Object.entries(tableHeaders).map(([key, value]) => {
        return { data: key };
      })],

      columnDefs: columnDefs,

      order: [[5, 'desc']],
      dom:
        '<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 20, 50, 70, 100], //for length of menu
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },
      // Buttons with Dropdown
      buttons: [
        {
          extend: 'collection',
          className: 'btn btn-outline-secondary dropdown-toggle me-4 waves-effect waves-light',
          text: '<i class="ri-upload-2-line ri-16px me-2"></i><span class="d-none d-sm-inline-block">Export </span>',
          buttons: [
            {
              extend: 'print',
              title: title,
              text: '<i class="ri-printer-line me-1" ></i>Print',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                format: {
                    header: function (inner, columnIndex) {
                        const headers = {
                            0: "#",
                            1: "Sender ID",
                            2: "Mobile",
                            3: "Message",
                            4: "Write Time",
                            5: "Sent Time",
                            6: "Last Update",
                            7: "SMS Count",
                            8: "Rate (BDT)",
                            9: "Charge (BDT)",
                            10: "Status",
                            11: "API/WEB",
                            12: "Campaign",
                            13: "Error Code",
                            14: "Error Message"
                        };
                        return headers[columnIndex] || inner; // Return the appropriate header for each column
                    },
                    body: function (inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                            } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                            } else result = result + item.innerText;
                        });
                        return result;
                    }
                }
            },
            customize: function (win) {
                //customize print view for dark
                $(win.document.body)
                  .css('color', config.colors.headingColor)
                  .css('border-color', config.colors.borderColor)
                  .css('background-color', config.colors.body);
                $(win.document.body)
                  .find('table')
                  .addClass('compact')
                  .css('color', 'inherit')
                  .css('border-color', 'inherit')
                  .css('background-color', 'inherit');
              }
            },
            {
              extend: 'csv',
              title: title,
              text: '<i class="ri-file-text-line me-1" ></i>Csv',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                format: {
                    header: function (inner, columnIndex) {
                        const headers = {
                            0: "#",
                            1: "Sender ID",
                            2: "Mobile",
                            3: "Message",
                            4: "Write Time",
                            5: "Sent Time",
                            6: "Last Update",
                            7: "SMS Count",
                            8: "Rate (BDT)",
                            9: "Charge (BDT)",
                            10: "Status",
                            11: "API/WEB",
                            12: "Campaign",
                            13: "Error Code",
                            14: "Error Message"
                        };
                        return headers[columnIndex] || inner; // Return the appropriate header for each column
                    },
                    body: function (inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                            } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                            } else result = result + item.innerText;
                        });
                        return result;
                    }
                }
              }
            },
            {
              extend: 'excel',
              title: title,
              text: '<i class="ri-file-excel-line me-1"></i>Excel',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                format: {
                    header: function (inner, columnIndex) {
                        const headers = {
                            0: "#",
                            1: "Sender ID",
                            2: "Mobile",
                            3: "Message",
                            4: "Write Time",
                            5: "Sent Time",
                            6: "Last Update",
                            7: "SMS Count",
                            8: "Rate (BDT)",
                            9: "Charge (BDT)",
                            10: "Status",
                            11: "API/WEB",
                            12: "Campaign",
                            13: "Error Code",
                            14: "Error Message"
                        };
                        return headers[columnIndex] || inner; // Return the appropriate header for each column
                    },
                    body: function (inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                            } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                            } else result = result + item.innerText;
                        });
                        return result;
                    }
                }
              }            
            },
            {
              extend: 'pdf',
              title: title,
              text: '<i class="ri-file-pdf-line me-1"></i>Pdf',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                format: {
                    header: function (inner, columnIndex) {
                        const headers = {
                            0: "#",
                            1: "Sender ID",
                            2: "Mobile",
                            3: "Message",
                            4: "Write Time",
                            5: "Sent Time",
                            6: "Last Update",
                            7: "SMS Count",
                            8: "Rate (BDT)",
                            9: "Charge (BDT)",
                            10: "Status",
                            11: "API/WEB",
                            12: "Campaign",
                            13: "Error Code",
                            14: "Error Message"
                        };
                        return headers[columnIndex] || inner; // Return the appropriate header for each column
                    },
                    body: function (inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                            } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                            } else result = result + item.innerText;
                        });
                        return result;
                    }
                }
              }
            },
            {
              extend: 'copy',
              title: title,
              text: '<i class="ri-file-copy-line me-1"></i>Copy',
              className: 'dropdown-item',
              exportOptions: {
                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                format: {
                    header: function (inner, columnIndex) {
                        const headers = {
                            0: "#",
                            1: "Sender ID",
                            2: "Mobile",
                            3: "Message",
                            4: "Write Time",
                            5: "Sent Time",
                            6: "Last Update",
                            7: "SMS Count",
                            8: "Rate (BDT)",
                            9: "Charge (BDT)",
                            10: "Status",
                            11: "API/WEB",
                            12: "Campaign",
                            13: "Error Code",
                            14: "Error Message"
                        };
                        return headers[columnIndex] || inner; // Return the appropriate header for each column
                    },
                    body: function (inner, coldex, rowdex) {
                        if (inner.length <= 0) return inner;
                        var el = $.parseHTML(inner);
                        var result = '';
                        $.each(el, function (index, item) {
                            if (item.classList !== undefined && item.classList.contains('user-name')) {
                                result = result + item.lastChild.firstChild.textContent;
                            } else if (item.innerText === undefined) {
                                result = result + item.textContent;
                            } else result = result + item.innerText;
                        });
                        return result;
                    }
                }
              }            
            }
          ]
        }
      ],
      // For responsive popup
      /*responsive: {
        details: {
          display: $.fn.dataTable.Responsive.display.modal({
            header: function (row) {
              var data = row.data();
              return 'Details of ' + data['name'];
            }
          }),
          type: 'column',
          renderer: function (api, rowIdx, columns) {
            var data = $.map(columns, function (col, i) {
              return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                ? '<tr data-dt-row="' +
                    col.rowIndex +
                    '" data-dt-column="' +
                    col.columnIndex +
                    '">' +
                    '<td>' +
                    col.title +
                    ':' +
                    '</td> ' +
                    '<td>' +
                    col.data +
                    '</td>' +
                    '</tr>'
                : '';
            }).join('');

            return data ? $('<table class="table"/><tbody />').append(data) : false;
          }
        }
      }*/
    });
  }
});
