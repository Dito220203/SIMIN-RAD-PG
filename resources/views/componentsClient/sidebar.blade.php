  {{-- <div class="min-height-300 bg-dark position-absolute w-100"></div> --}}
<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
         <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0">
            <img src="{{ asset('client/img/logo-kabupaten.png') }}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Dashboard RAD-PG</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('dasbor') ? 'active' : '' }}" href="{{ route('dasbor') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
             <a class="nav-link {{ Request::routeIs('strategi') ? 'active' : '' }}" href="{{ route('strategi') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                   <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Strategi</span>
            </a>
        </li>
        <li class="nav-item">
             <a class="nav-link {{ Request::routeIs('rencanaAksi') ? 'active' : '' }}" href="{{ route('rencanaAksi') }}">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Rencana Aksi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('rencanaKerja') ? 'active' : '' }}" href="{{ route('rencanaKerja') }}"> <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Rencana Kerja</span>
            </a>
        </li>
        <li class="nav-item">
             <a class="nav-link {{ Request::routeIs('Monev') ? 'active' : '' }}" href="{{ route('Monev') }}"> <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-archive-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Monev</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::routeIs('progreskerja') ? 'active' : '' }}" href="{{ route('progreskerja') }}"> <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-image text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Progres Kegiatan</span>
            </a>
        </li>






    </ul>
     </div>

     <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <img class="w-50 mx-auto" src="../client/img/illustrations/icon-documentation.svg" alt="sidebar_illustration">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
            <h6 class="mb-0">Login to Your Account</h6>
            <p class="text-xs font-weight-bold mb-0"></p>
          </div>
        </div>
      </div>
      <a href="{{route('login')}}" target="_blank" class="btn btn-dark btn-sm w-100 mb-3">Sign In</a>
      <a class="btn btn-primary btn-sm mb-0 w-100" href="#" type="button">Example</a>
    </div>

</aside>
