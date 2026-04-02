document.addEventListener("DOMContentLoaded", function () {

    console.log("Global JS aktif");

    // =========================
    // DETEKSI HALAMAN DARI ELEMENT
    // =========================

    const tableBody = document.getElementById('dataTabelBody');
    const searchInput = document.getElementById('liveSearchInput');
    const showEntries = document.getElementById('showEntries');

    // =========================
    // HALAMAN BANNER (AUTO DETECT)
    // =========================
    if (tableBody) {
        console.log("Halaman Banner terdeteksi");

        initSearch(tableBody, searchInput);
        initEntries(tableBody, showEntries);
    }

});


// =========================
// LIVE SEARCH
// =========================
function initSearch(tableBody, input) {
    if (!input) return;

    input.addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
}


// =========================
// SHOW ENTRIES (5,10,25,dll)
// =========================
function initEntries(tableBody, select) {
    if (!select) return;

    const rows = tableBody.querySelectorAll('tr');

    function showRows(limit) {
        rows.forEach((row, index) => {
            row.style.display = index < limit ? '' : 'none';
        });
    }

    // default
    showRows(parseInt(select.value));

    select.addEventListener('change', function () {
        showRows(parseInt(this.value));
    });
}
