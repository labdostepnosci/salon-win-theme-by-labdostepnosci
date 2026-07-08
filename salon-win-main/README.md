# Salon Win — Motyw WordPress

Elegancki, nowoczesny motyw WordPress dla butikowego salonu winiarskiego.  
Zaprojektowany zgodnie z trendami 2026: ciemna paleta, serif display font, asymetryczne layouty, animacje scroll-triggered.

---

## Aktualna wersja

| Pole | Wartość |
|------|---------|
| Aktualna wersja motywu | `0.0.9` |
| Data ostatniej wersji | 2026-07-08 |
| Tag release | `v0.0.9` |
| Repozytorium | `labdostepnosci/salon-win-theme-by-labdostepnosci` |
| Paczka release | `salon-win-0.0.9.zip` oraz `salon-win-theme-0.0.9.zip` |

---

## Historia wersji i changelog

| Wersja | Data | Typ zmian | Changelog |
|--------|------|-----------|-----------|
| `0.0.9` | 2026-07-08 | Blog archive | Poprawiono karty wpisów na stronie bloga: zdjęcie, tytuł, zajawka i opisowy link są widoczne od razu, z fallbackiem dla wpisów bez miniatury. |
| `0.0.8` | 2026-07-08 | Repo i updater | Zmieniono nazwę repozytorium na `salon-win-theme-by-labdostepnosci`; zaktualizowano `GitHub Theme URI` i konfigurację updatera, aby automatyczne aktualizacje korzystały z nowego repozytorium. |
| `0.0.7` | 2026-07-08 | Release automation | Uruchomiono automatyczne publikowanie GitHub Releases po wypchnięciu taga `vX.Y.Z`; dodano workflow GitHub Actions przygotowujący paczki ZIP motywu; uporządkowano wersjonowanie od `0.0.7`. |
| `0.0.5` | 2026-07-08 | System wizualny | Ujednolicono szablony stron, wpisów, archiwów, strony 404 i WooCommerce z językiem wizualnym strony głównej; dodano style dla treści, nagłówków, zdjęć, galerii, przycisków, cytatów, paginacji oraz bloków Gutenberg, Kadence i UAGB. |
| `0.0.4` | 2026-07-04 | Customizer obrazów | Dodano sekcję `Salon Win — obrazy` w Customizerze, osobne ustawienia `theme_mod` dla kluczowych obrazów oraz funkcję `salon_win_get_image_url()` z fallbackami. |
| `0.0.3` | 2026-07-04 | Dane kontaktowe | Zaktualizowano adres, telefon, e-mail, godziny, lokalizację Google Maps oraz dane Schema.org. |
| `0.0.2` | 2026-07-04 | Updater | Dodano własny mechanizm aktualizacji motywu z GitHub Releases, obsługę paczek release, cache API oraz opcjonalny token `SALON_WIN_GITHUB_TOKEN`. |
| `0.0.1` | 2026-07-04 | Pierwsza wersja | Oznaczono pierwszy stan rozwojowy motywu, ustawiono nagłówki wersjonowania i przygotowano repozytorium do aktualizacji z GitHuba. |

Pełny changelog znajduje się w pliku [`CHANGELOG.md`](CHANGELOG.md).

---

## Wymagania

| Zależność        | Minimalna wersja |
|-----------------|-----------------|
| WordPress        | 6.4+            |
| PHP              | 8.1+            |
| WooCommerce      | 8.0+ (opcjonalnie) |

---

## Instalacja krok po kroku

### 1. Upload motywu
```
/wp-content/themes/salon-win/
```
Wgraj cały folder przez FTP lub panel hostingowy, albo zipuj i zainstaluj przez  
`Wygląd > Motywy > Dodaj nowy > Wgraj motyw`.

### 2. Aktywuj motyw
`Wygląd > Motywy > Salon Win > Aktywuj`

### 3. Ustaw stronę główną
`Ustawienia > Czytanie > Strona startowa to wybrana strona statyczna`  
Utwórz stronę o nazwie „Strona Główna" i przypisz jako Front Page.  
WordPress automatycznie użyje `front-page.php`.

### 4. Menu nawigacyjne
`Wygląd > Menus > Utwórz menu > przypisz do lokalizacji "Menu Główne"`

### 5. Dane kontaktowe (Customizer)
`Wygląd > Dostosuj > Salon Win > Dane Kontaktowe`  
Wpisz: telefon, e-mail, adres, godziny, URL rezerwacji, linki społecznościowe, embed Google Maps.

---

## Opcjonalne wtyczki (zalecane)

| Wtyczka                    | Zastosowanie                        |
|---------------------------|-------------------------------------|
| WooCommerce               | Sklep online z winami               |
| Yoast SEO / Rank Math      | SEO i meta tagi                     |
| WP Rocket / LiteSpeed Cache| Optymalizacja wydajności            |
| Smush / ShortPixel         | Kompresja obrazów                   |
| Fancybox 4                 | Galeria lightbox (zastępuje wbudowaną) |
| Contact Form 7             | Dodatkowe formularze                |
| Newsletter                 | Zaawansowany newsletter             |
| WPML                       | Wielojęzyczność                     |
| Wordfence                  | Bezpieczeństwo                      |

---

## Struktura plików

