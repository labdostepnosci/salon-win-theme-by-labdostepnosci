# Release 0.0.2

Dodano własny mechanizm aktualizacji motywu `Salon Win by labdostepnosci` z GitHub Releases.

## Added
- Własny updater motywu oparty o GitHub Releases.
- Obsługa najnowszego release z repozytorium `labdostepnosci/salon-win`.
- Obsługa paczki `salon-win-theme-X.Y.Z.zip`.
- Cache zapytań do GitHub API.
- Opcjonalna obsługa tokena `SALON_WIN_GITHUB_TOKEN`.

## Deployment
- Utworzyć GitHub Release z tagu `v0.0.2`.
- Dołączyć paczkę `salon-win-theme-0.0.2.zip`.
- Wersję `0.0.2` zainstalować jednorazowo ręcznie albo przez Git Updater.
- Od wersji `0.0.2` sprawdzić w WordPressie wykrywanie kolejnych aktualizacji.
