# Salon Win — Motyw WordPress

Elegancki, nowoczesny motyw WordPress dla butikowego salonu winiarskiego.  
Zaprojektowany zgodnie z trendami 2026: ciemna paleta, serif display font, asymetryczne layouty, animacje scroll-triggered.

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

https://github.com/labdostepnosci/salon-win

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
