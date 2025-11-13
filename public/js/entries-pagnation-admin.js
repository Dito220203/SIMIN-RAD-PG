document.addEventListener('DOMContentLoaded', () => {
    // 1. Ambil elemen-elemen DOM yang dibutuhkan
    const table = document.getElementById('dataTable');
    if (!table) return; // Keluar jika tabel tidak ditemukan

    const tbody = document.getElementById('dataTabelBody');
    const showEntriesSelect = document.getElementById('showEntries');
    const paginationControls = document.getElementById('paginationControls');
    const paginationInfo = document.getElementById('paginationInfo');
    const liveSearchInput = document.getElementById('liveSearchInput');

    // 2. Ambil semua baris data dari tabel (sebelum difilter/pagination)
    let allRows = Array.from(tbody.querySelectorAll('tr'));
    let rowsPerPage = parseInt(showEntriesSelect.value);
    let currentPage = 1;

    // --- Fungsi Utama untuk Merender Data dan Kontrol ---

    /**
     * Menampilkan data ke halaman saat ini.
     * @param {Array<HTMLTableRowElement>} filteredData - Array baris data yang sudah difilter/dicari.
     * @param {number} page - Halaman yang akan ditampilkan.
     */
    function displayData(filteredData, page) {
        const startIndex = (page - 1) * rowsPerPage;
        const endIndex = startIndex + rowsPerPage;
        const pageRows = filteredData.slice(startIndex, endIndex);

        // Bersihkan tbody
        tbody.innerHTML = '';

        // Tampilkan baris data untuk halaman ini
        if (pageRows.length > 0) {
            pageRows.forEach((row, index) => {
                // Perbarui nomor urut (Kolom No) agar sesuai dengan halaman saat ini
                const rowNumberCell = row.cells[0];
                if (rowNumberCell) {
                    // Penomoran relatif terhadap total data yang difilter
                    rowNumberCell.textContent = startIndex + index + 1;
                }
                tbody.appendChild(row);
            });
        } else {
            // Tampilkan pesan "Data tidak ditemukan" jika tidak ada data
            const emptyRow = document.createElement('tr');
            // Pastikan colspan sesuai dengan jumlah kolom di <thead>
            const colSpan = table.querySelector('thead tr') ? table.querySelector('thead tr').children.length : 1;
            emptyRow.innerHTML = `<td colspan="${colSpan}" class="text-center">Data tidak ditemukan.</td>`;
            tbody.appendChild(emptyRow);
        }

        // Perbarui kontrol
        renderPaginationControls(filteredData.length, page);
        renderPaginationInfo(filteredData.length, page);
    }

    /**
     * Membuat kontrol pagination.
     */
    function renderPaginationControls(totalItems, page) {
        const totalPages = Math.ceil(totalItems / rowsPerPage);
        paginationControls.innerHTML = '';

        if (totalPages <= 1) return;

        const ul = document.createElement('ul');
        ul.className = 'pagination pagination-sm mb-0';

        // Tombol 'Previous'
        const prevLi = createPaginationItem('Sebelumnya', page, totalPages, false, page === 1);
        ul.appendChild(prevLi);

        // Tombol Angka Halaman (maksimal 5, termasuk ...)
        let startPage = Math.max(1, page - 2);
        let endPage = Math.min(totalPages, page + 2);

        if (page <= 3) {
            endPage = Math.min(5, totalPages);
        } else if (page > totalPages - 2) {
            startPage = Math.max(1, totalPages - 4);
        }

        if (startPage > 1) {
            ul.appendChild(createPaginationItem('1', 1, totalPages, true, false));
            if (startPage > 2) {
                ul.appendChild(createPaginationItem('...', null, totalPages, false, true));
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            ul.appendChild(createPaginationItem(i.toString(), i, totalPages, true, i === page));
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                ul.appendChild(createPaginationItem('...', null, totalPages, false, true));
            }
            ul.appendChild(createPaginationItem(totalPages.toString(), totalPages, totalPages, true, false));
        }

        // Tombol 'Next'
        const nextLi = createPaginationItem('Berikutnya', page, totalPages, true, page === totalPages);
        ul.appendChild(nextLi);

        paginationControls.appendChild(ul);
    }

    /**
     * Fungsi pembantu untuk membuat item pagination.
     */
    function createPaginationItem(text, pageNumber, totalPages, isClickable, isActive) {
        const li = document.createElement('li');
        li.className = `page-item ${isActive ? 'active' : ''} ${!isClickable || (pageNumber === 1 && text === 'Sebelumnya') || (pageNumber === totalPages && text === 'Berikutnya') ? 'disabled' : ''}`;

        const a = document.createElement('a');
        a.className = 'page-link';
        a.href = '#';
        a.textContent = text;

        if (isClickable && pageNumber !== null && !isActive) {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                if (text === 'Sebelumnya') {
                    currentPage = pageNumber - 1;
                } else if (text === 'Berikutnya') {
                    currentPage = pageNumber + 1;
                } else {
                    currentPage = pageNumber;
                }
                updateTable();
            });
        }

        li.appendChild(a);
        return li;
    }

    /**
     * Menampilkan informasi entri.
     */
    function renderPaginationInfo(totalItems, page) {
        const start = totalItems === 0 ? 0 : (page - 1) * rowsPerPage + 1;
        const end = Math.min(page * rowsPerPage, totalItems);
        paginationInfo.textContent = `Menampilkan ${start} sampai ${end} dari ${totalItems} entri`;
    }

    // --- Logika Filtering dan Update Tabel ---

    /**
     * Memfilter data berdasarkan input pencarian (MENCARI PER HURUF DI SELURUH BARIS).
     * @param {Array<HTMLTableRowElement>} data - Array baris data.
     * @param {string} searchTerm - Kata kunci pencarian.
     * @returns {Array<HTMLTableRowElement>} - Array baris yang cocok.
     */
    function filterData(data, searchTerm) {
        const lowerCaseSearch = searchTerm.toLowerCase();

        if (!lowerCaseSearch) {
            return data;
        }

        return data.filter(row => {
            // Ambil SELURUH teks dari baris tersebut (termasuk No, Program, Strategi, Aksi)
            const rowText = row.textContent.toLowerCase();

            // Cek apakah teks baris mengandung kata kunci pencarian (searching per huruf)
            if (rowText.includes(lowerCaseSearch)) {
                return true;
            }

            return false;
        });
    }

    /**
     * Mengupdate tabel setelah perubahan filter/entries/page.
     */
    function updateTable() {
        const searchTerm = liveSearchInput.value.trim();
        const filteredData = filterData(allRows, searchTerm);

        const totalPages = Math.ceil(filteredData.length / rowsPerPage);

        // Sesuaikan currentPage jika melebihi totalPages baru setelah filter
        if (currentPage > totalPages && totalPages > 0) {
            currentPage = totalPages;
        } else if (totalPages === 0) {
            currentPage = 1;
        }

        displayData(filteredData, currentPage);
    }

    // --- Event Listeners ---

    // 1. Event Dropdown Show Entries
    showEntriesSelect.addEventListener('change', (e) => {
        rowsPerPage = parseInt(e.target.value);
        currentPage = 1; // Reset ke halaman 1 saat merubah jumlah entri
        updateTable();
    });

    // 2. Event Live Search
    liveSearchInput.addEventListener('input', () => {
        currentPage = 1; // Reset ke halaman 1 saat pencarian baru
        updateTable();
    });

    // 3. Panggil saat DOM selesai dimuat (Inisialisasi)
    updateTable();
});
