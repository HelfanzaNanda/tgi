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
        @if ($user->can('customers.add'))
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
              <th>Nama</th>
              <th>Country</th>
              <th>Email</th>
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
    @include('master.customer.modal')
@endsection

@section('script')
  <script type="text/javascript">
    drawDatatable();

    getCountry();

    $('#input-country').select2({
      width: '100%',
      dropdownParent: $("#main-modal")
    });

    function getCountry() {
      $.ajax({
        url: BASE_URL+"/api/countries?all=true",
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          $("#input-country").empty();

          let html = '';

          html += '<option value=""> - Select Country - </option>';

          $.each(data, function(key, value) {
            html += '<option value="'+value.country_name+'">'+value.country_name+'</option>';
          });

          $("#input-country").append(html);
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

    $(document).on("click","button#show-main-modal",function() {
      $('#modal-title').text('Tambah {{$title}}');
      $('#input-id').val('');
      $('#main-modal').modal('show');
    });

    $(document).on("click", "a#edit-data",function(e) {
      e.preventDefault();
      let id = $(this).data('id');
      $.ajax({
        url: BASE_URL+"/api/customers/"+id,
        type: 'GET',
        "headers": {
          'Authorization': TOKEN
        },
        dataType: 'JSON',
        success: function(data, textStatus, jqXHR){
          $('#input-id').val(data.id);
          $('#input-name').val(data.name);
          $('#input-country').val(data.country).trigger('change');
          $('#input-email').val(data.email);
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
            "url": BASE_URL+"/api/customer_datatables",
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
            {data: 'name', name: 'name'},
            {data: 'country', name: 'country'},
            {data: 'email', name: 'email'},
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
      url: BASE_URL+"/api/customers",
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
        showAlertOnSubmit(msg, '#main-modal', '#main-table');
      }
    })
  });

  $(document).on('click', 'a#delete-data', function( e ) {
    e.preventDefault();
    let id = $(this).data('id');
    showDeletePopup(BASE_URL + '/api/customers/' + id, TOKEN, '', '#main-table', '');
  });
  </script>
@endsection