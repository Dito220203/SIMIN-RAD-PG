
@extends('components.layout')
@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                {{-- Card Profile Info --}}
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            {{-- Avatar dengan Initial --}}
                            <div class="avatar-circle mb-3">
                                <span class="avatar-initial">{{ strtoupper(substr($user->nama ?? 'U', 0, 1)) }}</span>
                            </div>

                            <h2 class="mb-1">{{ $user->nama ?? 'Tidak diketahui' }}</h2>
                            <h6 class="text-muted mb-3">{{ $user->opd->nama ?? '-' }}</h6>
                            <span class="badge bg-primary mb-3">{{ $user->level ?? 'User' }}</span>

                            {{-- Contact Info --}}
                            <div class="profile-info w-100 mt-3">
                                <div class="info-item">
                                    <i class="bi bi-envelope"></i>
                                    <span>{{ $profil->email ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-telephone"></i>
                                    <span>{{ $profil->telepon ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span>{{ $profil->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Content --}}
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            {{-- Tabs --}}
                            <ul class="nav nav-tabs nav-tabs-bordered" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview" type="button" role="tab">
                                        <i class="bi bi-person"></i> Overview
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password" type="button" role="tab">
                                        <i class="bi bi-shield-lock"></i> Change Password
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content pt-4">
                                {{-- Tab Overview --}}
                                <div class="tab-pane fade show active" id="profile-overview" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="card-title mb-0">About</h5>
                                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                            <i class="bi bi-pencil-square me-1"></i> Lengkapi Data
                                        </button>
                                    </div>
                                    <p class="text-muted mb-4">
                                        {{ $profil->about ?? 'Belum ada deskripsi tentang pengguna ini.' }}
                                    </p>

                                    <h5 class="card-title">Profile Details</h5>

                                    <div class="profile-details">
                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-person-badge"></i>
                                                <span>Full Name</span>
                                            </div>
                                            <div class="detail-value">{{ $user->nama ?? '-' }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-building"></i>
                                                <span>Organization</span>
                                            </div>
                                            <div class="detail-value">{{ $user->opd->nama ?? '-' }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-briefcase"></i>
                                                <span>Position</span>
                                            </div>
                                            <div class="detail-value">{{ $user->opd->status ?? '-' }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-shield-check"></i>
                                                <span>Role</span>
                                            </div>
                                            <div class="detail-value">
                                                <span class="badge bg-primary">{{ $user->level ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-envelope"></i>
                                                <span>Email</span>
                                            </div>
                                            <div class="detail-value">{{ $profil->email ?? '-' }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-telephone"></i>
                                                <span>Phone</span>
                                            </div>
                                            <div class="detail-value">{{ $profil->telepon ?? '-' }}</div>
                                        </div>

                                        <div class="detail-row">
                                            <div class="detail-label">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>Address</span>
                                            </div>
                                            <div class="detail-value">{{ $profil->alamat ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Tab Change Password (Sudah diperbaiki) --}}
                                <div class="tab-pane fade" id="profile-change-password" role="tabpanel">
                                    {{-- Tambahkan ID: formGantiPassword --}}
                                    <form id="formGantiPassword" action="{{route('update.password')}}" method="POST">
                                        @csrf

                                        {{-- Current Password --}}
                                        <div class="row mb-3">
                                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{-- Tambahkan ID: current_password --}}
                                                <input name="current_password" type="password" class="form-control" id="current_password" required>
                                                {{-- Tambahkan elemen untuk error JS --}}
                                                <div id="error_current_password" class="text-danger small mt-1"></div>
                                            </div>
                                        </div>

                                        {{-- New Password --}}
                                        <div class="row mb-3">
                                            <label for="newpassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{-- Tambahkan ID: newpassword --}}
                                                <input name="new_password" type="password" class="form-control" id="newpassword" required>
                                                {{-- Tambahkan elemen untuk error JS (ID disesuaikan dengan skrip: error_newpassword) --}}
                                                <div id="error_newpassword" class="text-danger small mt-1"></div>

                                            </div>
                                        </div>

                                        {{-- Confirm New Password --}}
                                        <div class="row mb-3">
                                            <label for="new_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                {{-- Tambahkan ID: new_password_confirmation --}}
                                                <input name="new_password_confirmation" type="password" class="form-control" id="new_password_confirmation" required>
                                                {{-- Tambahkan elemen untuk error JS --}}
                                                <div id="error_new_password_confirmation" class="text-danger small mt-1"></div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" id="btnSimpanPassword" class="btn btn-primary">
                                                <i class="bi bi-shield-check me-1"></i> Change Password
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Modal Edit Profile (Tetap sama) --}}
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="bi bi-pencil-square me-2"></i>Lengkapi Data Profile
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('profil.update')}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">

                            <div class="row mb-3">
                                <label for="editAbout" class="col-sm-3 col-form-label">About</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="editAbout" name="about" rows="3">{{ $user->about }}</textarea>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="editEmail" class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="editEmail" name="email" value="{{ $user->email }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="editTelepon" class="col-sm-3 col-form-label">Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="editTelepon" name="telepon" value="{{ $user->telepon }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="editAlamat" class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="editAlamat" name="alamat" rows="2">{{ $user->alamat }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        /* Avatar Circle */
        .avatar-circle {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .avatar-initial {
            color: white;
            font-size: 48px;
            font-weight: 600;
        }

        /* Profile Info */
        .profile-info {
            padding: 0 20px;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item i {
            font-size: 18px;
            color: #667eea;
            margin-right: 12px;
            width: 24px;
        }

        .info-item span {
            color: #495057;
            font-size: 14px;
        }

        /* Profile Details */
        .profile-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            display: flex;
            align-items: center;
            font-weight: 500;
            color: #6c757d;
        }

        .detail-label i {
            font-size: 18px;
            color: #667eea;
            margin-right: 10px;
        }

        .detail-value {
            font-weight: 500;
            color: #212529;
            text-align: right;
        }

        /* Card Title */
        .card-title {
            color: #2c3e50;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            display: inline-block;
        }

        /* Tabs */
        .nav-tabs-bordered .nav-link {
            border: none;
            color: #6c757d;
            padding: 12px 24px;
            transition: all 0.3s;
        }

        .nav-tabs-bordered .nav-link:hover {
            color: #667eea;
            background: #f8f9fa;
        }

        .nav-tabs-bordered .nav-link.active {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            background: transparent;
        }

        .nav-tabs-bordered .nav-link i {
            margin-right: 6px;
        }

        /* Form */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Button */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 30px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-outline-primary {
            color: #667eea;
            border-color: #667eea;
            transition: all 0.3s;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
        }

        /* Card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }

        /* Modal */
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title i {
            font-size: 1.2rem;
        }
    </style>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        // tombol Simpan manual trigger submit
        // PERBAIKAN: .off('click') menghapus listener 'click' lama sebelum menambah yang baru
        $("#btnSimpanPassword").off('click').on("click", function() {
            $("#formGantiPassword").trigger("submit");
        });

        // PERBAIKAN: .off('submit') menghapus listener 'submit' lama sebelum menambah yang baru
        $("#formGantiPassword").off('submit').on("submit", function(e) {
            e.preventDefault();
            let valid = true;

            // reset pesan error
            $(".text-danger").text("");

            // ambil nilai input
            let currentPassword = $("#current_password").val().trim();
            let newPassword = $("#newpassword").val().trim();
            let confirmPassword = $("#new_password_confirmation").val().trim();

            // Validasi password lama
            if (currentPassword === "") {
                $("#error_current_password").text("Password lama wajib diisi.");
                valid = false;
            }

            // Validasi password baru
            if (newPassword === "") {
                $("#error_newpassword").text("Password baru wajib diisi.");
                valid = false;
            } else if (newPassword.length < 8) {
                $("#error_newpassword").text("Password minimal 8 karakter.");
                valid = false;
            }

            // Validasi konfirmasi password
            if (confirmPassword === "") {
                $("#error_new_password_confirmation").text("Konfirmasi password wajib diisi.");
                valid = false;
            } else if (newPassword !== confirmPassword) {
                $("#error_new_password_confirmation").text("Konfirmasi password tidak cocok.");
                valid = false;
            }

            if(valid){
                // Tambahkan SweetAlert "Loading" sebelum pengiriman AJAX
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang mencoba mengganti password Anda.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.close(); // Tutup loading

                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // reset form
                            $("#formGantiPassword")[0].reset();
                            $(".text-danger").text(""); // Bersihkan juga pesan error

                        } else {
                             // Jika ada logika server yang mengembalikan status: 'error' tapi bukan 422
                             Swal.fire({
                                icon: "error",
                                title: "Gagal",
                                text: response.message || "Gagal mengganti password."
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.close(); // Tutup loading

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let firstErrorMessage = "Validasi gagal. Mohon periksa kembali input Anda.";

                            // Tampilkan error di bawah masing-masing input (jika format error 422 sesuai Laravel)
                            $.each(errors, function(key, messages) {
                                // Menyesuaikan ID error di blade: current_password, new_password, new_password_confirmation
                                let errorKey = key; // Asumsi key sudah benar (cth: 'current_password')

                                if (key === 'new_password') {
                                    firstErrorMessage = messages[0];
                                    $("#error_newpassword").text(messages[0]); // ID input newpassword
                                } else if (key === 'current_password') {
                                    firstErrorMessage = messages[0];
                                    $("#error_current_password").text(messages[0]);
                                } else if (key === 'new_password_confirmation') {
                                    $("#error_new_password_confirmation").text(messages[0]);
                                } else {
                                     $("#error_" + errorKey).text(messages[0]);
                                }
                            });

                            // Tampilkan SweetAlert dengan error pertama yang ditemukan
                            Swal.fire({
                                icon: "error",
                                title: "Validasi Gagal",
                                text: firstErrorMessage
                            });

                        } else if (xhr.status === 401 || xhr.status === 403) {
                             Swal.fire({
                                icon: "warning",
                                title: "Otorisasi Gagal",
                                text: xhr.responseJSON.message || "Anda tidak diizinkan melakukan aksi ini."
                            });
                        } else {
                             Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Terjadi kesalahan server. Silakan coba lagi nanti."
                            });
                    }
                });
            }
        });

        // Live check konfirmasi password
        // PERBAIKAN: .off('input')
        $("#new_password_confirmation").off('input').on("input", function() {
            let newPassword = $("#newpassword").val();
            let confirmPassword = $(this).val();

            if (confirmPassword === "") {
                $("#error_new_password_confirmation").text("Konfirmasi password wajib diisi.");
            } else if (newPassword !== confirmPassword) {
                $("#error_new_password_confirmation").text("Konfirmasi password tidak cocok.");
            } else {
                $("#error_new_password_confirmation").text("");
            }
        });

        // Live check panjang password baru
        // PERBAIKAN: .off('input')
        $("#newpassword").off('input').on("input", function() {
            let newPassword = $(this).val();
            if (newPassword === "") {
                 $("#error_newpassword").text("Password baru wajib diisi."); // ID diperbaiki
            } else if (newPassword.length < 8) {
                $("#error_newpassword").text("Password minimal 8 karakter."); // ID diperbaiki
            } else {
                $("#error_newpassword").text(""); // ID diperbaiki
            }

            // Periksa juga konfirmasi jika password baru diubah
            let confirmPassword = $("#new_password_confirmation").val();
            if (confirmPassword !== "" && newPassword !== confirmPassword) {
                 $("#error_new_password_confirmation").text("Konfirmasi password tidak cocok.");
            } else if (confirmPassword !== "" && newPassword === confirmPassword) {
                 $("#error_new_password_confirmation").text("");
            }
        });
    });
</script>
@endpush
