document.addEventListener("DOMContentLoaded", function () {
    // --- Elemen-elemen yang dibutuhkan ---
    const modalElement = document.getElementById('anggaranModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const modalForm = document.getElementById('modal-anggaran-form');
    const anggaranInput = document.getElementById('modal-anggaran');
    const sumberDanaSelect = document.getElementById('modal-sumberdana');
    const lainnyaContainer = document.getElementById('modal-sumberdana-lainnya-container');
    const lainnyaInput = document.getElementById('modal-sumberdana-lainnya');
    const addButton = document.getElementById('tambah-ke-tabel');
    const tableBody = document.getElementById('anggaran-table-body');
    const hiddenContainer = document.getElementById('hidden-inputs-container');

    // --- Fungsi Helper untuk Format Rupiah ---
    const formatRupiah = (input) => {
        // Hanya ambil angka saja untuk diformat
        let value = input.value.replace(/\D/g, '');
        return value ? 'Rp. ' + parseInt(value).toLocaleString('id-ID') : '';
    };

    // --- Event Listener untuk Modal (PERBAIKAN DISINI) ---
    anggaranInput.addEventListener('input', function() {
        // 1. Ambil nilai asli
        let val = this.value;

        // 2. Bersihkan 'Rp', titik, dan spasi untuk pengecekan huruf
        // Tujuannya agar 'Rp' tidak dianggap sebagai nama barang
        let cleanVal = val.replace(/Rp|\.| /gi, '');

        // 3. Cek apakah sisa karakter mengandung huruf a-z
        const hasText = /[a-zA-Z]/.test(cleanVal);

        // 4. Jika TIDAK ada huruf (berarti angka murni), jalankan format Rupiah
        if (!hasText) {
            this.value = formatRupiah({ value: val });
        }
        // Jika ada huruf (misal: "Laptop"), biarkan apa adanya tanpa format
    });

    sumberDanaSelect.addEventListener('change', () => {
        if (sumberDanaSelect.value === 'Lainnya') {
            lainnyaContainer.style.display = 'block';
            lainnyaInput.required = true;
        } else {
            lainnyaContainer.style.display = 'none';
            lainnyaInput.required = false;
        }
    });

    // --- Logika saat tombol "Tambah ke Tabel" di Modal diklik ---
    addButton.addEventListener('click', () => {
        if (!modalForm.checkValidity()) {
            modalForm.reportValidity();
            return;
        }

        const anggaranFormatted = anggaranInput.value;

        let sumberDanaValue = sumberDanaSelect.value;
        const sumberDanaText = sumberDanaValue === 'Lainnya' ? lainnyaInput.value : sumberDanaSelect.options[sumberDanaSelect.selectedIndex].text;

        if (sumberDanaValue === 'Lainnya') {
            sumberDanaValue = lainnyaInput.value;
        }

        // Validasi: Pastikan tidak kosong (bisa angka Rp atau teks barang)
        if (!anggaranFormatted.trim() || !sumberDanaValue) {
            alert('Anggaran dan Sumber Dana harus diisi.');
            return;
        }

        const uniqueId = 'row-' + Date.now();

        const newRow = `
            <tr id="${uniqueId}">
                <td class="text-center">${anggaranFormatted}</td>
                <td class="text-center">${sumberDanaText}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm hapus-anggaran-row" data-target="${uniqueId}">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);

        const newHiddenInputs = `
            <div id="hidden-${uniqueId}">
                <input type="hidden" name="anggaran[]" value="${anggaranFormatted}">
                <input type="hidden" name="sumberdana[]" value="${sumberDanaValue}">
            </div>
        `;
        hiddenContainer.insertAdjacentHTML('beforeend', newHiddenInputs);

        modalForm.reset();
        lainnyaContainer.style.display = 'none';
        lainnyaInput.required = false;
        modal.hide();
    });

    // --- Logika Hapus Baris ---
    tableBody.addEventListener('click', (e) => {
        const deleteButton = e.target.closest('.hapus-anggaran-row');
        if (deleteButton) {
            const targetId = deleteButton.dataset.target;
            document.getElementById(targetId)?.remove();
            document.getElementById('hidden-' + targetId)?.remove();
        }
    });
});
