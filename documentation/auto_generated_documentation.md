# Automaticky generovaná dokumentace (PHPDoc)

## Přehled ✅
Tato dokumentace popisuje veřejné rozhraní (funkce, třídy, globální proměnné) projektu **ZWA**. Vzniká z PHPDoc bloků přímo ve zdrojovém kódu a je určena pro programátory, kteří potřebují rychlý přehled o API a popis chování jednotlivých funkcí.

## Jak to funguje
- Přidejte PHPDoc komentáře nad soubory a funkcemi (př. `@param`, `@return`, `@throws`, `@package`).
- Spusťte generátor (phpDocumentor, Doxygen nebo jiný) nad kořenem projektu.
- Výstup bude statické HTML (nebo jiný formát), snadno prohlížitelný v prohlížeči.

## Doporučené nástroje a příkazy 🔧
- Instalace pomocí Composer (doporučeno):

  composer require --dev phpdocumentor/phpdocumentor

  Generování dokumentace:

  vendor/bin/phpdoc -d . -t documentation/api

- Nebo jednorázově pomocí PHAR:

  wget https://phpdoc.org/phpDocumentor.phar -O phpDocumentor.phar
  php phpDocumentor.phar -d . -t documentation/api

Po vygenerování najdete HTML v `documentation/api/index.html`.

## Co je aktuálně zdokumentováno
Přidal jsem PHPDoc bloky pro klíčové utility v `includes/functions.php` a pro bootstrap v `config/init.php`. Příklad funkcí:

- `testFunction(string $string) : void` — debugovací helper (dočasný).
- `destroySession() : void` — bezpečně projde a odstraní session + cookie.
- `redirectIfLoggedIn() : void` — přesměruje na `/index.php`, pokud je uživatel přihlášen.

## Doporučený styl dokumentace
- Každá funkce/třída souboru by měla mít před sebou krátký popis (1-2 věty).
- Použijte `@param` pro všechny parametry a `@return` vždy, i když to je `void`.
- U chybových stavů přidejte `@throws`.

### Příklad bloku

/**
 * Načte uživatele podle id.
 *
 * @param int $id ID uživatele
 * @return array|null Uživatelská data nebo `null`, pokud neexistuje
 */
function getUserById(int $id)
{
    // ...
}

## Další kroky
- Přidejte PHPDoc bloky k dalším funkcím a hlavním stránkám (controllerům) v `pages/`.
- Spusťte `phpdoc` a prohlédněte výstup.
- Přidejte generovanou dokumentaci do repozitáře (např. `documentation/api`), nebo hostujte odděleně.

---

Poznámka: Pokud chcete, mohu automaticky sloučit výpis všech funkcí a tříd do přehledné reference a vygenerovat příkazy pro vaše CI/CD, které budou dokumentaci pravidelně aktualizovat.