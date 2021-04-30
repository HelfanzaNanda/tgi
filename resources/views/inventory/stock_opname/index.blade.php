@extends('layouts.main')

@section('title', 'Stok Opname')

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
      <div class="page-pretitle">
        <center>Overview</center>
      </div>
      <h2 class="page-title">
        <center>ON PROGRESS..</center>
      </h2>
    </div>
  </div>
</div>
<div class="row row-deck row-cards">
  
</div>
@endsection

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">

  </script>
@endsection