<?php 
// tady je potreba kontolovat, jestli je v $_SESSION ulozeny nejaky $user_id 
// a jestli jo tak misto login buttonu ukaze treba jmeno + prijmeni prihlasenyho uzivatele 
// a nastavi pro nove zobrazeny text okdaz na pages/profile.php
?>


<nav class="navbar">
    <a  href="/">Community Marketplace</a>
    <select id="categories-dropdown">
        <option>Kategorie 0<option>
        <option>Kategorie 1<option>
        <option>Kategorie 2<option>    
    </select>
    <button id="login"><a href="/pages/login.php">Log In</a></butotn>
</nav>