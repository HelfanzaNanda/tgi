@extends('layouts.main')

@section('title', $title)

@section('content')
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
        <button type="button" class="btn btn-primary d-none d-sm-inline-block" id="show-main-modal">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Tambah {{$title}}
        </button>
        <button type="button" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </button>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="main-table" style="padding-top: 15px;">
          <thead>
            <tr>
              <th class="w-1">No.</th>
              <th>Code</th>
              <th>Nama Rak</th>
              <th>Asal Gudang</th>
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
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id='modal-title'></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="main-form" method="POST" action="">
          <input type="hidden" name="id" id="input-id">
          <div class="modal-body">
            <div>
              <label class="form-label">Gudang</label>
              <select class="form-control" id="input-warehouse" name="warehouse_id">
                
              </select>
            </div>

            <div>
              <label class="form-label">Nama</label>
              <input type="text" class="form-control" name="name" id="input-name" />
            </div>

            <div>
              <label class="form-label">Code</label>
              <input type="text" class="form-control" name="code" id="input-code" />
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
    getNumber();
    getWarehouses();

    let number = 0;

    $('#input-warehouse').select2({
      width: '100%'
    });

    $(document).on("keyup", "input#input-name",function(e) {
      let val = $(this).val();
      if ($('#input-id').val() == "") {
        $('#input-code').val(generalInitials(val)+'-'+number);
      }
    });

    $(document).on("click","button#show-main-modal",function() {
      $('#modal-title').text('Tambah {{$title}}');
      $('#input-id').val('');
      $('#main-modal').modal('show');
    });

    $(document).on("click", "a#edit-data",function(e) {
      e.preventDefault();
      let id = $(this).data('id');
      $.ajax({
        url: BASE_URL+"/api/racks/"+id,
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          $('#input-id').val(data.id);
          $('#input-name').val(data.name);
          $('#input-warehouse').val(data.warehouse_id).trigger('change');
          $('#input-code').val(data.code).prop('disabled', true);
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
            "url": BASE_URL+"/api/rack_datatables",
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
            {data: 'name', name: 'name'},
            {data: 'warehouse_name', name: 'warehouse_name'},
            {data: 'action', name: 'action', orderable: false, className: 'text-end'}
        ],
        "order": [0, 'desc']
      });
    }

    function getNumber() {
      $.ajax({
        url: BASE_URL+"/api/rack_numbers",
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          number = data;
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
          let content = '';
          content += '<option value=""> - Pilih Gudang - </option>';
          $.each(data, function(key, value) {
            content += '<option value="'+value.id+'">'+value.name+'</option>';
          });

          $('#input-warehouse').html(content);
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

  $( 'form#main-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/racks",
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
        url: BASE_URL + '/api/racks/' + id,
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