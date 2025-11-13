<div class="modal fade" id="modalGantiPassword" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formGantiPassword" action="{{ route('update.password') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Password Lama</label>
                        <input type="password" class="form-control" name="current_password" id="current_password">
                        <div class="text-danger mt-1" id="error_current_password"></div>
                    </div>

                    <div class="mb-3">
                        <label>Password Baru</label>
                        <input type="password" class="form-control" id="newpassword" name="new_password">
                        <div class="text-danger mt-1" id="error_new_password"></div>
                    </div>

                    <div class="mb-3">
                        <label>Ulangi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation">
                        <div class="text-danger mt-1" id="error_new_password_confirmation"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <!-- ubah dari submit ke button -->
                        <button type="button" class="btn btn-primary" id="btnSimpanPassword">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {

        // tombol Simpan manual trigger submit
        $("#btnSimpanPassword").on("click", function() {
            $("#formGantiPassword").trigger("submit");
        });

        $("#formGantiPassword").on("submit", function(e) {
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
                $("#error_new_password").text("Password baru wajib diisi.");
                valid = false;
            } else if (newPassword.length < 8) {
                $("#error_new_password").text("Password minimal 8 karakter.");
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

            if (valid) {
                $.ajax({
                    url: $(this).attr("action"),
                    method: $(this).attr("method"),
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Berhasil",
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // reset form + tutup modal
                            $("#formGantiPassword")[0].reset();
                            $("#modalGantiPassword").modal("hide");
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, messages) {
                                $("#error_" + key).text(messages[0]);

                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    text: messages[0]
                                });
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Terjadi kesalahan server"
                            });
                        }
                    }
                });
            }
        });

        // Live check konfirmasi password
        $("#new_password_confirmation").on("input", function() {
            let newPassword = $("#newpassword").val();
            let confirmPassword = $(this).val();

            if (newPassword !== confirmPassword) {
                $("#error_new_password_confirmation").text("Konfirmasi password tidak cocok.");
            } else {
                $("#error_new_password_confirmation").text("");
            }
        });

        // Live check panjang password baru
        $("#newpassword").on("input", function() {
            let newPassword = $(this).val();
            if (newPassword.length < 8) {
                $("#error_new_password").text("Password minimal 8 karakter.");
            } else {
                $("#error_new_password").text("");
            }
        });
    });
</script>
@endpush
