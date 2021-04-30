@extends('layouts.main')

@section('title', $title)

@section('content')
@php
    $user = App\Models\User::find(Session::get('_id'));
@endphp
<style type="text/css">
  .text-muted {
    font-size: 10px;
  }
</style>
<!-- Page title -->
<div class="page-header text-white d-print-none">
  <div class="row align-items-center">
    <div class="col">
      <!-- Page pre-title -->
{{--       <div class="page-pretitle">
        <center>Overview</center>
      </div>
      <h2 class="page-title">
        <center>ALL TIME STATS</center>
      </h2> --}}
    </div>
  </div>
</div>
<div class="row row-deck row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="col">
          <h3 class="card-title" id="status-title-table">Data {{$title}}</h3>
        </div>
        @if ($user->can('incoming_inventories.add'))
        <button type="button" class="btn btn-primary d-none d-sm-inline-block" id="show-main-modal">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Tambah {{$title}}
        </button>
        @endif
        <button type="button" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </button>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="main-table" style="padding-top: 15px;">
          <thead>
            <tr>
              <th class="w-1">No.</th>
              <th>Kode</th>
              <th>Nomor</th>
              <th>Diterima Oleh</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('modal')
  <div class="modal modal-blur fade" id="main-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id='modal-title'></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="main-form" method="POST" action="">
          <input type="hidden" name="id" id="input-id">
          <div class="modal-body">
            <div>
              <label class="form-label">Nomor Invoice</label>
              <input type="text" class="form-control" name="code" id="input-code" />
            </div>

            <div>
              <label class="form-label">Catatan</label>
              <textarea class="form-control" id="input-note" name="note"></textarea>
            </div>
          </div>
          <div class="modal-body">
            <div class="table-responsive">
              <table class="table table-vcenter card-table table-striped">
                <tbody id="add-row-table">

                </tbody>
              </table>
            </div>
            <div>
              <center><button type="button" class="btn btn-success" id="add-row-button">Tambah Barang</button></center>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script type="text/javascript">
    let allInventories = [];
    let allWarehouses = [];

    drawDatatable();
    getInventories();
    getWarehouses();

    function getInventories() {
      $.ajax({
        url: BASE_URL+"/api/inventories?all=true",
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          allInventories = data;
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

    function getWarehouses() {
      $.ajax({
        url: BASE_URL+"/api/warehouses?all=true",
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          allWarehouses = data;
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

    function getRacks(rowNumber, warehouseId) {
      $.ajax({
        url: BASE_URL+"/api/racks?all=true&warehouse_id="+warehouseId,
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          $("#input-rack-"+rowNumber).empty();

          let html = '';

          $.each(data, function(key, value) {
            html += '<option value="'+value.id+'">'+value.name+'</option>';
          });

          $("#input-rack-"+rowNumber).append(html);
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

    let addRowCount = 0;
    $('#add-row-button').on("click", function() {
        let html = '';

        if (addRowCount < 1) {
          html += '<tr>';
          html += '  <td width="25%">Barang</td>';
          html += '  <td width="15%">Jumlah</td>';
          html += '  <td width="25%">Gudang</td>';
          html += '  <td width="25%">Rak</td>';
          html += '  <td width="10%"></td>';
          html += '</tr>';
        }

        html += '<tr id="add-row-'+addRowCount+'">';
        html += '<td>';
        html += '<select class="form-control" id="input-inventory-'+addRowCount+'" name="inventory_id[]">';
          html += '<option value=""> - Pilih Barang - </option>';
          $.each(allInventories, function(key, value) {
            html += '<option value="'+value.id+'">'+value.name+'</option>';
          });
        html += '</select>';
        html += '</td>';
        html += '  <td><input type="number" class="form-control" id="input-qty" name="qty[]"></input></td>';
        html += '<td>';
        html += '<select class="form-control" id="input-warehouse-'+addRowCount+'" name="warehouse_id[]" data-row-id="'+addRowCount+'">';
          html += '<option value=""> - Pilih Tujuan Gudang - </option>';
          $.each(allWarehouses, function(key, value) {
            html += '<option value="'+value.id+'">'+value.name+'</option>';
          });
        html += '</select>';
        html += '</td>';
        html += '<td>';
        html += '<select class="form-control" id="input-rack-'+addRowCount+'" name="rack_id[]">';

        html += '</select>';
        html += '</td>';
        html += '  <td><button type="button" class="btn btn-danger" id="remove-row-button" data-id="'+addRowCount+'">X</button></td>';
        html += '</tr>';

        $("#add-row-table").append(html);

        $('#input-inventory-'+addRowCount).select2({
          width: '100%'
        });

        $('#input-warehouse-'+addRowCount).select2({
          width: '100%'
        });

        $('#input-rack-'+addRowCount).select2({
          width: '100%'
        });

        addRowCount++;
    });

    $(document).on('change', 'select[id^="input-warehouse-"]', function() {
      let rowId = $(this).data('row-id');
      let val = $(this).find(':selected').val();

      getRacks(rowId, val);
    });


    $(document).on("click", "#remove-row-button", function(){
        let id = $(this).data('id');

        $("tr#add-row-"+id).empty();
    });

    $(document).on("click","button#show-main-modal",function() {
      addRowCount = 0;
      $('#add-row-table').empty();
      $('#modal-title').text('Tambah {{$title}}');
      $('#input-id').val('');
      $('#main-modal').modal('show');
    });

    $(document).on("click", "a#edit-data",function(e) {
      e.preventDefault();
      let id = $(this).data('id');
      $.ajax({
        url: BASE_URL+"/api/incoming_inventories/"+id,
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        beforeSend: function() {
          addRowCount = 0;
          $('#add-row-table').empty();
        },
        success: function(data, textStatus, jqXHR){
          $('#input-id').val(data.id);
          $('#input-note').val(data.note);
          $('#input-code').val(data.receipt_number);

          $.each(data.transaction.transaction_details, function(key, value) {
            let html = '';

            if (key < 1) {
              html += '<tr>';
              html += '  <td width="25%">Barang</td>';
              html += '  <td width="15%">Jumlah</td>';
              html += '  <td width="25%">Gudang</td>';
              html += '  <td width="25%">Rak</td>';
              html += '  <td width="10%"></td>';
              html += '</tr>';
            }

            html += '<tr id="add-row-'+key+'">';
            html += '<td>';
            html += '<select class="form-control" id="input-inventory-'+key+'" name="inventory_id[]">';
              html += '<option value=""> - Pilih Barang - </option>';
              $.each(allInventories, function(keyInv, valueInv) {
                html += '<option value="'+valueInv.id+'">'+valueInv.name+'</option>';
              });
            html += '</select>';
            html += '</td>';
            html += '  <td><input type="number" class="form-control" id="input-qty" name="qty[]" value="'+value.qty+'"></input></td>';
            html += '<td>';
            html += '<select class="form-control" id="input-warehouse-'+key+'" name="warehouse_id[]" data-row-id="'+key+'">';
              html += '<option value=""> - Pilih Tujuan Gudang - </option>';
              $.each(allWarehouses, function(keyWh, valueWh) {
                html += '<option value="'+valueWh.id+'">'+valueWh.name+'</option>';
              });
            html += '</select>';
            html += '</td>';
            html += '<td>';
            html += '<select class="form-control" id="input-rack-'+key+'" name="rack_id[]">';

            html += '</select>';
            html += '</td>';
            html += '  <td><button type="button" class="btn btn-danger" id="remove-row-button" data-id="'+key+'">X</button></td>';
            html += '</tr>';

            $("#add-row-table").append(html);

            $('#input-inventory-'+key).select2({
              width: '100%'
            });

            $('#input-warehouse-'+key).select2({
              width: '100%'
            });

            $('#input-rack-'+key).select2({
              width: '100%'
            });

            $('#input-inventory-'+key).val(value.inventory_id).trigger('change');
            $('#input-warehouse-'+key).val(value.warehouse_id).trigger('change');

            setTimeout(function(){ 
              $('#input-rack-'+key).val(value.rack_id).trigger("change"); 
            }, 1000); 

            addRowCount++;

          });

          $('#modal-title').text('Edit {{$title}}');
          $('#main-modal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    });

    function drawDatatable() {
      $("#main-table").DataTable({
        destroy: true,
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        // "searching": false,
        // "ordering": false,
        "ajax":{
            "url": BASE_URL+"/api/incoming_inventory_datatables",
            "headers": {
              'Authorization': TOKEN
            },
            "dataType": "json",
            "type": "POST",
            "data":function(d) { 
              // d.status = status
            },
        },
        "columns": [
            {data: 'id', name: 'id', width: '5%', "visible": false},
            {data: 'code', name: 'code'},
            {data: 'receipt_number', name: 'receipt_number'},
            {data: 'received_by_name', name: 'received_by_name'},
            {data: 'date', name: 'date'},
            {data: 'action', name: 'action', orderable: false, className: 'text-end'}
        ],
        "order": [0, 'desc']
      });
    }

  $( 'form#main-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/store_incoming_inventories",
      "headers": {
        'Authorization': TOKEN
      },
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function() {
        $('.loading-area').show();
      },
      success: function(msg) {
        if(msg.status == 'success'){
          setTimeout(function() {
            swal({
              title: "Sukses",
              text: msg.message,
              type:"success",
              html: true
            }, function() {
              $('#main-modal').modal('hide');
              $("#main-table").DataTable().ajax.reload( null, false );
            });
          }, 200);
        } else {
          $('.loading-area').hide();
          swal({
            title: "Gagal",
            text: msg.message,
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
          });
        }
      }
    })
  });

  $(document).on('click', 'a#delete-data', function( e ) {
    e.preventDefault();
    let id = $(this).data('id');
    swal( {
      title:                "Apakah anda yakin?",
      text:                 "Apakah anda yakin menghapus data ini?",
      type:                 "warning",
      showCancelButton:     true,
      closeOnConfirm:       false,
      showLoaderOnConfirm:  true,
      confirmButtonText:    "Ya!",
      cancelButtonText:     'Tidak',
      confirmButtonColor:   "#ec6c62"
    }, function() {
      $.ajax({
        url: BASE_URL + '/api/incoming_inventories/' + id,
        "headers": {
          'Authorization': TOKEN
        },
        type: "DELETE"
      })
      .done( function( data ) {
        swal( "Dihapus!", "Data telah berhasil dihapus!", "success" );
        $("#main-table").DataTable().ajax.reload( null, false );
      } )
      .error( function( data ) {
        swal( "Oops", "We couldn't connect to the server!", "error" );
      } );
    } );
  });
  </script>
@endsection