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
      <li class="nav-item">
        <a class="nav-link {{(request()->segment(1) == 'subscriptions' ? 'active' : '')}}" href="{{url('/subscriptions')}}" >
          <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" /></svg>
          </span>
          <span class="nav-link-title">
            Subscription
          </span>
        </a>
      </li>
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