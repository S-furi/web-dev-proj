<?php
require_once("bootstrap.php");
require_once('../utils/utils_functions.php');

$templateParams["Titolo"] = "Brogram - Modifica Profilo";
$templateParams["user"] = getUser(intval($_SESSION['user_id']), $mysqli);
$templateParams["suggested_users"] = getSuggestedUser($_SESSION["user_id"], $mysqli);
$templateParams["template_name"] = "edit-profile-template.php";
$templateParams["mysqli"] = $mysqli;

$error = "";

$userInfo = getUserInfo($_SESSION['user_id'], $mysqli);
if (empty($userInfo)) {
  $bio = "";
} else {
  $bio = $userInfo[0]['bio'];
}

if (isset($_POST['bio']) && ($_FILES['propic']['error'] == 0 || $_FILES['propic']['error'] == UPLOAD_ERR_NO_FILE)) {
  $usrId = $_SESSION['user_id'];
  $bio = $_POST['bio'];
  $username = getUser($usrId, $mysqli)['username'];
  $propic_dir = IMG_DIR . $username . "/propic/";
  if ($_FILES['propic']['error'] == 0) {
    $propic = $_FILES['propic'];
    list($err, $imgPath) = uploadImage($propic_dir, $propic);
  } else {
    if (checkUserInfoExists($_SESSION['user_id'], $mysqli)) {
      $imgPath = getUserInfo($_SESSION['user_id'], $mysqli)[0]['profileImg'];
    } else {
      $imgPath = 'img/no-profile-pic.png';
    }
    $err = 1;
  }

  if (!is_dir($propic_dir)) {
    mkdir($propic_dir, 0777, true);
  } 

  if ($err != 0) {
    if (checkUserInfoExists($usrId, $mysqli) == 1) {
      if (!updateProfileInfo($usrId, $bio, $imgPath, $mysqli)) {
        $error = "C'è stato un errore nell'aggiornamento del profilo, si prega di riprovare più tardi.";
      } else {
        $files = glob($propic_dir . '/*');
        foreach ($files as $file) {
          if (basename($file) !== $imgPath) {
            unlink($file);
          }
        }
        header("Location: personal-profile.php");
      }
    } else {
      if (!insertNewUserInfo($usrId, $bio, $imgPath, $mysqli)) {
        $error = "C'è stato un errore nell'inserimento del profilo, si prega di riprovare più tardi.";
      }
    }
  }
}

require("template/base.php");
?>
