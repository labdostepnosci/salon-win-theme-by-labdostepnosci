# Facebook -> WordPress: aktualności od 1 stycznia

Fanpage: `https://www.facebook.com/SalonWinJaslo`

Cel: pobrać aktualności z fanpage od `2026-01-01` i zamienić je na wpisy WordPress.

## Ważne

Bez dostępu administratora do fanpage nie da się tego zrobić pewnie i stabilnie. Publiczne scrapowanie Facebooka jest niestabilne, często wymaga logowania i może łamać zasady platformy. Najlepsza ścieżka to eksport/API z poziomu osoby, która ma dostęp do strony.

## Najprostszy wariant: CSV

1. Otwórz plik `facebook-posts-template.csv`.
2. Dla każdego posta od `2026-01-01` uzupełnij wiersz:
   - `date` - data posta, np. `2026-01-15`
   - `title` - tytuł wpisu WordPress, może być dopisany ręcznie
   - `message` - treść posta z Facebooka
   - `permalink` - link do posta
   - `image_url` - link do obrazka, jeśli jest
   - `categories` - np. `Aktualności; Facebook`
   - `tags` - np. `Salon Win; Jasło; Slow life`
   - `status` - najlepiej `draft`
3. Zapisz jako CSV UTF-8.
4. Uruchom konwerter:

```powershell
& "C:\Users\Lenovo\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe" `
  "outputs\facebook_to_wordpress_wxr.py" `
  --input "outputs\facebook-posts-template.csv" `
  --output "outputs\facebook-posty-wordpress-import.xml" `
  --since "2026-01-01" `
  --site-url "https://salon-win.pl"
```

5. Powstały plik `facebook-posty-wordpress-import.xml` importujesz w WordPress:
   `Narzędzia -> Import -> WordPress`.

## Wariant lepszy: Graph API

Ten wariant wymaga dostępu administratora fanpage i Page Access Token.

1. Wejdź do Meta for Developers / Graph API Explorer.
2. Wybierz aplikację i konto, które ma dostęp do fanpage.
3. Wygeneruj Page Access Token z uprawnieniami do odczytu treści strony.
4. Pobierz posty endpointem w stylu:

```text
/{PAGE_ID}/posts
  ?fields=id,message,story,created_time,permalink_url,full_picture,attachments{media_type,media,url,title,description}
  &since=2026-01-01
  &limit=100
```

5. Zapisz odpowiedź jako JSON, np. `facebook-posts.json`.
6. Uruchom konwerter:

```powershell
& "C:\Users\Lenovo\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe" `
  "outputs\facebook_to_wordpress_wxr.py" `
  --input "facebook-posts.json" `
  --output "outputs\facebook-posty-wordpress-import.xml" `
  --since "2026-01-01" `
  --site-url "https://salon-win.pl"
```

## Co zrobi konwerter

- pominie posty sprzed `2026-01-01`,
- utworzy wpisy WordPress jako `draft`,
- doda kategorie i tagi,
- zachowa link do oryginalnego posta na Facebooku,
- doda meta pola:
  - `_facebook_permalink`,
  - `_facebook_image_url`,
  - `_yoast_wpseo_metadesc`,
  - `rank_math_description`.

## Po imporcie

1. Wejdź w `Wpisy -> Wszystkie wpisy`.
2. Otwórz szkice.
3. Popraw tytuły, jeśli automatycznie utworzone są zbyt długie.
4. Dodaj obrazki wyróżniające ręcznie.
5. Opublikuj wybrane wpisy.

## Uwaga o zdjęciach

WordPress importer nie pobierze automatycznie zdjęć z Facebooka jako media, bo Facebookowe linki do obrazków często wygasają albo wymagają uprawnień. Konwerter zapisuje `image_url` w polu meta, żeby łatwo odnaleźć źródło i ręcznie ustawić obrazek wyróżniający.
