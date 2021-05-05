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
        @if ($user->can('inspections.add'))
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
              <th>Question</th>
              <th>Type Answer</th>
              <th>Type Questions</th>
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
    @include('master.inspection_question.modal')
@endsection

@section('script')
  <script type="text/javascript">
    drawDatatable();
    let index_answer = 0;

    $(document).on("click","button#show-main-modal",function() {
      $('#modal-title').text('Tambah {{$title}}');
      $('#input-id').val('');
      $('#input-question').val('');
      $('#input-type-question').val('').trigger('change');
      $('#input-type-answer').val('').trigger('change');
      $('#input-answer').val('');
      $('#main-modal').modal('show');
    });

    $(document).on("click", "a#edit-data",function(e) {
      e.preventDefault();
      let id = $(this).data('id');
      $.ajax({
        url: BASE_URL+"/api/inspection_questions/"+id,
        type: 'GET',
        "headers": { 'Authorization': TOKEN },
        dataType: 'JSON',
        beforeSend: function(){
          $('.form-answers').empty()
        },
        success: function(data, textStatus, jqXHR){
          $('#input-id').val(data.id);
          $('#input-question').val(data.question);
          $('#input-type-question').val(data.type_question).trigger('change');
          $('#input-type-answer').val(data.type_answer).trigger('change');
          $('#modal-title').text('Edit {{$title}}');
          if (data.question_answers.length > 0) {
            index_answer = 0
            $.each(data.question_answers, function (index, value) { 
                $('.form-answers').append(addrow(value.content))
                index_answer++
            }) 
          }
          $('#main-modal').modal('show');
        },
        error: function(jqXHR, textStatus, errorThrown){

        },
      });
    });

    

    $(document).on('change', '#input-type-answer', function (e) { 
        e.preventDefault()
        const value = $(this).val()
        if (value == 'multiple_choice') {
            const button = '<button type="button" class="btn mb-3 btn-primary btn-add-row">Add Row</button>'
            $('.form-answers').append(button)
        }else{
          $('.form-answers').empty()
        }
    })

    $(document).on('click', '.btn-add-row', function (e) {
        $('.form-answers').append(addrow())
        index_answer++
    })

    $(document).on('click', '.btn-remove-row', function () { 
        const key = $(this).data('key')
        $('.row-'+key).remove()
    })

    function addrow(value = '') { 
        let row = ''
            row += '<div class="row-'+index_answer+'">'
            row += '    <label class="form-label">Answer</label>'
            row += '    <div class="input-group mb-3">'
            row += '      <input type="text" class="form-control input-answer"  name="answer[]" id="input-question-'+index_answer+'" '
            row += '      value="'+value+'"/>'
            row += '      <div class="input-group-append">'
            row += '        <button data-key="'+index_answer+'" class="btn btn-danger btn-remove-row" type="button">Remove</button>'
            row += '      </div>'
            row += '  </div>'
            row += '</div>'
        return row
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
            "url": BASE_URL+"/api/inspection_question_datatables",
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
            {data: 'question', name: 'question'},
            {data: 'type_answer', name: 'type_answer'},
            {data: 'type_question', name: 'type_question'},
            {data: 'action', name: 'action', orderable: false, className: 'text-end'}
        ],
        "order": [0, 'desc']
      });
    }

  $( 'form.main-form' ).submit( function( e ) {
    e.preventDefault();
    var form_data   = new FormData( this );
    $.ajax({
      type: 'post',
      url: BASE_URL+"/api/inspection_questions",
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
    showDeletePopup(BASE_URL + '/api/inspections/' + id, TOKEN, '', '#main-table', '');
  });
  </script>
@endsection