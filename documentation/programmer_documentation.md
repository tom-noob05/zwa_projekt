# Programátorská dokumentace (Technický manuál)

## Cíl dokumentu
Tato dokumentace je určena pro vývojáře zajišťující údržbu, úpravy nebo rozšiřování aplikace. Popisuje interní architekturu, datové toky a bezpečnostní standardy projektu.

---

## 1. Struktura projektu
Aplikace je postavena na modulární struktuře s oddělením logiky a statických souborů:

- `index.php` — Hlavní vstupní bod, veřejný výpis aktivních inzerátů.
- `config/`
  - `config.php` — Definice databázových konstant (`DB_HOST`, `DB_NAME`, atd.).
  - `init.php` — Bootstrap aplikace: inicializace session a PDO spojení (`$pdo`).
- `includes/`
  - `header.php`, `footer.php`, `navbar.php` — Globální layout prvky.
  - `functions.php` — Pomocné utility (přesměrování, správa session).
- `pages/` — Logika jednotlivých pohledů (Controllers/Views).
  - Obsahuje soubory pro správu inzerátů, uživatelských profilů a administraci.
- `public/` — Veřejně přístupné assety (CSS, JavaScript, složka `uploads` pro obrázky).
- `misc/` — Databázové skripty a schémata.

---

## 2. Databázový model
Systém využívá relační schéma v MySQL. Hlavní entity a jejich vztahy:



- `users`: Uživatelé s vazbou na `user_roles`.
- `offers`: Inzeráty (vazba na prodejce a kategorii). Stav definován sloupcem `status` (`active`/`sold`).
- `categories`: Stromová struktura kategorií (podpora `parent_id`).
- `bought_offers`: Historie transakcí propojující inzerát s kupujícím.

---

## 3. Klíčové technické procesy

### Autentizace a autorizace
- **Heslování:** Hesla jsou při registraci (`pages/register.php`) šifrována pomocí `password_hash()`. Ověření probíhá přes `password_verify()`.
- **Řízení přístupu:** Administrátorské stránky (`admin_*`) kontrolují `role_id` uživatele uložené v session.

### Transakční nákup (Purchase Flow)
V modulu `pages/offer_detail.php` je nákup realizován pomocí SQL transakce:
1. Ověření, zda je inzerát stále dostupný (`status = 'active'`).
2. Atomický update stavu na `sold`.
3. Vložení záznamu o nákupu do `bought_offers`.
4. V případě jakéhokoliv selhání následuje `ROLLBACK`.

### Stránkování v administraci
Výpis v `pages/admin_offer_list.php` používá server-side výpočet offsetu:
`$offset = ($page - 1) * $itemsPerPage;`
Data jsou dotazována pomocí `LIMIT` a `OFFSET` pro optimalizaci výkonu při větším počtu záznamů.

---

## 4. Bezpečnostní standardy a doporučení
Při dalším vývoji je nutné dodržovat tyto zásady:

* **Databáze:** Striktní používání **Prepared Statements** pro ochranu proti SQL Injection.
* **XSS Ochrana:** Všechny uživatelské výstupy musí být ošetřeny funkcí `htmlspecialchars()`.
* **Budoucí vylepšení (Backlog):**
    * Implementace **CSRF tokenů** pro všechny POST operace.
    * Zavedení **Rate Limitingu** na přihlašovací formuláře.
    * Přechod z přímého výpisu chyb (`echo`) na strukturované logování do souborů.

---

## 5. Konvence pro příspěvky (Standardy kódu)
- **Dokumentace:** Každá nová funkce musí obsahovat PHPDoc blok.
- **Validace:** Vstupy musí být validovány jak na straně klienta (UX), tak na straně serveru (Bezpečnost).


```php
/**
 * Příklad dokumentace funkce
 * @param int $id ID inzerátu
 * @return array|null Data inzerátu nebo null
 */
function getOfferById(int $id) { ... }
```
