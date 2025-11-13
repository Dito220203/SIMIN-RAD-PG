$(document).ready(function () {
    const dataTable = $('#tabelSaya').DataTable({
        paging: true,
        lengthChange: true,
        ordering: true,
        info: true,
        autoWidth: false,
        responsive: true,
        dom: 'lrtip',
        language: {
            search: "Cari:",
            info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ entri",
            infoEmpty: "Tidak ada data untuk ditampilkan",
            infoFiltered: "(disaring dari _MAX_ total entri)",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: ">",
                previous: "<"
            }
        }
    });

    // Pindah elemen DataTables ke container custom
    $('#tabelSaya_length').appendTo('#lengthContainer');
    $('#tabelSaya_paginate').appendTo('#paginationContainer');
    $('#tabelSaya_info').appendTo('#infoContainer');

    // Pastikan pagination tetap dipindah setiap kali redraw
    dataTable.on('draw', function () {
        if (!$('#paginationContainer').has('#tabelSaya_paginate').length) {
            $('#tabelSaya_paginate').appendTo('#paginationContainer');
        }
        if (!$('#infoContainer').has('#tabelSaya_info').length) {
            $('#tabelSaya_info').appendTo('#infoContainer');
        }
    });

    // Custom search
    $('#liveSearchInput').on('keyup', function () {
        dataTable.search(this.value).draw();
    });
});
