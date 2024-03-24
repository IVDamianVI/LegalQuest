function createDirectory() {
    var directoryName = document.getElementById('directoryName').value;

    $.ajax({
        url: 'cloudCreateDirScript.php',
        type: 'POST',
        data: {
            directoryName: directoryName
        },
        success: function (response) {
                location.reload();
        },
        error: function (error) {
            alert('Error creating directory 2');
        }
    });
}

function deleteFile(fileName, id, fileType) {
    if (confirm("Czy na pewno chcesz usunąć ten plik?")) {
        $.ajax({
            url: 'cloudDeleteScript.php',
            type: 'POST',
            data: {
                fileName: fileName,
                id: id,
                fileType: fileType
            },
            success: function (response) {
                location.reload();
            }
        });
    }
}

function duplicateFile(fileName, id) {
    if (confirm("Czy na pewno chcesz utworzyć kopię tego pliku?")) {
        $.ajax({
            url: 'cloudCopyScript.php',
            type: 'POST',
            data: {
                fileName: fileName,
                fileId: id
            },
            success: function (response) {
                location.reload();
            }
        });
    }
}

function renameFile(fileName, id) {
    var newFileName = prompt('Wprowadź nową nazwę pliku:', fileName);

    if (newFileName !== null) {
        $.ajax({
            url: 'cloudRenameScript.php',
            type: 'POST',
            data: {
                newFileName: newFileName,
                fileId: id
            },
            success: function (response) {
                location.reload();
            }
        });
    }
}

$(document).ready(function () {
    $('#table').DataTable({
        "lengthMenu": [5, 10, 20, 50, 75, 100],
        "pageLength": 5,
        "dom": '<"top"f>rt<"bottom"ilp><"clear">',
        "columnDefs": [{
            "targets": 0,
            "orderable": true,
        }],
        "order": [[1, "asc"]],
        "info": true,
        "searching": true,
        "paging": false,
        "language": {
            "decimal": "",
            "emptyTable": "Brak danych",
            "info": "Wyświetlanie _END_ z _TOTAL_ plików",
            "infoEmpty": "Wyświetlanie 0 z 0 plików",
            "infoFiltered": "(Filtrowanie z _MAX_ plików)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Pokazuj _MENU_ plików",
            "loadingRecords": "Ładowanie...",
            "processing": "",
            "search": "Szukaj: ",
            "zeroRecords": "Brak pasujących wyników",
            "paginate": {
                "first": " Pierwsza ",
                "last": " Ostatnia ",
                "next": '<i class="bi bi-arrow-right-circle-fill"></i>',
                "previous": '<i class="bi bi-arrow-left-circle-fill"></i>'
            },
            "aria": {
                "sortAscending": ": aktywuj, żeby sortować rosnąco",
                "sortDescending": ": aktywuj, żeby sortować malejąco"
            }
        },
    });
});