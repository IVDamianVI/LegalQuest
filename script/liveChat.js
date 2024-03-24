$(document).ready(function () {
    fetchData();
    setInterval(function () {
        fetchData();
    }, 6000);
});

function fetchData() {
    $.ajax({
        url: 'chatScript.php',
        type: 'GET',
        success: function (data) {
            $('#data-container').html(data);
        },
        error: function (error) {
            console.log('Wystąpił błąd podczas pobierania danych: ' + error);
        }
    });
}
