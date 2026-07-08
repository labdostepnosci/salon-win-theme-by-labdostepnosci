# Changelog

## [0.0.9] - 2026-07-08

### Changed
- Poprawiono widok archiwum bloga, aby każda karta wpisu od razu pokazywała zdjęcie wyróżniające, tytuł, zajawkę i opisowy link do pełnego wpisu.
- Wydzielono czytelniejsze style kart wpisów blogowych z zachowaniem estetyki Salon Win oraz responsywnej siatki na desktopie i mobile.
- Dodano bezpieczny wariant karty dla wpisów bez zdjęcia wyróżniającego.
- Podbito wersję motywu do `0.0.9`.

### Checked
- Sprawdzono składnię PHP plików szablonów.
- Sprawdzono, że mechanizm GitHub Releases / updater nie został zmieniony.

## [0.0.8] - 2026-07-08

### Changed
- Zmieniono repozytorium motywu na `labdostepnosci/salon-win-theme-by-labdostepnosci`.
- Zaktualizowano `GitHub Theme URI` oraz konfigurację własnego updatera GitHub Releases, aby automatyczne aktualizacje korzystały z nowego repozytorium.
- Podbito wersję motywu do `0.0.8`.

### Checked
- Sprawdzono, że workflow GitHub Actions nadal uruchamia release automatycznie po wypchnięciu taga `vX.Y.Z`.
- Sprawdzono, że release ZIP nadal zawiera katalog główny `salon-win/`.

## [0.0.7] - 2026-07-08

### Added
- Uruchomiono automatyczny proces publikacji GitHub Releases po wypchnięciu taga `vX.Y.Z`.
- Dodano workflow GitHub Actions przygotowujący paczkę ZIP motywu i publikujący ją jako asset release.

### Changed
- Uporządkowano wersjonowanie motywu od wersji `0.0.7`.
- Zaktualizowano instrukcję wydawania wersji pod automatyczne GitHub Releases.

### Checked
- Zachowano `GitHub Theme URI` i `Primary Branch` w `style.css`.
- Nie zmieniono mechanizmu updatera GitHub Releases.

## [0.0.5] - 2026-07-08

### Changed
- Ujednolicono szablony stron, wpisów, archiwów, strony 404 i widoków WooCommerce z językiem wizualnym strony głównej.
- Dodano wspólny system stylów dla treści, nagłówków, akapitów, zdjęć, galerii, przycisków, cytatów, paginacji oraz bloków Gutenberg, Kadence i UAGB.
- Poprawiono czytelność długich treści przez ograniczenie szerokości kolumny, spokojniejsze odstępy i spójne proporcje zdjęć.
- Dodano wsparcie edytora dla stylów motywu, szerokiego wyrównania i responsywnych osadzeń.
- Uporządkowano semantykę bloków nagłówkowych w treści pojedynczych widoków, aby tytuł szablonu pozostał głównym H1.

### Checked
- Sprawdzono, że `GitHub Theme URI` i `Primary Branch` w `style.css` pozostały bez zmian.
- Sprawdzono, że mechanizm GitHub Releases / updater nie został zmieniony.

## [0.0.4] - 2026-07-04

### Added
- Dodano sekcję `Salon Win — obrazy` w Customizerze WordPressa.
- Dodano osobne ustawienia `theme_mod` dla wszystkich kluczowych obrazów motywu.
- Dodano funkcję `salon_win_get_image_url()` obsługującą obrazy z biblioteki mediów i fallbacki z `assets/images/`.

### Changed
- Zaktualizowano hero, sekcję „O nas”, galerię oraz obrazy win, aby korzystały z ustawień Customizera.
- Zachowano dotychczasowe ścieżki plików statycznych jako fallbacki.

### Checked
- Sprawdzono sanitizację URL-i oraz escapowanie przy wyświetlaniu obrazów.
- Sprawdzono składnię wszystkich plików PHP motywu.

## [0.0.3] - 2026-07-04

### Changed
- Zaktualizowano adres Salon-Win na `ul. Wyspiańskiego 16, 38-200 Jasło`.
- Zaktualizowano numer telefonu i linki telefoniczne.
- Zaktualizowano adres e-mail i linki kontaktowe.
- Ujednolicono godziny restauracji oraz informacje o recepcji i dobie hotelowej.
- Zaktualizowano lokalizację i linki Google Maps.
- Poprawiono dane kontaktowe w metadanych Schema.org i ustawieniach motywu.

### Checked
- Sprawdzono wszystkie wystąpienia starych danych kontaktowych.
- Sprawdzono składnię wszystkich plików PHP.

## [0.0.2] - 2026-07-04

### Added
- Dodano własny mechanizm aktualizacji motywu z GitHub Releases.
- Dodano sprawdzanie najnowszego release z repozytorium `labdostepnosci/salon-win`.
- Dodano obsługę paczek release `salon-win-theme-X.Y.Z.zip`.
- Dodano cache zapytań do GitHub API.
- Dodano opcjonalną obsługę tokena `SALON_WIN_GITHUB_TOKEN`.

### Checked
- Sprawdzono podpięcie updatera w `functions.php`.
- Sprawdzono zgodność wersji motywu z mechanizmem GitHub Releases.

## [0.0.1] - 2026-07-04

### Added
- Oznaczono aktualny stan motywu jako pierwszą wersję rozwojową.
- Ustawiono nazwę motywu jako `Salon Win by labdostepnosci`.
- Dodano albo uzupełniono nagłówki umożliwiające aktualizację motywu z GitHuba.
- Przygotowano repozytorium do obsługi aktualizacji przez Git Updater lub podobne narzędzie.

### Checked
- Sprawdzono nagłówek motywu w `style.css`.
- Sprawdzono obecność pola `Version`.
- Sprawdzono obecność pól `GitHub Theme URI` i `Primary Branch`.
