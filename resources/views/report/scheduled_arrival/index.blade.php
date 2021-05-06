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
        <button type="button" class="btn btn-warning d-none d-sm-inline-block" id="show-main-modal">
          {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg> --}}
          Filter
        </button>
        @if ($user->can('report_scheduled_arrivals.pdf'))
        <form action="{{ url('reports/scheduled_arrivals/pdf') }}" target="_blank"
        style="margin-left: 10px;" method="POST">
          @csrf
          <input type="hidden" id="pdf-customer-id" name="customer_id">
          <input type="hidden" id="pdf-dispatch-date" name="dispatch_date">
          <input type="hidden" id="pdf-eta" name="eta">
          <button type="submit" class="btn btn-primary d-none d-sm-inline-block">
            PDF
          </button>
        </form>
        
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
              <th>Invoice Number</th>
              <th>Product Code</th>
              <th>Product Description</th>
              <th>Qty</th>
              <th>Customer Order Number</th>
              <th>Dispatch Date</th>
              <th>ETA</th>
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
    @include('report.scheduled_arrival.modal')
@endsection
@section('script')
  <script type="text/javascript">

    drawDatatable();

    $('.single-select').select2({
        width: '100%'
    });

    flatpickr($(".daterangepicker"), {
        mode: "range",
        dateFormat: "Y-m-d",
        position : 'above',
        disable: [ function(date) { } ]
    });

    $(document).on("click","button#show-main-modal",function() {
        $('#modal-title').text('Filter {{$title}}');
        $('#input-id').val('');
        $('#main-modal').modal('show');
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
            "url": BASE_URL+"/api/report_scheduled_arrival_datatables",
            "headers": { 'Authorization': TOKEN },
            "dataType": "json",
            "type": "POST",
            "data" : function(d) { 
              d.customer_id = $('#input-customer-id').val()
              d.dispatch_date = $('#input-dispatch-date').val()
              d.eta = $('#input-eta').val()
            },
        },
        "columns": [
            {data: 'id', name: 'id', width: '5%', "visible": false},
            {data: 'invoice_number', name: 'invoice_number'},
            {data: 'inventory_code', name: 'inventory_code'},
            {data: 'inventory_description', name: 'inventory_description'},
            {data: 'qty', name: 'qty'},
            {data: 'customer_order_number', name: 'customer_order_number'},
            {data: 'dispatch_date', name: 'dispatch_date'},
            {data: 'estimated_time_of_arrival', name: 'estimated_time_of_arrival'},
        ],
        "order": [0, 'desc']
      });
    }

    $(document).on('submit', 'form#main-form', function (e) { 
        e.preventDefault()
        $('#main-modal').modal('hide')
        $("#main-table").DataTable().ajax.reload(null, false)
        $('#pdf-customer-id').val($('#input-customer-id').val())
        $('#pdf-dispatch-date').val($('#input-dispatch-date').val())
        $('#pdf-eta').val($('#input-eta').val())
    })
  </script>
@endsection