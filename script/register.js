function sanitizeUsername(input) {
    input.value = input.value.replace(/[^a-zA-Z0-9ęóąśłżźćńĘÓĄŚŁŻŹĆŃ]/g, '');
}

function validateEmail(input) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(input);
}

let isUsernameAvailable = false;

$(document).ready(function () {
    var timeoutId = null;

    $("#username").on("input", function () {
        clearTimeout(timeoutId);

        var username = $(this).val();
        if (username.length >= 4) {
            timeoutId = setTimeout(function () {
                $.post("controller/check-username.php", { username: username }, function (data) {
                    var response = JSON.parse(data);
                    if (response.available) {
                        isUsernameAvailable = true;
                        $("#usernameAvailability").text("");
                        $("#username").css("color", "var(--input-font-color)");
                        $("#username").css("border-color", "var(--input-border-color)");
                    } else {
                        isUsernameAvailable = false;
                        $("#usernameAvailability").html("<i class='bi bi-exclamation-triangle-fill'></i> Wprowadź inną nazwę użytkownika.");
                        $("#username").css("color", "var(--error-color)");
                        $("#username").css("border-color", "var(--error-color)");
                    }
                });
            }, 1000);
        } else {
            isUsernameAvailable = false;
            $("#usernameAvailability").text("");
            $("#username").css("color", "var(--input-font-color)");
            $("#username").css("border-color", "var(--input-border-color)");
        }
    });

    $('input').on('input', function () {
        var isUsernameValid = $('input[name="user"]').val().length >= 5 &&
            isUsernameAvailable == true;
        var isPasswordValid = $('input[name="pass"]').val() != "" &&
            $('input[name="pass"]').val().length >= 8 &&
            $('input[name="pass1"]').val() != "" &&
            $('input[name="pass"]').val() == $('input[name="pass1"]').val();
        if (isUsernameValid && isPasswordValid) {
            $('button[type="submit"]').prop('disabled', false);
            $('button[type="submit"]').addClass('ready');
        } else {
            $('button[type="submit"]').prop('disabled', true);
            $('button[type="submit"]').removeClass('ready');
        }
        if ($('input[name="pass"]').val() != "" && $('input[name="pass"]').val() != $('input[name="pass1"]').val()) {
            $('input[name="pass1"]').attr('style', 'border-color: var(--error-color)');
        } else {
            $('input[name="pass1"]').attr('style', 'border-color: #333333');
        }
    });
});

function showPasswordStrength(visible) {
    document.getElementById("passwordStrengthBar").style.display = visible ? "block" : "none";
    document.getElementById("passwordFeedback").style.display = visible ? "block" : "none";
}

const showFeedback = false;

function checkPasswordStrength() {
    const password = document.getElementById("pass").value;
    let strength = 0;
    let feedbackText = "";

    if (password.length >= 8) strength += 1;
    else if (showFeedback) feedbackText += "Hasło powinno mieć co najmniej 8 znaków.\n";

    if (password.length >= 12) strength += 1;

    if (/\d/.test(password)) strength += 1;
    else if (showFeedback) feedbackText += "Dodaj przynajmniej jedną cyfrę.\n";

    if (/[A-Z]/.test(password)) strength += 1;
    else if (showFeedback) feedbackText += "Dodaj przynajmniej jedną dużą literę.\n";

    if (/[a-z]/.test(password)) strength += 1;
    else if (showFeedback) feedbackText += "Dodaj przynajmniej jedną małą literę.\n";

    if (/[^A-Za-z0-9]/.test(password)) strength += 1;
    else if (showFeedback) feedbackText += "Dodaj przynajmniej jeden znak specjalny.\n";

    if (/.*(.)\1\1/.test(password)) {
        strength = 0;
        if (showFeedback) feedbackText = "Hasło nie może zawierać trzech takich samych znaków pod rząd.\n";
    }

    updateStrengthIndicator(strength);
    provideFeedback(feedbackText, strength);
}

function provideFeedback(feedbackText, strength) {
    const feedbackElement = document.getElementById("passwordFeedback");
    if (!feedbackElement) {
        console.error("Feedback element not found.");
        return;
    }

    feedbackElement.innerHTML = feedbackText.replace(/\n/g, '<br>');
    feedbackElement.style.display = (strength < 3 && showFeedback) ? "block" : "none";
}

