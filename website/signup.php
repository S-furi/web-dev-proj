<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

$templateParams["Titolo"] = "Brogram - Sign Up";
$templateParams["nome"] = "signup-form.php";

if (isset($_POST['mail'], $_POST['password'], $_POST['username'])) {
  $email = $_POST['mail'];
  $password = $_POST['password'];
  $username = $_POST['username'];

  if (insertNewUser($email,$username, $password, $mysqli)) {
    echo "utente inserito";
    // REDIRECT SULLA HOME
  } else {
    echo "errore durante l'inserimento";
  }
}

require("template/base.php");

?>
