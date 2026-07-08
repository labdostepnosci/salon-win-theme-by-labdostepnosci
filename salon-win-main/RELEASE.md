# Wydania motywu Salon Win

Aktualna wersja motywu `Salon Win by labdostepnosci` to `0.0.9`. Numer wersji
jest ustawiany w nagłówku pliku `style.css` i musi być zgodny z SemVer.

## Publikowanie wersji przez GitHub Releases

Każda wersja motywu musi mieć:

- numer wersji w `style.css`,
- wpis w `CHANGELOG.md`,
- commit,
- tag Git w formacie `vX.Y.Z`,
- GitHub Release utworzony automatycznie przez GitHub Actions po wypchnięciu taga,
- paczkę ZIP motywu dołączoną automatycznie jako asset Release.

Zmiany przeznaczone dla WordPressa należy publikować jako kompletne wydania.
Sam commit wypchnięty na `main` nie jest wydaniem motywu.

## Nazewnictwo paczki ZIP

Workflow tworzy paczkę ZIP wymaganą dla release:

```text
salon-win-X.Y.Z.zip
```

Dla zgodności z obecnym updaterem motywu workflow dołącza także drugi asset:

```text
salon-win-theme-X.Y.Z.zip
```

Przykłady:

```text
salon-win-0.0.9.zip
salon-win-theme-0.0.9.zip
```

W katalogu głównym archiwum musi znajdować się jeden katalog `salon-win/`,
a w nim pliki motywu. Nie należy publikować paczek z przypadkowym katalogiem,
takim jak:

```text
salon-win-main/
salon-win-0.0.2/
source/
dist/
motyw/
nowe/
```

Automatyczne archiwa „Source code” tworzone przez GitHub nie gwarantują
wymaganej nazwy katalogu głównego. Dlatego asset ZIP motywu przygotowuje
workflow `.github/workflows/release.yml`.

Paczka produkcyjna nie może zawierać `.git/`, zbędnego `.github/`,
`node_modules/`, niewymaganego `vendor/`, plików tymczasowych, lokalnych
ustawień IDE, `.DS_Store` ani `Thumbs.db`.

## Publikowanie nowej wersji od 0.0.7

1. Utworzyć branch:

   ```bash
   git checkout main
   git pull origin main
   git checkout -b fix/nazwa-poprawki
   ```

2. Wprowadzić zmiany.

3. Sprawdzić składnię PHP:

   ```bash
   find . -name "*.php" -print0 | xargs -0 -n1 php -l
   ```

4. Podbić wersję w `style.css`, na przykład:

   ```text
   Version: 0.0.9
   ```

5. Uzupełnić `CHANGELOG.md`:

   ```markdown
   ## [0.0.9] - YYYY-MM-DD
   ```

6. Zrobić commit:

   ```bash
   git add .
   git commit -m "Release version 0.0.9"
   ```

7. Zmergować zmiany do `main`:

   ```bash
   git checkout main
   git pull origin main
   git merge fix/nazwa-poprawki
   git push origin main
   ```

8. Utworzyć i wypchnąć tag:

   ```bash
   git tag -a v0.0.9 -m "Release 0.0.9"
   git push origin v0.0.9
   ```

9. Po wypchnięciu taga workflow GitHub Actions automatycznie:

   - przygotuje katalog `salon-win/` z zawartości `salon-win-main/`,
   - spakuje motyw do `salon-win-0.0.9.zip`,
   - przygotuje kompatybilny asset `salon-win-theme-0.0.9.zip`,
   - utworzy GitHub Release,
   - dołączy paczki ZIP jako assety release.

Nie należy ręcznie wrzucać ZIP-a, jeśli workflow zakończy się poprawnie.

10. W WordPressie uruchomić aktualizację motywu z GitHub Release.

## Wydanie 0.0.1

Tag i GitHub Release `v0.0.1` już istnieją. Do Release jest dołączona paczka
`salon-win-theme-0.0.1.zip`.

## Własny updater GitHub Releases

Motyw zawiera własny updater, który sprawdza najnowsze wydanie GitHub Releases
w repozytorium:

<https://github.com/labdostepnosci/salon-win-theme-by-labdostepnosci>

Updater:

- pobiera najnowszy release z GitHub API,
- porównuje tag release z wersją motywu w `style.css` za pomocą
  `version_compare()`,
- pokazuje aktualizację w panelu WordPress,
- preferuje paczkę `salon-win-theme-X.Y.Z.zip` dołączoną do Release,
- używa `zipball_url` wyłącznie jako fallback,
- cache’uje poprawną odpowiedź przez 6 godzin w site transiencie
  `salon_win_github_release`,
- cache’uje błąd API przez 30 minut, aby awaria GitHuba nie powodowała zapytań
  przy każdym ładowaniu,
- nie wymaga Git Updatera dla publicznego repozytorium.

Fallback `zipball_url` nie gwarantuje katalogu głównego `salon-win/`, dlatego
każde wydanie produkcyjne powinno zawierać przygotowany asset ZIP.

Dla prywatnego repozytorium należy dodać token z prawem odczytu zawartości
repozytorium w `wp-config.php`:

```php
define( 'SALON_WIN_GITHUB_TOKEN', 'TOKEN' );
```

Tokena nie wolno zapisywać w repozytorium. Updater pobiera prywatny asset
przez GitHub API z nagłówkiem `Authorization`, obsługuje odpowiedź `200` oraz
przekierowanie `302`, a token nie jest przekazywany do docelowego hosta pliku.

Wersja `0.0.1` nie zawiera własnego updatera. Wydanie `0.0.2` trzeba więc
zainstalować jednorazowo ręcznie albo przez Git Updater. Po instalacji `0.0.2`
własny updater będzie wykrywał kolejne wydania, począwszy od `0.0.3`.

## Repozytorium publiczne i prywatne

Asset z publicznego repozytorium można pobrać bez tokena. W repozytorium
prywatnym własny updater uwierzytelnia żądania tokenem
`SALON_WIN_GITHUB_TOKEN`. Token musi mieć uprawnienie do odczytu zawartości
repozytorium.

## Rollback

Jeżeli wersja `0.0.2` powoduje błąd, nie cofamy numeru wersji do `0.0.1`.
Należy przygotować nową wersję naprawczą, na przykład `0.0.3`, która odwraca
problematyczną zmianę.

Poprawny schemat:

- `0.0.1` — wersja poprzednia,
- `0.0.2` — wersja z błędem,
- `0.0.3` — rollback albo poprawka błędu.

## Aktualizacja w WordPressie

Po opublikowaniu GitHub Release należy:

1. wejść do panelu WordPress,
2. sprawdzić dostępność aktualizacji motywu,
3. wykonać aktualizację,
4. wyczyścić cache,
5. sprawdzić stronę główną,
6. sprawdzić `/wp-admin/`,
7. sprawdzić kluczowe podstrony,
8. sprawdzić log błędów.

Obecne nagłówki zgodne z Git Updaterem pozostają w `style.css`, ale od wersji
`0.0.2` motyw ma również własny mechanizm aktualizacji z GitHub Releases.
