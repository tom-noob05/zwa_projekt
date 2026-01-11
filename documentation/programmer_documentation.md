# Programátorská dokumentace (Detailní) 🔧

## Cíl dokumentu
Tato dokumentace je určena pro vývojáře, kteří chtějí porozumět interním mechanismům aplikace, provádět úpravy nebo přidávat nové funkce. Obsahuje mapu souborů, popis databáze, hlavní toky (auth, offers), JavaScript interakce, návrhy na zlepšení a bezpečnostní doporučení.

---

## Struktura projektu (soubory a účely)
- `index.php` — veřejná hlavní stránka, výpis inzerátů
- `config/` — konfigurace
  - `config.php` — DB konstanty (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`)
  - `init.php` — bootstrap: session + PDO $pdo
- `includes/` — sdílené části
  - `header.php`, `footer.php`, `navbar.php` — layout
  - `functions.php` — utility (session destroy, redirect helper)
- `pages/` — jednotlivé stránky (controllers + views)
  - `login.php`, `register.php`, `offer_create.php`, `offer_detail.php`, `offer_update.php`, `profile.php`, `admin_*`...
- `public/` — statické assety (CSS, JS, uploads)
  - `js/offer_details.js` — načítá detail inzerátu a provádí nákup přes fetch
- `misc/` — databázové skripty a diagramy (`db_create_script.sql`)
- `documentation/` — dokumentace (tady vytvořené soubory)

---

## Databázové schéma (souhrn)
Z hlavního SQL skriptu (`misc/db_create_script.sql`):
- `users` (id, username, password, jmeno, prijmeni, email, role_id)
- `user_roles` (id, name)
- `offers` (id, title, description, price, status, condition, seller_id, category_id)
- `categories` (id, name, parent_id)
- `bought_offers` (id, offer_id, user_id, bought_at)

Poznámka: `status` v `offers` používá hodnoty jako `active` nebo `sold`.

---

## Hlavní toky a algoritmy
### Registrace
- V `pages/register.php`:
  - Kontrola, zda uživatel s daným username nebo emailem neexistuje
  - Heslo se ukládá pomocí `password_hash()`
  - Role defaultně `2` (user)

### Přihlášení
- V `pages/login.php`:
  - Načtení uživatele podle username
  - Ověření hesla `password_verify()`
  - Nastavení `$_SESSION['user_id']` po úspěchu

### Nákup inzerátu (transakce)
- V `pages/offer_detail.php`: serverová POST handla `buy_offer_id`.
- Používá se transakce:
  - UPDATE offers SET status = 'sold' WHERE id = ? AND status = 'active'
  - Pokud se UPDATE neprovede (rowCount() === 0) vrátí se chyba
  - Dále se vloží záznam do `bought_offers`
  - Commit nebo rollback v případě chyby

### Frontend interakce
- `public/js/offer_details.js`:
  - Načítá JSON z endpointu `offers.php?id=...` (server-side endpoint vrací data inzerátu)
  - Naplní DOM prvky (`#offer-name`, `#offer-price` ...)
  - Postup nákupu: POST na stejnou stránku s `buy_offer_id`, zpracovává odpověď a přesměruje/nebo zobrazí chybu

### Administrace nabídek (`pages/admin_offer_list.php`) 🔧
- Účel: zobrazit administrátorovi přehled všech inzerátů s možností úprav a zobrazení detailu.
- Přístup: stránka kontroluje `$_SESSION['user_id']` a dotazuje `users` pro ověření `role_id == 1`.
- Stránkování:
  - Implementováno server‑side pomocí `LIMIT :limit OFFSET :offset` v SQL.
  - Proměnné: `$itemsPerPage` (počet položek na stránku), `$page` (z GET), `$offset = ($page - 1) * $itemsPerPage`.
  - Backend také spočítá celkový počet položek (`SELECT COUNT(*) FROM offers`) a vypočítá `$totalPages = ceil($totalOffers / $itemsPerPage)`.
- Výstup:
  - Data se vypisují escapovaná (`htmlspecialchars`) aby se snížilo riziko XSS.
  - Paginace se renderuje server‑side a poskytuje odkazy `?page=N` k navigaci.
- Doporučení pro rozšíření:
  - Přidat filtrování a řazení (např. podle stavu `status` nebo prodejce `seller_id`) pomocí parametrů GET (přidat sanitaci a bindované parametry).
  - Zavést AJAX page loader, pokud chcete rychlejší navigaci, ale vždy mít server‑side provedení jako fallback.
  - Přidat batch‑akce (smazat více záznamů najednou) s CSRF ochranou a transakcemi.

---

## Bezpečnostní a kvalitativní poznámky ⚠️
- Používání připravených dotazů (prepared statements) je správné — pokračujte v tom
- Chybí CSRF tokeny při POST formulářích (doporučeno přidat)
- Vstupy by měly být validovány na serveru i klientovi (např. cena jako číselná hodnota)
- Výstupy jsou v šablonách částečně escapené (`htmlspecialchars`) — udržujte konzistentní
- Doporučení: přidat rate limiting a lepší chybové hlášení (logování místo echo)

---

## Jak přidat novou funkci a dokumentovat ji
1. Přidejte PHPDoc blok nad novou funkcí/třídou (`@param`, `@return`, `@throws`, stručný popis).
2. Spusťte `vendor/bin/phpdoc -d . -t documentation/api` (po instalaci phpDocumentor přes Composer).
3. Ověřte výstup v `documentation/api`.

### Příklad dokumentační šablony
/**
 * Vrátí inzerát podle ID.
 *
 * @param int $id ID inzerátu
 * @return array|null Asociační pole s daty inzerátu nebo `null`
 */
function getOfferById(int $id)
{
    // ...
}

---

## Lokální vývoj a testování
- Přidejte `misc/db_create_script.sql` do testovací databáze a spusťte lokální instanci.
- Doporučení: vytvořit jednoduché PHPUnit testy pro kritické funkce (auth, nákup, data layer).

## Příspěvky a konvence
- Používejte prepared statements pro DB
- Pište krátké, jednoúčelové funkce
- Každá funkce by měla mít PHPDoc a minimální unit test

---

Pokud chcete, mohu:
- Projít repozitář a vložit PHPDoc bloky tam, kde chybí (mohu pokračovat a automaticky vytvořit rozsáhlejší API reference)
- Přidat jednoduché PHPUnit testy pro klíčové části (auth, nákup)
- Připravit skript pro automatické generování a nasazení dokumentace v CI (GitHub Actions)