```
salon-win/
├── style.css                  — Główny arkusz stylów + deklaracja motywu
├── functions.php              — Setup, CPT, meta, AJAX, WooCommerce
├── header.php                 — Header z nawigacją
├── footer.php                 — Stopka z linkami i social media
├── front-page.php             — Strona główna (hero, booking, sklep, opinie…)
├── index.php                  — Fallback (blog, archiwa, 404 treści)
├── single.php                 — Pojedynczy wpis
├── page.php                   — Strona statyczna
├── woocommerce.php            — Wrapper dla stron WooCommerce
├── 404.php                    — Strona błędu 404
├── assets/
│   ├── js/
│   │   └── main.js            — Cały JavaScript (bez zewnętrznych zależności)
│   └── images/                — Umieść tutaj zdjęcia (hero, wina, galeria)
└── template-parts/
    └── wine-placeholder-cards.php — Demo kart win
```

---

## Custom Post Types (wbudowane)

| CPT          | Cel                          | Pola meta                                             |
|-------------|------------------------------|-------------------------------------------------------|
| `degustacja` | Degustacje i eventy          | data, godzina, cena, miejsca, link do rezerwacji      |
| `wino`       | Karty win (bez WooCommerce)  | rocznik, region, szczep, cena, alkohol, etykieta      |
| `opinia`     | Opinie gości                 | imię, źródło (Google/TA), ocena, data, zweryfikowana  |
| `rezerwacja` | Wewnętrzne — formularze      | wszystkie dane z formularza                           |

---

## Rezerwacje online

Formularz rezerwacji na `front-page.php` wysyła dane przez **AJAX** do WordPress (`wp_ajax_salon_win_booking`).

Funkcja w `functions.php`:
1. Waliduje dane
2. Zapisuje rezerwację jako CPT `rezerwacja`
3. Wysyła e-mail do administratora
4. Wysyła potwierdzenie do gościa

Alternatywnie — podmień `sw_booking_url` w Customizerze na zewnętrzny link (Booksy, Calendly, SimplyBook itp.).

---

## Obrazy

Wymagane rozmiary (wgraj do `/assets/images/`):

| Plik                   | Wymiary    | Opis                      |
|------------------------|------------|---------------------------|
| `hero-wine.jpg`        | 1920×1080  | Tło hero                  |
| `about-salon.jpg`      | 900×1200   | Zdjęcie salonu             |
| `about-wine.jpg`       | 600×600    | Akcent                    |
| `gallery-1.jpg`        | 800×1200   | Galeria — główne (wyższe) |
| `gallery-2..5.jpg`     | 800×600    | Galeria — kafelki         |
| `wine-red-1..3.jpg`    | 600×800    | Wina czerwone             |
| `wine-white-1..2.jpg`  | 600×800    | Wina białe                |
| `wine-sparkling-1.jpg` | 600×800    | Wina musujące             |
| `wine-rose-1.jpg`      | 600×800    | Wina różowe               |
| `wine-natural-1.jpg`   | 600×800    | Wina naturalne            |
| `wine-placeholder.jpg` | 600×800    | Fallback dla wina         |

---

## Paleta kolorów

```css
--color-ink:         #1A0A00   /* Głęboka czerń mahoniowa */
--color-burgundy:    #8B1A2F   /* Burgund */
--color-burgundy-deep:#5C0F1E  /* Głęboki burgund */
--color-gold:        #C9A84C   /* Złoto */
--color-gold-light:  #E8D49A   /* Jasne złoto */
--color-cream:       #F5F0E8   /* Krem */
--color-mahogany:    #2C1810   /* Mahoń */
```

---

## Fonts

- **Cormorant Garant** — display headlines (Google Fonts)
- **Inter** — body text (Google Fonts)

---

## Wsparcie

Kontakt: salon-win@salon-win.pl

Strona: https://salon-win.pl

# Instrukcja wersjonowania i aktualizacji motywu WordPress Salon Win

Repozytorium motywu:

https://github.com/labdostepnosci/salon-win-theme-by-labdostepnosci

Motyw powinien być rozwijany, wersjonowany i aktualizowany w sposób kontrolowany. Każda zmiana musi być możliwa do sprawdzenia, opisania, wdrożenia i cofnięcia.

Celem tej instrukcji jest uporządkowanie pracy nad motywem WordPress oraz umożliwienie aktualizacji motywu bezpośrednio z GitHuba, np. przez wtyczkę Git Updater.

---

## 1. Główne zasady

Nie pracujemy bezpośrednio na produkcji.

Nie nadpisujemy plików motywu przez FTP bez wersjonowania.

Każda zmiana w motywie powinna mieć:

- osobny branch,
- opisowy commit,
- numer wersji,
- wpis w changelogu,
- test składni PHP,
- możliwość cofnięcia,
- tag Git dla wersji produkcyjnej.

Produkcja powinna korzystać wyłącznie z wersji stabilnych, czyli z brancha `main` albo z oznaczonych tagów wersji.

---

## 2. Struktura branchy

Przyjmujemy prosty układ branchy:

```text
main        - wersja stabilna, zgodna z produkcją
develop     - wersja robocza, opcjonalna
fix/...     - poprawki błędów
feature/... - nowe funkcje
release/... - przygotowanie wydania
