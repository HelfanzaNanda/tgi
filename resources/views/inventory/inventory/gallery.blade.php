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
      <form id="image-form" method="POST" action="#">
        {!! csrf_field() !!}
        <div class="card-body">
          <div class="row">
            <div>
              <label class="form-label">Gambar</label>
              <input class="form-control" type="file" name="files[]" id="input-file" onchange="preview_image();" multiple required="">
              <input type="hidden" name="model" value="App\Models\Inventories">
              <input type="hidden" name="model_id" value="{{$id}}">
              <input type="hidden" name="type" value="galleries">
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              <div id="image_preview"></div>
            </div>
          </div>
          <center>
            <div style="margin-top: 10px;">
              <button type="submit" class="btn btn-primary d-none d-sm-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Upload
              </button>
            </div>
          </center>
        </div>
      </form>
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
          @foreach ($media as $gallery)
              <div class="col-md-3 mb-3">
                  <img class="image" src="{{ asset($gallery->filepath.'/'.$gallery->filename) }}" >
                  <a href="#" data-id="{{ $gallery->id }}" class="btn btn-delete btn-sm btn-danger btn-block rounded-0 shadow shadow-sm">Hapus</a>
              </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- {
    "code": "INV002",
    "name": "Kain Merah",
    "category_id": 1,
    "buy_price": 12000,
    "supplier_id": 1,
    "unit_id": 1,
    "location": {
        "warehouse_id": 6,
        "rack_id": 2,
        "stock": 100
    }   
} --}}

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">
  function preview_image() {
   var total_file=document.getElementById("input-file").files.length;
   for(var i=0;i<total_file;i++) {
    $('#image_preview').append("<img class='image-row' src='"+URL.createObjectURL(event.target.files[i])+"'>");
   }
  }

  $( 'form#image-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/media",
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
              location.reload();
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

  $(document).on('click', '.btn-delete', function( e ) {
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
        url: BASE_URL + '/api/media/' + id,
        "headers": {
          'Authorization': TOKEN
        },
        type: "DELETE"
      })
      .done( function( data ) {
        swal( "Dihapus!", "Data telah berhasil dihapus!", "success" );
        location.reload();
      } )
      .error( function( data ) {
        swal( "Oops", "We couldn't connect to the server!", "error" );
      } );
    } );
  });
  </script>
@endsection