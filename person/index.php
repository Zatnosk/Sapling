<?php
require_once __DIR__."/../access.php";
Access::logged_in();
require_once __DIR__."/../layout.php";
open_html();
menu();
?>
<main>Profiles are not yet implemented.</main>
<?
close_html();
?>