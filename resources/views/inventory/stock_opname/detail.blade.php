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
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div>
                <label class="form-label">No. Ref.</label>
                <label class="form-label" id="input-number">Loading...</label>
              </div>
              <div>
                <label class="form-label">Tanggal</label>
                <label class="form-label" id="input-date">Loading...</label>
              </div>
            </div>
            <div class="col-md-6">
              <div>
                <label class="form-label">Gudang</label>
                <label class="form-label" id="input-warehouse">Loading...</label>
              </div>
            </div>
            <div class="col-md-12">
              <div>
                <label class="form-label">Catatan</label>
                <label class="form-label" id="input-note">Loading...</label>
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
    </div>
  </div>
</div>
@endsection

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">
  let warehouseId = 0;

  getStockOpname({{$id}});

  $(document).on('change', '#input-warehouse', function() {
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

  function getStockOpname(id) {
    $.ajax({
      url: BASE_URL+"/api/stock_opnames/"+id,
      type: 'GET',
      "headers": {
        'Authorization': TOKEN
      },
      dataType: 'JSON',
      success: function(res, textStatus, jqXHR){
        let content = '';
        $('#input-date').text(res.date);
        $('#input-number').text(res.number);
        $('#input-warehouse').text(res.warehouse.name);
        $('#input-note').text(res.description);
        $.each(res.items, function(key, value) {
          content += '<tr>';
          content += '<td>'+(key+1)+'</td>';
          content += '<td>'+value.inventory.code+'</td>';
          content += '<td>'+value.inventory.name+'</td>';
          content += '<td>'+value.rack.name+'</td>';
          content += '<td>'+(value.column ? value.column.name : '-')+'</td>';
          content += '<td>'+parseFloat(value.stock_on_book)+'</td>';
          content += '<td>'+parseFloat(value.stock_on_physic)+'</td>';
          content += '<td>'+diff(value.stock_on_book, value.stock_on_physic)+'</td>';
          content += '<td>'+(value.note == null ? '-' : value.note)+'</td>';
          content += '</tr>';
        });

        $('#item-table').html(content);
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
  </script>
@endsection