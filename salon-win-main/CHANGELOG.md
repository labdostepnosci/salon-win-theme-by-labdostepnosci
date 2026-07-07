# Changelog

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
