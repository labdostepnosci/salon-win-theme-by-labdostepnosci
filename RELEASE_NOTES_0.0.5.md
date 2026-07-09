# Release 0.0.4

Dodano zarządzanie kluczowymi obrazami motywu `Salon Win by labdostepnosci` z poziomu WordPress Customizera.

## Added
- Sekcja `Wygląd → Dostosuj → Salon Win — obrazy`.
- Siedemnaście osobnych kontrolek wyboru obrazu z biblioteki mediów.
- Ustawienia `theme_mod` dla hero, sekcji „O nas”, galerii i obrazów win.
- Funkcja `salon_win_get_image_url()` obsługująca wybrany obraz oraz ścieżkę fallbackową.

## Changed
- Hero, galeria, sekcja „O nas” i obrazy win korzystają teraz z ustawień Customizera.
- Brak wybranego obrazu zachowuje ścieżkę fallbackową w `assets/images/`.

## Deployment
- Zaktualizować motyw do wersji `0.0.4`.
- Wejść w `Wygląd → Dostosuj → Salon Win — obrazy`.
- Wybrać wymagane obrazy z biblioteki mediów i opublikować ustawienia.
- Jeżeli katalog `assets/images/` nie został dostarczony osobno, skonfigurować wszystkie obrazy w Customizerze.
- Wyczyścić cache i sprawdzić stronę główną.
