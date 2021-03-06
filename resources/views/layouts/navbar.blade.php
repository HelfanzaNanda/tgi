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
      @if($user->hasAnyPermission(['suppliers', 'users', 'inspections', 'customers']))
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
                    Supplier
                  </a>  
                @endif
                @if ($user->can('users'))
                  <a class="dropdown-item" href="{{url('/users')}}" >
                    User
                  </a>
                @endif 
                @if ($user->can('inspection_questions'))
                  <a class="dropdown-item" href="{{url('/inspection_questions')}}" >
                    Inspection Question
                  </a>
                @endif 
                @if ($user->can('customers'))
                  <a class="dropdown-item" href="{{url('/customers')}}" >
                    Customer
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
              Storage
            </span>
          </a>
          <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
              <div class="dropdown-menu-column">
                @if ($user->can('warehouses'))
                  <a class="dropdown-item" href="{{url('/warehouses')}}" >
                    Warehouse
                  </a>    
                @endif
                @if ($user->can('racks'))
                  <a class="dropdown-item" href="{{url('/racks')}}" >
                    Rack
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
            Inventory
          </span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
              @if ($user->can('categories'))
                <a class="dropdown-item" href="{{url('/categories')}}" >
                  Category
                </a>    
              @endif
              @if ($user->can('units'))
                <a class="dropdown-item" href="{{url('/units')}}" >
                  Unit
                </a>    
              @endif
              @if ($user->can('inventory_groups'))
              <a class="dropdown-item" href="{{url('/inventory_groups')}}" >
                Product Group
              </a>
              @endif
              @if ($user->can('variants'))
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{url('/variants')}}" >
                Variant
              </a>
              <div class="dropdown-divider"></div>
              @endif
              @if ($user->can('inventories'))
                <a class="dropdown-item" href="{{url('/inventories')}}" >
                  Product
                </a>    
              @endif
              @if ($user->can('stock_opnames'))
                <a class="dropdown-item" href="{{url('/stock_opnames')}}" >
                  Stock Opname
                </a>    
              @endif
            </div>
          </div>
        </div>
      </li>
      @endif

      @if ($user->hasAnyPermission(['incoming_inventories', 'outcoming_inventories', 'request_inventories', 'scheduled_arrivals']))
        <li class="nav-item active dropdown">
          <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
            <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
            </span>
            <span class="nav-link-title">
              Mutation
            </span>
          </a>
          <div class="dropdown-menu">
            <div class="dropdown-menu-columns">
              <div class="dropdown-menu-column">
                @if ($user->can('incoming_inventories'))
                  <a class="dropdown-item" href="{{url('/incoming_inventories')}}" >
                    Inbound
                  </a>    
                @endif
                @if ($user->can('outcoming_inventories'))
                  <a class="dropdown-item" href="{{url('/outcoming_inventories')}}" >
                    Outbound
                  </a>    
                @endif
                @if ($user->can('request_inventories'))
                  <a class="dropdown-item" href="{{url('/request_inventories')}}" >
                    Request Product
                  </a>    
                @endif
                @if ($user->can('scheduled_arrivals'))
                  <a class="dropdown-item" href="{{url('/scheduled_arrivals')}}" >
                    Scheduled Arrival
                  </a>    
                @endif
              </div>
            </div>
          </div>
        </li>    
      @endif

      @if ($user->hasAnyPermission(['report_product_mutations', 'report_scheduled_arrivals', 'report_stock_minimums']))
      <li class="nav-item active dropdown">
        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" /><line x1="12" y1="12" x2="20" y2="7.5" /><line x1="12" y1="12" x2="12" y2="21" /><line x1="12" y1="12" x2="4" y2="7.5" /><line x1="16" y1="5.25" x2="8" y2="9.75" /></svg>
          </span>
          <span class="nav-link-title">
            Report
          </span>
        </a>
        <div class="dropdown-menu">
          <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
              @if ($user->can('report_product_mutations'))
                <a class="dropdown-item" href="{{url('reports/product_mutations')}}" >
                  Product Mutation
                </a>
              @endif
              @if ($user->can('report_scheduled_arrivals'))
                <a class="dropdown-item" href="{{url('reports/scheduled_arrivals')}}" >
                  Scheduled Arrival
                </a>    
              @endif
              @if ($user->can('report_stock_minimums'))
                <a class="dropdown-item" href="{{url('reports/stock_minimums')}}" >
                  Stok Minumum
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
    {{-- <div class="ms-md-auto ps-md-4 py-2 py-md-0 me-md-4 order-first order-md-last flex-grow-1 flex-md-grow-0">
      <form action="." method="get">
        <div class="input-icon">
          <span class="input-icon-addon">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="10" cy="10" r="7" /><line x1="21" y1="21" x2="15" y2="15" /></svg>
          </span>
          <input type="text" class="form-control form-control-dark" placeholder="Search???" aria-label="Search in website">
        </div>
      </form>
    </div> --}}
  </div>
</div>