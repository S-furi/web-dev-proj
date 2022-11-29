<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

$templateParams["Titolo"] = "Brogram - Login";
$templateParams["nome"] = "login-form.php";

if (isset($_POST['email'], $_POST['password'])) {
  if (login($_POST['email'], $_POST['password'], $mysqli)) {
    echo "login avvennuto con successo";
  } else {
    echo "login fallito!";
  }
}

require("template/base.php");

?>