let lastStrengthLevel = -1;

const strengthMessages = {
    weak: [
        "To hasło? Serio?",
        "Łatwe jak 123456.",
        "Hakerzy to polubią!",
        "Zbyt słabe. Bez dyskusji.",
        "Dodaj wielkie litery i cyfry!",
        "Wymyśl coś lepszego...",
        "To hasło nie przetrwa próby czasu.",
        "Jesteś w stanie wymyślić coś silniejszego.",
        "Spróbuj kombinacji liter i cyfr.",
        "Zastosuj znaki specjalne (@, #, $).",
        "Myśl o haśle jak o tajnym kodzie."
    ],
    medium: [
        "Może być... Ale nie.",
        "Jesteś na dobrej drodze.",
        "Średniak, jak moja kawa.",
        "Podkręć to trochę...",
        "Jak pizza bez sera...",
        "Niezłe, ale postaraj się bardziej",
        "Całkiem solidne, ale nie idealne.",
        "To już coś, ale możemy lepiej.",
        "Dodaj więcej dużych liter.",
        "Użyj nieoczywistych słów.",
        "Zmieszaj różne języki."
    ],
    good: [
        "Całkiem, całkiem.",
        "Niezłe, ale zaskocz mnie!",
        "Mocniejsze niż myślisz.",
        "Robi wrażenie... Częściowo.",
        "Dobre, ale da się lepiej.",
        "W porządku, ale mogłoby być lepiej",
        "Solidne hasło, ale daj z siebie więcej.",
        "To hasło ma potencjał, ale nie jest doskonałe.",
        "Twórz długie hasła, łatwe do zapamiętania.",
        "Inspiruj się cytatami.",
        "Używaj nietypowych kombinacji słów."
    ],
    strong: [
        "Bomba!",
        "Twierdza nie do zdobycia.",
        "Hakerzy płaczą.",
        "Znakomite!",
        "Mistrzostwo świata!",
        "Mocne hasło!",
        "Tak trzymaj! To prawdziwa forteca.",
        "Brawo! Twoje hasło jest naprawdę silne.",
        "Perfekcyjne! Trudne do zgadnięcia.",
        "Jesteś mistrzem haseł!",
        "Idealne. Trzymaj tak dalej!"
    ]
};

function getRandomMessage(messages) {
    return messages[Math.floor(Math.random() * messages.length)];
}

function updateStrengthIndicator(strength) {
    const strengthIndicator = document.getElementById("strengthIndicator");
    let strengthText = "";

    if (strength !== lastStrengthLevel) {
        switch (strength) {
            case 1:
            case 2:
                strengthIndicator.style.width = "25%";
                strengthIndicator.style.backgroundColor = "var(--error-color)";
                // strengthIndicator.style.color = "var(--input-font-color);";
                strengthText = getRandomMessage(strengthMessages.weak);
                break;
            case 3:
                strengthIndicator.style.width = "50%";
                strengthIndicator.style.backgroundColor = "orange";
                // strengthIndicator.style.color = "var(--input-font-color);";
                strengthText = getRandomMessage(strengthMessages.medium);
                break;
            case 4:
                strengthIndicator.style.width = "75%";
                strengthIndicator.style.backgroundColor = "yellow";
                // strengthIndicator.style.color = "black";
                strengthText = getRandomMessage(strengthMessages.good);
                break;
            case 5:
            case 6:
                strengthIndicator.style.width = "100%";
                strengthIndicator.style.backgroundColor = "var(--success-color)";
                // strengthIndicator.style.color = "var(--input-bg-color)";
                strengthText = getRandomMessage(strengthMessages.strong);
                break;
        }
        lastStrengthLevel = strength;
    }

    if (strengthText) {
        strengthIndicator.setAttribute("data-strength-text", strengthText);
    }
}

function provideFeedback(feedbackText, strength) {
    const feedbackElement = document.getElementById("passwordFeedback");
    if (!feedbackElement) {
        console.error("Feedback element not found.");
        return;
    }
    feedbackElement.style.display = "block";
    feedbackElement.textContent = strength < 3 ? feedbackText : "";
}

function disableSubmit(disabled) {
    const submitButton = document.querySelector('button[type="submit"]');
    submitButton.disabled = disabled;
}