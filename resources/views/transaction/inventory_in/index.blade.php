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
        <a href="{{url('/incoming_inventories/create')}}" class="btn btn-primary d-none d-sm-inline-block">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Tambah {{$title}}
        </a>
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
  @include('transaction.inventory_in.modal')
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
    drawDatatable();
    
    $(document).ready(function() {
      $('.summernote').summernote();
    });

    function initializeSelect2(){
      $('.single-select').select2({
        width: '100%'
      });
    }

    flatpickr($(".datepicker"), {
        //enableTime: true,
        dateFormat: "Y-m-d",
        //time_24hr: true,
        onChange: function (selectedDates, dateStr, instance) {
          instance.close();
        }
    });

    $(document).on('click', '.create-ba', function (e) { 
        e.preventDefault()
        const id = $(this).data('id')
        resetForm()
        getSuppliers()
        $('#input-model-id').val(id)
        $('#modal-title').text('Create BA')
        $('#ba-modal').modal('show')
    })

    $(document).on('click', '.edit-ba', function (e) { 
        e.preventDefault()
        resetForm()
        getSuppliers()
        const id = $(this).data('id')
        const model_type = "App\\Models\\IncomingInventories"
        const data = {
          model_id:id,
          model: model_type
        };
        $.ajax({
            url: BASE_URL+'/api/record_of_transfers/get',
            type: 'POST',
            data : data,
            "headers": {
              'Authorization': TOKEN
            },
            dataType: 'JSON',
            beforeSend : function () { 
              
            },
            success: function(data, textStatus, jqXHR){
              console.log(data);
                $('#input-id').val(data.id)
                $('#input-model-id').val(data.model_id)
                $('#input-date').val(new Date(data.date).toISOString().split('T')[0])
                $('#input-received-by').val(data.received_by).trigger('change')
                $('#input-description').summernote('editor.pasteHTML',data.description)
                $('#modal-title').text('Edit BA')
                $('#ba-modal').modal('show')
            },
            error: function(jqXHR, textStatus, errorThrown){

            },
        })
    })

    $(document).on('submit', '#ba-form', function (e) { 
        e.preventDefault()
        const form_data = new FormData(this)
        $.ajax({
            type: 'post',
            url: BASE_URL+"/api/record_of_transfers",
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
              showAlertOnSubmit(msg, '#ba-modal', '#main-table');
            }
      })
        
    })

    function resetForm(){
        $('#input-id').val('')
        $('#input-model-id').val('')
        $('#input-date').val(null)
        $('#input-received-by').val('')
        $('#input-description').summernote('reset')
    }

    function getSuppliers() {
        $.ajax({
            url: BASE_URL+"/api/suppliers?all=true",
            type: 'GET',
            "headers": {
              'Authorization': TOKEN
            },
            dataType: 'JSON',
            success: function(data, textStatus, jqXHR){
                let content = '';
                content += '<option value="" selected disabled>-- Choose Supplier --</option>';
                $.each(data, function(key, value) {
                  content += '<option value="'+value.id+'">'+value.name+'</option>';
                });
                initializeSelect2()
                $('#input-received-by').html(content);

            },
            error: function(jqXHR, textStatus, errorThrown){

            },
        })
    }



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
            // success : function (res) {  
            //     console.log(res);
            // }
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