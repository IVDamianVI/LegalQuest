// Funkcja do zmiany motywu strony
function toggleTheme() {
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'light');
        localStorage.setItem('theme', 'light'); // Zapisz wybór użytkownika w localStorage
    } else {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        localStorage.setItem('theme', 'dark'); // Zapisz wybór użytkownika w localStorage
    }
}

// Funkcja do ustawiania ikony na podstawie motywu strony
function setIconBasedOnTheme() {
    var icon = $('#btnSwitch').find('i');
    if (document.documentElement.getAttribute('data-bs-theme') == 'dark') {
        icon.removeClass('bi-sun-fill').addClass('bi-moon-stars-fill');
    } else {
        icon.removeClass('bi-moon-stars-fill').addClass('bi-sun-fill');
    }
}

// Obsługa kliknięcia przycisku zmiany motywu
document.getElementById('btnSwitch').addEventListener('click', function () {
    toggleTheme();
    setIconBasedOnTheme();
});

// Ustawienie ikony na podstawie motywu
setIconBasedOnTheme();

// Funkcja inicjalizująca motyw strony na podstawie danych z localStorage
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme');
    // console.log(savedTheme);
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    } else if (savedTheme === 'light') {
        document.documentElement.setAttribute('data-bs-theme', 'light');
    } else {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
    }

    // Aktualizacja ikony na podstawie motywu strony
    setIconBasedOnTheme();
}

// inicjalizacja motywu strony
initializeTheme();