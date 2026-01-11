# Dokumentace projektu ZWA

## Úvod
Tato dokumentace popisuje projekt ZWA, který je webovou aplikací pro správu nabídek a uživatelů.

## Struktura projektu
Projekt má následující strukturu:

```
index.php
README.md
test_db.php
config/
	config.ex.php
	config.php
	init.php
documentation/
	notes.txt
includes/
	footer.php
	functions.php
	header.php
	navbar.php
misc/
	db_create_script.sql
	db_model_final.mwb
	db_model_final.mwb.bak
	EER.mwb
	EER.mwb.bak
	struktura.txt
	zwa_db_navrh.drawio
	zwa_projekt_db.drawio
pages/
	admin_offer_list.php
	admin_user_list.php
	login.php
	logout.php
	offer_create.php
	offer_detail.php
	offer_update.php
	offers.php
	profile_edit.php
	profile.php
	register.php
	sign_in.php
public/
	js/
		admin_offer_list.js
		admin_user_list.js
		index.js
		login.js
		navbar.js
		offer_details.js
		register.js
	styles/
		admin_offer_list.css
		admin_user_list.css
		home.css
		login.css
		navbar.css
		offer_create.css
		offer_detail.css
		offer_update.css
		profile_edit.css
		profile.css
		register.css
		sign_in.css
	uploads/
```

## Popis souborů
- **index.php**: Hlavní vstupní bod aplikace.
- **README.md**: Základní informace o projektu.
- **test_db.php**: Skript pro testování databáze.
- **config/**: Konfigurační soubory aplikace.
- **documentation/**: Dokumentace a poznámky.
- **includes/**: Zahrnuje soubory pro hlavičku, patičku a funkce.
- **misc/**: Různé soubory, včetně skriptů pro databázi a diagramů.
- **pages/**: Různé stránky aplikace.
- **public/**: Veřejné soubory, včetně JavaScriptu a stylů.

## Instalace
1. Klonujte repozitář: `git clone https://github.com/tom-noob05/zwa_projekt.git`
2. Nainstalujte potřebné závislosti.
3. Nakonfigurujte databázi podle souboru `db_create_script.sql`.

## Použití
Popis, jak používat aplikaci a jaké funkce nabízí.

## Přispívání
Pokyny pro přispívání do projektu.

## Licencování
Informace o licenci projektu.
