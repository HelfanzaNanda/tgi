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
<form id="main-form" method="POST" action="">
  <div class="col-12">
    <div class="card" id="tabs">
        <ul class="nav nav-tabs" data-bs-toggle="tabs">
          <li class="nav-item">
            <a href="#tab-inspection-checklist" class="nav-link active" data-bs-toggle="tab">Inspection Checklist</a>
          </li>
          <li class="nav-item">
            <a href="#tab-inspection-photo" class="nav-link" data-bs-toggle="tab">Inspection Photo</a>
          </li>
          <li class="nav-item">
            <a href="#tab-inspection-mutation" class="nav-link" data-bs-toggle="tab">Mutation</a>
          </li>
        </ul>
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="tab-inspection-checklist">

            </div>
            <div class="tab-pane fade" id="tab-inspection-photo">
              <div class="row">
                <div>
                  <label class="form-label">Gambar</label>
                  <input class="form-control" type="file" name="files[]" id="input-file" onchange="previewImage();" multiple required="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <div id="image_preview"></div>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tab-inspection-mutation">
              <div class="table-responsive">
                <table class="table table-vcenter card-table table-striped">
                  <tbody id="add-row-table">

                  </tbody>
                </table>
              </div>
              <div>
                <center><button type="button" class="btn btn-warning" id="add-row-button">Add Product Row</button></center>
              </div>
              <div style="margin-top: 15px;">
                <center><button type="submit" class="btn btn-success">Submit</button></center>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="status" value="completed">
</form>
</div>
@endsection

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">
    let allInventories = [];
    let allWarehouses = [];
    // $("#tabs").tabs( "option", "active", 0 );

    getInventories();
    getWarehouses();
    getQuestions();

    function getQuestions() {
      $.ajax({
        url: BASE_URL+"/api/inspection_questions?all=true&type_question=outbound",
        type: 'GET',
        "headers": { 'Authorization': TOKEN },
        dataType: 'JSON',
        beforeSend: function(){

        },
        success: function(data, textStatus, jqXHR){
          let html = '';

          $.each(data, function(key, value) {
            html += '<div>';
            html += '<label class="form-check">';
            if (value.type_answer == 'checkbox') {
              html += '  <input type="checkbox" class="form-check-input" name="inspection_checklist['+value.id+']" id="input-inspection-'+value.id+'" value="true" />';
            }
            html += '  <span class="form-check-label">'+value.question+'</span>';
            html += '</label>';
            html += '</div>';
          });

          $('#tab-inspection-checklist').append(html);
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    }

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

          html += '<option value=""> - Choose Rack - </option>';
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
          html += '  <td width="25%">Product</td>';
          html += '  <td width="15%">Qty (Pcs)</td>';
          html += '  <td width="15%">Batch Number</td>';
          html += '  <td width="15%">Expired Date</td>';
          html += '  <td width="10%">Warehouse</td>';
          html += '  <td width="10%">Rack</td>';
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
        html += '  <td><input type="text" class="form-control" id="input-batch_number" name="batch_number[]"></input></td>';
        html += '  <td><input type="text" class="form-control" id="input-expired_date-'+addRowCount+'" name="expired_date[]"></input></td>';
        html += '<td>';
        html += '<select class="form-control" id="input-warehouse-'+addRowCount+'" name="warehouse_id[]" data-row-id="'+addRowCount+'">';
          html += '<option value=""> - Choose Warehouse Dest - </option>';
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

        flatpickr($("#input-expired_date-"+addRowCount), {
          dateFormat: "Y-m-d",
          onChange: function (selectedDates, dateStr, instance) {
            // console.log(selectedDates);
            // console.log(dateStr);
            // console.log(instance);
            instance.close();
          }
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

  $( 'form#main-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/store_outcoming_inventories",
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

  function previewImage() {
   var total_file=document.getElementById("input-file").files.length;
   for(var i=0;i<total_file;i++) {
    $('#image_preview').append("<img class='image-row' src='"+URL.createObjectURL(event.target.files[i])+"'>");
   }
  }
  </script>
@endsection