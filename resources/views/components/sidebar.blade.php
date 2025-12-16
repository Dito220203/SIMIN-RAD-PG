
<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar d-flex flex-column">

    <ul class="sidebar-nav flex-grow-1" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? '' : 'collapsed' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Beranda</span>
            </a>
        </li><!-- End Dashboard Nav -->

        @if (auth()->guard('pengguna')->user()->level == 'Admin')
            {{-- Menu khusus Admin --}}

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rencana6tahun*') ? '' : 'collapsed' }}"
                    href="{{ route('rencana6tahun') }}">
                    <i class="bi bi-journal-check"></i>
                    <span>Rencana Aksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rencanakerja*') ? '' : 'collapsed' }}"
                    href="{{ route('rencanakerja') }}">
                    <i class="bi bi-journal-check"></i>
                    <span>Rencana Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monev*') ? '' : 'collapsed' }}" href="{{ route('monev') }}">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Monitoring Evaluasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('progres*') ? '' : 'collapsed' }}"
                    href="{{ route('progres') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Progres Kerja</span>
                </a>
            </li>
        @elseif (auth()->guard('pengguna')->user()->level == 'Super Admin')




            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('banner*') ? '' : 'collapsed' }}"
                    href="{{ route('banner') }}">
                    <i class="bi bi-image"></i>
                    <span>Banner</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('subprogram*') ? '' : 'collapsed' }}"
                    href="{{ route('subprogram') }}">
                    <i class="bi bi-diagram-3"></i>
                    <span>Strategi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rencana6tahun*') ? '' : 'collapsed' }}"
                    href="{{ route('rencana6tahun') }}">
                   <i class="bi bi-check2-square"></i>
                    <span>Rencana Aksi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('rencanakerja*') ? '' : 'collapsed' }}"
                    href="{{ route('rencanakerja') }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Rencana Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('monev*') ? '' : 'collapsed' }}" href="{{ route('monev') }}">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>Monitoring Evaluasi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('progres*') ? '' : 'collapsed' }}"
                    href="{{ route('progres') }}">
                    <i class="bi bi-card-list"></i>
                    <span>Progres Kerja</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('regulasi*') ? '' : 'collapsed' }}"
                    href="{{ route('regulasi') }}">
                     <i class="bi bi-file-earmark-text"></i>
                    <span>Regulasi</span>
                </a>
            </li>




        @endif
    </ul>


    <!-- Bagian bawah sidebar -->
    <div class="mt-auto">
        <ul class="sidebar-nav">
            <li class="nav-heading">Pages</li>
             <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profil*') ? '' : 'collapsed' }}"
                    href="{{ route('profil') }}">
                    <i class="bi bi-person"></i>
                    <span>Profil</span>
                </a>
            </li>
            @if (auth()->guard('pengguna')->user()->level == 'Super Admin')
             <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('pengguna*') ? '' : 'collapsed' }}"
                    href="{{ route('pengguna') }}">
                    <i class="bi bi-people"></i>

                    <span>Pengguna</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('opd*') ? '' : 'collapsed' }}" href="{{ route('opd') }}">
                    <i class="fa-solid fa-house-user"></i>
                    <span>OPD</span>
                </a>
            </li>

            @endif
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="modal"
                    data-bs-target="#modalGantiPassword">
                    <i class="bi bi-key"></i>
                    <span>Ganti Password</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('logout') }}">
                    <i class="ri-logout-box-line"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</aside><!-- End Sidebar-->
