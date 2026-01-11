# Produktová dokumentace (Návod pro zadavatele) ✅

## Shrnutí produktu
ZWA je jednoduchá webová aplikace pro inzerci a prodej věcí mezi uživateli. Umožňuje:
- Registraci a přihlášení uživatelů
- Vytváření, editaci a mazání inzerátů
- Zobrazení detailu inzerátu a nákup (označení jako "prodáno")
- Základní administraci (seznam inzerátů a uživatelů)

## Cílová skupina
- Koncoví uživatelé, kteří chtějí prodávat nebo nakupovat zboží
- Zadavatel / administrátor, který spravuje data a uživatele

## Rychlý návod pro uživatele (UI flows) 🔁
1. Registrace
   - Otevřít: /pages/register.php
   - Vyplnit jméno, příjmení, email, uživatelské jméno a heslo
   - Po registraci je uživatel přesměrován na přihlášení

2. Přihlášení
   - Otevřít: /pages/login.php
   - Zadat uživatelské jméno a heslo
   - Po úspěchu: přesun na domovskou stránku (`index.php`)

3. Vytvoření inzerátu
   - Otevřít: /pages/offer_create.php
   - Vyplnit název, popis, cenu, volitelně nahrát obrázek
   - Po uložení: inzerát se zobrazí v nabídce a na profilu uživatele

4. Nákup inzerátu
   - Otevřít detail inzerátu (/pages/offer_detail.php?id=123)
   - Kliknout na tlačítko BUY
   - Po úspěchu je inzerát označen jako prodaný a záznam vložen do `bought_offers`

## Administrace
- `pages/admin_offer_list.php` — výpis všech inzerátů s možností upravit/smazat
- `pages/admin_user_list.php` — správa uživatelů (úprava, smazání)

## Jak pořizovat a kde přidat snímky obrazovky (Screenshots) 🖼️
Doporučené snímky a názvy:
- `docs/images/home.png` — hlavní stránka a výpis inzerátů
- `docs/images/register.png` — registrační formulář
- `docs/images/create_offer.png` — formulář vytvoření inzerátu
- `docs/images/offer_detail.png` — detail inzerátu

Vložím tyto snímky do repozitáře, pokud mi je dodáte nebo je mohu vytvořit při běhu aplikace (potřebuju přístup a instrukci, které pohledy chcete zachytit).

## Instalace & nasazení (zestručněno) 🛠️
Požadavky:
- PHP 8.x (nebo 7.4+)
- MySQL nebo MariaDB
- Webserver (Apache / Nginx) s nastaveným dokumentovým kořenem

Kroky:
1. Nakopírujte obsah repozitáře do www rootu (např. `c:\xampp\htdocs\zwa_projekt`).
2. Vytvořte databázi a spusťte `misc/db_create_script.sql`.
3. Upravte `config/config.php` s přihlašovacími údaji k DB.
4. Otevřete aplikaci v prohlížeči.

## Řešení běžných problémů (FAQ)
- Problém: "Chyba při připojení k DB" — zkontrolujte `config/config.php` a správnost údajů
- Problém: "Nejde se přihlásit" — zkontrolujte, zda uživatel existuje v tabulce `users` a heslo bylo hashováno funkcí `password_hash`

---

Pokud chcete, připravím profesionální PDF návod s vloženými snímky obrazovky a krátkými popisy jednotlivých obrazovek.