

document.addEventListener('DOMContentLoaded', function () {
    const textarea = document.getElementById('expandingTextarea');
    const form = document.querySelector('form');

    // // Function to send a new message using AJAX
    // function sendMessage(message) {
    //     $.post('ajax_update.php', { message: message }, function (response) {
    //         // Handle the response, e.g., show success message, clear the textarea, etc.
    //         // You may need to adjust this based on your specific requirements.
    //     });
    // }

    // form.addEventListener('submit', function (event) {
    //     event.preventDefault();
    //     const message = textarea.value;
    //     sendMessage(message);
    // });

    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const message = textarea.value;
        const formattedMessage = message.replace(/\n/g, '<br>');
        textarea.value = formattedMessage;
        this.submit();
    });
});

//^ Skrypt blokowania przycisku submit, jeśli pola nie spełniają wymagań
$(document).ready(function () {
    // Funkcja sprawdzająca, czy textarea jest pusta
    function isTextareaEmpty() {
        return $('#expandingTextarea').val().trim() === '';
    }

    function isToUserEmpty() {
        return $('#toUser').val().trim() === '';
    }

    function isAttachmentEmpty() {
        return $('#attachment').val().trim() === '';
    }

    // Funkcja sprawdzająca, czy wszystkie pola spełniają wymagania i aktualizująca przycisk submit
    function updateSubmitButton() {
        if ((!isToUserEmpty() && !isTextareaEmpty()) || (!isToUserEmpty() && !isAttachmentEmpty())) {
            $('button[type="submit"]').prop('disabled', false);
            $('button[type="submit"]').addClass('ready');
        } else {
            $('button[type="submit"]').prop('disabled', true);
            $('button[type="submit"]').removeClass('ready');
        }
    }

    // Nasłuchiwanie na zmiany w polach input
    $('input, #expandingTextarea').on('input', updateSubmitButton);

    // Inicjalna aktualizacja przycisku submit po załadowaniu strony
    updateSubmitButton();
});