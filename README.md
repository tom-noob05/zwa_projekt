# Community Marketplace

Naším projektem je komunitní trh, na kterém mají uživatelé možnost nabízet a objednávat nabídky různých typů.
Nabídka může být nabízená služba: posekání zahrady, uklizení půdy, vyvenčení psa, ...
Nebo hotový produkt: sekačka, vysavač, použité triko, ...

Nabídky se dělí do kategorií a dají se řadit dle data publikace, nebo vyhledávat dle názvu.

Nepřihlášení uživatelé jsou omezeni pouze na prohlížení a vyhledávání nabídek.
Přihlášení uživatelé mohou nabídky navíc tvořit a kupovat.

Kvůli daným omezením v zadání projektu probíhá platba domluvou mezi prodávajícím a kupujícím uživatelem.

_Na projektu pracují: Tomáš Mamica, Tadeáš Krása_

---

## Dokumentace
- Produktová dokumentace (pro zadavatele): `documentation/product_documentation.md`
- Programátorská dokumentace (pro vývojáře): `documentation/programmer_documentation.md`
- Dokumentace generovaná z PHPDoc: `documentation/auto_generated_documentation.md`

Pokud chcete vygenerovat API dokumentaci HTML: nainstalujte `phpdocumentor` přes Composer a spusťte:

```
composer require --dev phpdocumentor/phpdocumentor
vendor/bin/phpdoc -d . -t documentation/api
```

