/**
 * Listener untuk menangani form aksi kunci massal (bulk lock).
 * Menampilkan konfirmasi sebelum form dikirim.
 */
document.addEventListener('DOMContentLoaded', function() {
    const bulkLockForm = document.getElementById('bulk-lock-form');

    if (bulkLockForm) {
        bulkLockForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah form dikirim langsung

            const form = this;
            const opdSelect = form.querySelector('[name="opd_id"]');
            const actionSelect = form.querySelector('[name="action"]');

            // Validasi di sisi klien untuk memastikan input tidak kosong
            if (!opdSelect.value || !actionSelect.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Input Tidak Lengkap',
                    text: 'Silakan pilih Perangkat Daerah dan Aksi terlebih dahulu.',
                });
                return;
            }

            const opdName = opdSelect.options[opdSelect.selectedIndex].text;
            const actionText = actionSelect.options[actionSelect.selectedIndex].text;

            // Tampilkan konfirmasi SweetAlert
            Swal.fire({
                title: 'Konfirmasi Aksi',
                html: `Anda yakin ingin melakukan aksi:<br><strong>"${actionText}"</strong><br>untuk semua data pada OPD:<br><strong>"${opdName}"</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirim form
                    form.submit();
                }
            });
        });
    }
});


// --- FUNGSI-FUNGSI LAMA ANDA (TIDAK PERLU DIUBAH) ---

/**
 * Menampilkan pesan alert bahwa data sedang dikunci.
 */
function showLockedAlert() {
    Swal.fire({
        icon: 'error',
        title: 'Akses Ditolak',
        text: 'Data sedang dikunci. Harap hubungi Super Admin PSIK BAPPEDA untuk membuka kunci.',
    });
}

/**
 * Konfirmasi sebelum mengubah status kunci (lock/unlock) data per baris.
 * @param {string} id - ID dari data monev.
 * @param {boolean} isLocked - Status kunci saat ini.
 */
function toggleLock(id, isLocked) {
    const confirmationText = isLocked ?
        'Apakah Anda yakin ingin MEMBUKA KUNCI data ini? Pengguna lain akan dapat mengeditnya kembali.' :
        'Apakah Anda yakin ingin MENGUNCI data ini? Data tidak akan bisa diedit sampai kunci dibuka.';

    Swal.fire({
        title: 'Konfirmasi Tindakan',
        text: confirmationText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Lanjutkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('form-lock-' + id).submit();
        }
    });
}

/**
 * Konfirmasi sebelum mengubah status validasi data.
 * @param {string} id - ID dari data monev.
 * @param {string} currentStatus - Status validasi saat ini.
 */
function updateStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Valid' ? 'Belum Valid' : 'Valid';
    const text = newStatus === 'Valid' ?
        "Apakah Anda yakin ingin memvalidasi data ini?" :
        "Apakah Anda yakin ingin membatalkan validasi data ini?";

    Swal.fire({
        title: 'Konfirmasi Perubahan Status',
        text: text,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#718355',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Ubah!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('form-status-' + id);
            form.querySelector('input[name="status"]').value = newStatus;
            form.submit();
        }
    });
}

/**
 * Konfirmasi sebelum menghapus data.
 * @param {string} id - ID dari data monev.
 */
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#718355',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formDelete-' + id).submit();
        }
    });
}
