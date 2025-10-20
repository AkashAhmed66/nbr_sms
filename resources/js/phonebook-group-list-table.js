'use strict';
$(function () {
  var data_table = $('#datatable');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  var columnDefs = [{
    targets: 0,
    title: '#',
    render: function (data, type, full, meta) {
      return meta.row + meta.settings._iDisplayStart + 1;
    },
    orderable: false,
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

      order: [],
      dom:
        '<"card-header d-flex rounded-0 flex-wrap pb-md-0 pt-0"' +
        '<"me-5 ms-n2"f>' +
        '<"d-flex justify-content-start justify-content-md-end align-items-baseline"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center gap-4"lB>>' +
        '>t' +
        '<"row mx-1"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      lengthMenu: [10, 20, 50, 70, 100],
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Search',
        info: 'Displaying _START_ to _END_ of _TOTAL_ entries'
      },
      buttons: [
        {
          text: '<i class="ri-form-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Upload CSV/Excel</span>',
          className: 'open-form btn btn-primary waves-effect waves-light me-3',
          attr: {
            'data-bs-toggle': 'offcanvas',
            'data-bs-target': '#offcanvasOpenForm'
          }
        },
        // {
        //   text: '<i class="ri-add-line ri-16px me-0 me-sm-2 align-baseline"></i><span class="d-none d-sm-inline-block">Add New Record</span>',
        //   className: 'add-new btn btn-primary waves-effect waves-light',
        //   attr: {
        //     'data-bs-toggle': 'offcanvas',
        //     'data-bs-target': '#offcanvasAddRecord'
        //   }
        // }
      ],
      responsive: {
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
              return col.title !== ''
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
      }
    });

    // Wrap the "Upload CSV/Excel" button
    var uploadButton = $('.dt-action-buttons button').first();
    var buttonContainer = $('<div class="button-container"></div>'); // Create a container div
    uploadButton.wrap(buttonContainer); // Wrap the button in the container

    // Prevent layout shift and ensure button heights remain unaffected
    $(".dt-action-buttons").css('position', 'relative');
  }
});
