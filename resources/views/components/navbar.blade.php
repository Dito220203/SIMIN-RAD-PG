<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="#" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/logo kabupaten.png') }}" alt="" style="height: 100px; width: auto;">
            <span style="font-family: 'Roboto', sans-serif; font-size: 13px; margin-left: 10px;">
                Halaman Admin
            </span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">


<!-- ==== ALERT DEADLINE (STATIC TEST) ==== -->
            @php
                // Coba ganti nilainya jadi 'lengkap' atau 'terlewat' untuk lihat perubahannya
                $statusData = 'belum';
                $tanggalDeadline = '23 Apr 2026';
            @endphp

            <li class="nav-item d-none d-md-block pe-2">
                @if($statusData == 'belum')
                    <div class="navbar-alert alert-soft-danger">
                        <i class="bi bi-exclamation-circle-fill navbar-alert-icon"></i>
                        <div class="navbar-alert-text">
                            <span class="navbar-alert-title">Deadline: {{ $tanggalDeadline }}</span>
                            <span class="navbar-alert-subtitle">Segera lengkapi data</span>
                        </div>
                    </div>
                @elseif($statusData == 'lengkap')
                    <div class="navbar-alert alert-soft-success">
                        <i class="bi bi-check-circle-fill navbar-alert-icon"></i>
                        <div class="navbar-alert-text">
                            <span class="navbar-alert-title">Data Lengkap</span>
                            <span class="navbar-alert-subtitle">Terima kasih!</span>
                        </div>
                    </div>
                @elseif($statusData == 'terlewat')
                    <div class="navbar-alert alert-soft-warning">
                        <i class="bi bi-exclamation-triangle-fill navbar-alert-icon"></i>
                        <div class="navbar-alert-text">
                            <span class="navbar-alert-title">Waktu Habis</span>
                            <span class="navbar-alert-subtitle">Hubungi Admin Bapperida</span>
                        </div>
                    </div>
                @endif
            </li>
            <!-- ==== END ALERT DEADLINE ==== -->

            @if (auth()->guard('pengguna')->user()->level == 'Super Admin')
                <li class="nav-item dropdown">
                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-warning text-black badge-number">{{ $notifikasi->count() ?? 0 }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end  dropdown-menu-arrow notifications">
                        <li class="dropdown-header">
                            You have {{ $notifikasi->count() ?? 0 }} new notifications
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        @forelse($notifikasi as $item)
                            <li class="notification-item">
                                @if ($item instanceof \App\Models\RencanaKerja)
                                    <i class="bi bi-journal-check text-success"></i>
                                    <div>
                                        <h4>
                                            @php
                                                $perPage = 10;
                                                $position = \App\Models\RencanaKerja::where('delete_at', '0')
                                                    ->where('id', '<=', $item->id)
                                                    ->count();
                                                $page = ceil($position / $perPage);
                                            @endphp
                                            <a
                                                href="{{ route('rencanakerja', ['page' => $page]) }}#row-{{ $item->id }}">
                                                Rencana Kerja Baru
                                            </a>
                                        </h4>
                                        <p>{{ $item->judul }}</p>
                                        <p>{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                @elseif($item instanceof \App\Models\ProgresKerja)
                                    <i class="bi bi-card-list text-success"></i>
                                    <div>
                                        <h4>
                                            @php
                                                $perPage = 10;
                                                $position = \App\Models\ProgresKerja::where(
                                                    'id',
                                                    '<=',
                                                    $item->id,
                                                )->count();
                                                $page = ceil($position / $perPage);
                                            @endphp
                                            <a href="{{ route('progres', ['page' => $page]) }}#row-{{ $item->id }}">
                                                Progres Kerja Baru
                                            </a>
                                        </h4>
                                        <p>{{ $item->judul }}</p>
                                        <p>{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                @elseif($item instanceof \App\Models\Monev)
                                    <i class="bi bi-clipboard-check text-success"></i>
                                    <div>
                                        <h4>
                                            @php
                                                $perPage = 10;
                                                $position = \App\Models\Monev::where('id', '<=', $item->id)->count();
                                                $page = ceil($position / $perPage);
                                            @endphp
                                            <a href="{{ route('monev', ['page' => $page]) }}#row-{{ $item->id }}">
                                                Monitoring & Evaluasi
                                            </a>
                                        </h4>
                                        <p>{{ $item->keterangan }}</p>
                                        <p>{{ $item->created_at->diffForHumans() }}</p>
                                    </div>
                                @endif
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        @empty
                            <li class="notification-item text-center text-muted p-3">
                                <div>Tidak ada notifikasi baru</div>
                            </li>
                        @endforelse

                        <li class="dropdown-footer">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#viewAllNotificationsModal">Lihat
                                semua notifikasi</a>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span>{{ Auth::guard('pengguna')->user()->nama }}</span>
                </a>
            </li>
        </ul>
    </nav>
</header>

<div class="modal fade" id="viewAllNotificationsModal" tabindex="-1" aria-labelledby="viewAllNotificationsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewAllNotificationsModalLabel">Semua Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="list-group list-group-flush">
                    @if (isset($all_notifications) && $all_notifications->count() > 0)
                        @foreach ($all_notifications as $item)
                            {{-- 👇 INI BLOK PHP YANG HILANG & MENYEBABKAN ERROR 👇 --}}
                            @php
                                $perPage = 10;
                                $link = '#'; // Link default jika terjadi kesalahan

                                if ($item instanceof \App\Models\RencanaKerja) {
                                    $position = \App\Models\RencanaKerja::where('delete_at', '0')->where('id', '<=', $item->id)->count();
                                    $page = ceil($position / $perPage);
                                    $link = route('rencanakerja', ['page' => $page]) . '#row-' . $item->id;
                                } elseif ($item instanceof \App\Models\ProgresKerja) {
                                    $position = \App\Models\ProgresKerja::where('id', '<=', $item->id)->count();
                                    $page = ceil($position / $perPage);
                                    $link = route('progres', ['page' => $page]) . '#row-' . $item->id;
                                } elseif ($item instanceof \App\Models\Monev) {
                                    $position = \App\Models\Monev::where('id', '<=', $item->id)->count();
                                    $page = ceil($position / $perPage);
                                    $link = route('monev', ['page' => $page]) . '#row-' . $item->id;
                                }
                            @endphp

                            {{-- Sekarang variabel $link sudah ada dan bisa digunakan di sini --}}
                            <a href="{{ $link }}" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                                @if ($item instanceof \App\Models\RencanaKerja)
                                    <i class="bi bi-journal-check text-success fs-4"></i>
                                @elseif($item instanceof \App\Models\ProgresKerja)
                                    <i class="bi bi-card-list text-success fs-4"></i>
                                @elseif($item instanceof \App\Models\Monev)
                                    <i class="bi bi-clipboard-check text-success fs-4"></i>
                                @endif
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                    <div>
                                        <h6 class="mb-0">
                                            @if ($item instanceof \App\Models\RencanaKerja)
                                                Rencana Kerja Baru
                                            @elseif($item instanceof \App\Models\ProgresKerja)
                                                Progres Kerja Baru
                                            @elseif($item instanceof \App\Models\Monev)
                                                Monitoring & Evaluasi
                                            @endif
                                        </h6>
                                        <p class="mb-0 opacity-75">
                                            @if ($item instanceof \App\Models\RencanaKerja || $item instanceof \App\Models\ProgresKerja)
                                                {{ Str::limit($item->judul, 70) }}
                                            @else
                                                {{ Str::limit($item->keterangan, 70) }}
                                            @endif
                                        </p>
                                    </div>
                                    <small class="opacity-50 text-nowrap">{{ $item->created_at->diffForHumans() }}</small>
                                </div>
                            </a>
                        @endforeach
                    @else
                        <div class="text-center p-5">
                            <p>Tidak ada notifikasi untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
{{-- Script highlight baris saat diarahkan dari notifikasi --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.location.hash) {
            const rowId = window.location.hash;
            try {
                const row = document.querySelector(rowId);
                if (row) {
                    row.style.backgroundColor = "#fff3cd"; // Warna highlight kuning bootstrap
                    row.style.transition = "background-color 0.5s ease";

                    setTimeout(() => {
                        row.style.backgroundColor = "";
                    }, 3000); // Highlight hilang setelah 3 detik
                }
            } catch (e) {
                console.error("Selector error for hash:", rowId, e);
            }
        }
    });
</script>
</body>

</html>
