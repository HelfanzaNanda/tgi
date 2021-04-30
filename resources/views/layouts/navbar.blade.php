@php
    $user = App\Models\User::find(Session::get('_id'));
@endphp
<div class="collapse navbar-collapse" id="navbar-menu">
  <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link {{(request()->segment(1) == 'home' ? 'active' : '')}}" href="{{url('/home')}}" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="5 12 3 12 12 3 21 12 19 12" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
          </span>
          <span class="nav-link-title">
            Home
          </span>
        </a>
      </li>
      @if($user->hasAnyPermission(['suppliers', 'users']))
        <li class="nav-item active dropdown">
          <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
            </span>
            <span class="nav-link-title">
              Master Data
            </span>
          </a>
          <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
              <div class="dropdown-menu-column">
                @if ($user->can('suppliers'))
                  <a class="dropdown-item" href="{{url('/suppliers')}}" >
                    Pemasok
                  </a>  
                @endif
                @if ($user->can('users'))
                  <a class="dropdown-item" href="{{url('/users')}}" >
                    Pengguna
                  </a>    
                @endif 
              </div>
            </div>
          </div>
        </li>
      @endif

      @if($user->hasAnyPermission(['warehouses', 'racks']))
        <li class="nav-item active dropdown">
          <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
            </span>
            <span class="nav-link-title">
              Penyimpanan
            </span>
          </a>
          <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
              <div class="dropdown-menu-column">
                @if ($user->can('warehouses'))
                  <a class="dropdown-item" href="{{url('/warehouses')}}" >
                    Gudang
                  </a>    
                @endif
                @if ($user->can('racks'))
                  <a class="dropdown-item" href="{{url('/racks')}}" >
                    Rak
                  </a>    
                @endif
              </div>
            </div>
          </div>
        </li>
      @endif


      @if($user->hasAnyPermission(['categories', 'units', 'inventories', 'stock_opnames']))
      <li class="nav-item active dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
          </span>
          <span class="nav-link-title">
            Barang
          </span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
              @if ($user->can('categories'))
                <a class="dropdown-item" href="{{url('/categories')}}" >
                  Kategori
                </a>    
              @endif
              @if ($user->can('units'))
                <a class="dropdown-item" href="{{url('/units')}}" >
                  Unit
                </a>    
              @endif
              @if ($user->can('inventories'))
                <a class="dropdown-item" href="{{url('/inventories')}}" >
                  Barang
                </a>    
              @endif
              @if ($user->can('stock_opnames'))
                <a class="dropdown-item" href="{{url('/stock_opnames')}}" >
                  Stok Opname
                </a>    
              @endif
            </div>
          </div>
        </div>
      </li>
      @endif

      @if ($user->hasAnyPermission(['incoming_inventories', 'outcoming_inventories', 'request_inventories']))
        <li class="nav-item active dropdown">
          <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
            </span>
            <span class="nav-link-title">
              Transaksi
            </span>
          </a>
          <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
              <div class="dropdown-menu-column">
                @if ($user->can('incoming_inventories'))
                  <a class="dropdown-item" href="{{url('/incoming_inventories')}}" >
                    Barang Masuk
                  </a>    
                @endif
                @if ($user->can('outcoming_inventories'))
                  <a class="dropdown-item" href="{{url('/outcoming_inventories')}}" >
                    Barang Keluar
                  </a>    
                @endif
                @if ($user->can('request_inventories'))
                  <a class="dropdown-item" href="{{url('/request_inventories')}}" >
                    Permintaan Barang
                  </a>    
                @endif
              </div>
            </div>
          </div>
        </li>    
      @endif
      

      @if ($user->hasAnyPermission(['roles', 'permissions']))
      <li class="nav-item active dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
          </span>
          <span class="nav-link-title">
            Permissions
          </span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
              @if ($user->can('roles'))
                <a class="dropdown-item" href="{{url('/roles')}}" >
                  Hak Akses
                </a>
              @endif
              @if ($user->can('permissions'))
                <a class="dropdown-item" href="{{url('/permissions')}}" >
                  Izin Pengguna
                </a>    
              @endif
            </div>
          </div>
        </div>
      </li>
      @endif



      {{-- <li class="nav-item active dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
          </span>
          <span class="nav-link-title">
            Laporan
          </span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
              <a class="dropdown-item" href="{{url('/report/inventories')}}" >
                Stok Barang
              </a>
              <a class="dropdown-item" href="{{url('/report/incoming_inventories')}}" >
                Barang Masuk
              </a>
              <a class="dropdown-item" href="{{url('/report/outcoming_inventories')}}" >
                Barang Keluar
              </a>
            </div>
          </div>
        </div>
      </li> --}}
    </ul>
    <div class="ms-md-auto ps-md-4 py-2 py-md-0 me-md-4 order-first order-md-last flex-grow-1 flex-md-grow-0">
      <form action="." method="get">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" class="form-control form-control-dark" placeholder="Search…" aria-label="Search in website">
        </div>
      </form>
    </div>
  </div>
</div>