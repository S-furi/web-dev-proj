<?php
require_once('../database/db_connect.php');
require_once('../database/db_functions.php');

sec_session_start();

if (!isStillLoggedIn($mysqli)) {
    header("Location: login.php");
    echo "Sessione scaduta";
}

$user = getUser($_SESSION['user_id'], $mysqli);

header("Content-Type: application/json");
echo json_encode($user);