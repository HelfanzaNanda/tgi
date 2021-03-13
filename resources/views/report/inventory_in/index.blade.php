@extends('layouts.main')

@section('title', 'Laporan Barang Masuk')

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
  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Ranking Business Type</h3>
      </div>
      <table class="table card-table table-vcenter">
        <thead>
          <tr>
            <th>Business Type</th>
            <th>Count</th>
          </tr>
        </thead>
        <tbody id="business-type-rank">
          <tr>
            <td>Instagram</td>
            <td>3,550</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Ranking Database Storage</h3>
      </div>
      <table class="table card-table table-vcenter">
        <thead>
          <tr>
            <th>Company</th>
            <th>Size (Mb)</th>
          </tr>
        </thead>
        <tbody id="database-usage-rank">
          <tr>
            <td>Instagram</td>
            <td>3,550</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-6 col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Ranking Edition</h3>
      </div>
      <table class="table card-table table-vcenter">
        <thead>
          <tr>
            <th>Edition</th>
            <th>Count</th>
          </tr>
        </thead>
        <tbody id="edition-rank">
          <tr>
            <td>Instagram</td>
            <td>3,550</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('modal')

@endsection

@section('script')
  <script type="text/javascript">

  </script>
@endsection