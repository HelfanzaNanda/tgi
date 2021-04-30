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
        {{-- <button type="button" class="btn btn-primary d-none d-sm-inline-block" id="show-main-modal">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          Tambah {{$title}}
        </button> --}}
        <button type="button" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal" data-bs-target="#modal-report" aria-label="Create new report">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </button>
      </div>
      <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable" id="main-table" style="padding-top: 15px;">
          <thead>
            <tr>
              <th class="w-1">Izin Pengguna</th>
              <th>Guard Name</th>
              @foreach ($roles as $role)
                  <th>{{ $role->name }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $key => $permission)
            <tr class="table-secondary">
              <td colspan="{{ $roles->count() + 3 }}"><strong>{{ $key }}</strong></td>
              @foreach ($permission as $item)
              <tr>
                <td>{{ __('permissions.'.$item['name']) }}</td>
                <td>{{ $item['guard_name'] }}</td>
                @foreach ($roles as $role)
                  <td>
                    <div class="form-check">
                      @php
                          $roleNames = $item->roles->pluck('name');
                      @endphp
                      <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="{{ $role->name }}" 
                      type="checkbox" {{ $roleNames->contains($role->name)  ? 'checked' : '' }}>
                    </div>
                  </td>
                @endforeach
              </tr>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $permissions->links() }}
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    $('.checkbox').on('click', function(){
      const perm = $(this).data('perm');
      const role = $(this).data('role');
      const bool = $(this).is(':checked');
      const data = { "_token": "{{ csrf_token() }}", 'perm': perm, 'role': role, 'bool': bool };
      $.ajax({
          type: 'POST',
          url: BASE_URL+'/permissions',
          data: data,
          success: function(res) {
              if (res.status) {
                location.reload()
              }
          }
        })
    });
      
  });
</script>
@endsection