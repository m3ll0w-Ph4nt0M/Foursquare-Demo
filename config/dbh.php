<?php

$db_name = "search_locale";
$db_user = "root";
$db_password = "";

$db = new PDO('mysql:host=localhost;dbname=' . $db_name . ';charset=UTF8', $db_user, $db_password);
$dbh = new PDO('mysql:host=localhost;dbname=' . $db_name . ';charset=UTF8', $db_user, $db_password);

?>