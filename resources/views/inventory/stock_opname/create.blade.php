@extends('layouts.main')

@section('title', $title)

@section('content')
<style type="text/css">
  .text-muted {
    font-size: 10px;
  }

  .image-row {
    margin: 10px;
    width:200px;
    height:150px;
    object-position: center;
    object-fit: cover;
  }

  .image {
    width:100%;
    height:250px;
    object-position: center;
    object-fit: fill;
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
      </div>
      <form id="main-form" method="POST" action="#">
        {!! csrf_field() !!}
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div>
                <label class="form-label">Tanggal</label>
                <input type="text" name="date" class="form-control" id="input-date" value="{{date('Y-m-d H:i:s')}}">
              </div>
            </div>
            <div class="col-md-6">
              <div>
                <label class="form-label">Gudang</label>
                <select class="form-control" id="input-warehouse" name="warehouse_id">
                  
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div>
                <label class="form-label">Catatan</label>
                <textarea class="form-control" name="note" rows="3"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <th width="5%">#</th>
              <th width="10%">Code</th>
              <th width="20%">Name</th>
              <th width="10%">Rak</th>
              <th width="10%">Kolom</th>
              <th width="10%">Stock Buku</th>
              <th width="10%">Stock Fisik</th>
              <th width="5%">Selisih</th>
              <th width="20%">Note</th>
            </thead>
            <tbody id="item-table">
              
            </tbody>
          </table>
        </div>
        <center>
          <div style="margin-top: 10px; margin-bottom: 10px;">
            <button type="submit" class="btn btn-primary d-none d-sm-inline-block">
              Submit
            </button>
          </div>
        </center>
      </form>
    </div>
  </div>
</div>
@endsection

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">
  let warehouseId = 0;

  getWarehouses();
  getInventoryLocations(warehouseId);

  $('#input-warehouse').select2({
    width: '100%'
  });

  $(document).on('change', '#input-warehouse', function() {
    swal({
      title: 'Now loading',
      allowEscapeKey: false,
      allowOutsideClick: false,
      timer: 2000,
      onOpen: () => {
        swal.showLoading();
      }
    });
    let val = $(this).find(':selected').val();

    warehouseId = val;
    getInventoryLocations(warehouseId);
  });

  flatpickr($("#input-date"), {
    enableTime: true,
    dateFormat: "Y-m-d H:i:ss",
    time_24hr: true,
    onChange: function (selectedDates, dateStr, instance) {
      // console.log(selectedDates);
      // console.log(dateStr);
      // console.log(instance);
      instance.close();
    }
  });

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

  function diff (num1, num2) {
    if (num1 > num2) {
      return parseFloat(num1) - parseFloat(num2)
    } else {
      return parseFloat(num2) - parseFloat(num1)
    }
  }

  function getInventoryLocations(warehouseId) {
    $.ajax({
      url: BASE_URL+"/api/inventory_locations?all=true&warehouse_id="+warehouseId,
      type: 'GET',
      "headers": {
        'Authorization': TOKEN
      },
      dataType: 'JSON',
      success: function(res, textStatus, jqXHR){
        let content = '';
        $.each(res.data, function(key, value) {
          content += '<tr>';
          content += '<td>'+(key+1)+'</td>';
          content += '<td><input type="hidden" class="form-control" name="items[inventory_id][]" value="'+value.inventory_id+'">'+value.inventory.code+'</td>';
          content += '<td>'+value.inventory.name+'</td>';
          content += '<td><input type="hidden" class="form-control" name="items[rack_id][]" value="'+value.rack_id+'">'+value.rack.name+'</td>';
          content += '<td><input type="hidden" class="form-control" name="items[column_id][]" value="'+value.column_id+'">'+(value.column ? value.column.name : '-')+'</td>';
          content += '<td><input type="hidden" class="form-control" name="items[stock_on_book][]" value="'+value.stock+'">'+value.stock+'</td>';
          content += '<td><input type="text" class="form-control" name="items[stock_on_physic][]" id="input-stock-row-'+key+'" data-row-id="'+key+'" data-stock="'+value.stock+'" required></td>';
          content += '<td id="difference-row-'+key+'">'+diff(value.stock, 0)+'</td>';
          content += '<td><textarea class="form-control" name="items[note][]"></textarea></td>';
          content += '</tr>';
        });

        $('#item-table').html(content);

        swal.close();
      },
      error: function(jqXHR, textStatus, errorThrown){

      },
    });
  }

  $(document).on('keyup', 'input[id^="input-stock-row-"]', function() {
    let rowId = $(this).data('row-id');
    let stock = $(this).data('stock');
    let val = $(this).val();
    if (val == '') {
      val = 0;
    }

    let calculateStock = diff(stock, val);
    $('#difference-row-'+rowId).text(calculateStock);
    console.log(rowId);
    console.log(stock);
    console.log(val);
  });

  $( 'form#main-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/stock_opnames",
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
        showAlertOnSubmit(msg, '', '', '{{url('/stock_opnames')}}');
      }
    })
  });

  $(document).on('click', '.btn-delete', function( e ) {
    e.preventDefault();
    let id = $(this).data('id');
    showDeletePopup(BASE_URL + '/api/stock_opnames/' + id, TOKEN, '', '', '{{url('/stock_opnames')}}');
  });
  </script>
@endsection