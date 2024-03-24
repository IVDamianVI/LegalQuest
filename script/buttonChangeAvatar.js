$(document).ready(function() {
    $('input[type="radio"]').on('change', function() {
        $('#avatar').val('');
    });

    $('#avatar').on('change', function() {
        $('input[type="radio"]').prop('checked', false);
    });

    $('input').on('input', function() {
        if ($('input[name="user"]').val() != "") {
            $('button[type="submit"]').prop('disabled', false);
            $('button[type="submit"]').addClass('ready');
        } else {
            $('button[type="submit"]').prop('disabled', true);
            $('button[type="submit"]').removeClass('ready');
        }
    });
});