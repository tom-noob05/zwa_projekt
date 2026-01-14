# Produktová dokumentace (Návod pro uživatele a správce)

## 1. Shrnutí produktu
Tento projekt je webová aplikace pro inzerci a online prodej, která pokrývá kompletní životní cyklus inzerátu – od jeho vytvoření uživatelem až po finální nákup.

**Klíčové funkce:**
* **Správa účtů:** Registrace a bezpečné přihlašování uživatelů.
* **Inzertní systém:** Vytváření, editace a správa inzerátů včetně nahrávání fotografií.
* **Transakční modul:** Proces nákupu zboží a evidence prodejů.
* **Administrace:** Rozhraní pro moderaci obsahu a správu uživatelské báze.

---

## 2. Uživatelské postupy (UI Flows)

### A. Registrace a přístup
Uživatel se zaregistruje prostřednictvím formuláře na `/pages/register.php`. Po úspěšném vyplnění údajů je systémem přesměrován k přihlášení na `/pages/login.php`.

### B. Správa inzerce
Po přihlášení může uživatel v sekci `/pages/offer_create.php` vystavit nové zboží. Inzeráty se po uložení okamžitě zobrazí ve veřejném katalogu a na profilu prodejce.

### C. Realizace nákupu
V detailu konkrétního inzerátu (`/pages/offer_detail.php`) je k dispozici tlačítko **BUY**. Po potvrzení transakce je inzerát označen jako prodaný a přesunut do archivu nákupů (`bought_offers`).

---

## 3. Administrace systému
Pro správu platformy slouží dedikované moduly:
* **Správa inzerátů:** `/pages/admin_offer_list.php` (možnost úpravy nebo smazání jakéhokoliv inzerátu).
* **Správa uživatelů:** `/pages/admin_user_list.php` (správa uživatelských účtů a jejich oprávnění).

---

## 4. Instalace a nasazení

### Technické požadavky
* **PHP:** verze 8.x (minimálně 7.4)
* **Databáze:** MySQL
* **Webserver:** Apache / Nginx

### Postup instalace
1.  **Zdrojové kódy:** Nakopírujte obsah repozitáře do kořenového adresáře webserveru (např. `htdocs`).
2.  **Databáze:** Importujte databázové schéma ze souboru `misc/db_create_script.sql`.
3.  **Konfigurace:** Upravte soubor `config/config.php` a doplňte přístupové údaje k vaší databázi.
4.  **Spuštění:** Otevřete adresu projektu v prohlížeči.

---

## 5. Bezpečnost a kvalita kódu
* **SQL Injection:** Implementována ochrana pomocí **Prepared Statements**.
* **Zabezpečení hesel:** Hesla jsou ukládána pomocí moderního algoritmu `password_hash`.
* **XSS Ochrana:** Výstupy v šablonách jsou ošetřeny funkcí `htmlspecialchars`.

---

## 6. Řešení běžných problémů
* **Chyba při připojení k DB:** Zkontrolujte správnost údajů v `config/config.php`.
* **Nejde se přihlásit:** Ověřte, zda uživatel v databázi skutečně existuje a zda jeho heslo odpovídá uloženému hashi.