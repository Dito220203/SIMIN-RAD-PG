function updateStatus(id, currentStatus) {
    let newStatus = currentStatus === 'Valid' ? 'Belum Validasi' : 'Valid';
    let confirmText = currentStatus === 'Valid'
        ? 'Batalkan validasi data ini?'
        : 'Validasi data ini?';

    Swal.fire({
        title: 'Yakin?',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#718355",
        cancelButtonColor: "#d33",
        confirmButtonText: 'Ya, lanjutkan',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.getElementById('form-status-' + id);
            form.querySelector('input[name="status"]').value = newStatus;
            form.submit();
        }
    });
}
