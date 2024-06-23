//^ Skrypt blokowania przycisku submit, jeśli pola nie spełniają wymagań
$(document).ready(function () {
    $('input').on('input', function () {
        if ($('input[name="user"]').val() != "" && $('input[name="pass"]').val() != "") {
            $('button[type="submit"]').prop('disabled', false);
            $('button[type="submit"]').addClass('ready');
        } else {
            $('button[type="submit"]').prop('disabled', true);
            $('button[type="submit"]').removeClass('ready');
        }
    });
});