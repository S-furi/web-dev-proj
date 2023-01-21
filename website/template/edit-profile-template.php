<?php 
$error = "";

$userInfo = getUserInfo($_SESSION['user_id'], $mysqli);
if (empty($userInfo)) {
  $bio = "";
} else {
  $bio = $userInfo[0]['bio'];
}

if (isset($_POST['bio']) && $_FILES['propic']['error'] == 0) {
  $usrId = $_SESSION['user_id'];
  $bio = $_POST['bio'];
  $propic = $_FILES['propic'];
  $username = getUser($usrId, $mysqli)['username'];
  $propic_dir = IMG_DIR . $username . "/propic/";

  if (!is_dir($propic_dir)) {
    mkdir($propic_dir, 0777, true);
  }

  list($err, $imgPath) = uploadImage($propic_dir, $propic);

  if ($err != 0) {
    if (checkUserInfoExists($usrId, $mysqli) == 1) {
      if (!updateProfileInfo($usrId, $bio, $imgPath, $mysqli)) {
        $error = "C'è stato un errore nell'aggiornamento del profilo, si prega di riprovare più tardi.";
      } else {
        $files = glob($propic_dir . '/*');
        foreach ($files as $file) {
          if (basename($file) !== $propic['name']) {
            unlink($file);
            echo "eliminato " . $file . " perchè diverso da " . $propic['name'];
          }
        }
      }
    } else {
      if (!insertNewUserInfo($usrId, $bio, $imgPath, $mysqli)) {
        $error = "C'è stato un errore nell'inserimento del profilo, si prega di riprovare più tardi.";
      }
    }
  }
}

?>    
    
    <div class="wrapper">
        <h1>Modifica Profilo</h1>
        <p><?php if (isset($_GET["err"])) echo $_GET["err"]; ?></p>
        <form action="" method="post" enctype="multipart/form-data">
            <ul>
              <li>
                  <label for="propic">Immagine profilo</label><input type="file" name="propic" id="propic" required />
              </li>
              <li>
                  <label for="bio">Biografia</label><textarea name="bio" id="bio" required><?php echo $bio; ?></textarea>
              </li>
            </ul>
          <input type="button" name="cancel-button" value="Annulla" class="btn" onclick="window.location.href='index.php'" />
          <input type="button" name="creation-button" value="Modifica" class="btn btn-primary" onclick="form.submit()"/>
          
          <div class="error">
            <?php echo $error; ?>
          </div>
        </form> 
    </div>