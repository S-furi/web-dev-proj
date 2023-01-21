<?php 
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

  list($err, $imgPath) = uploadImage(UPLOAD_POST_DIR, $propic);

  if ($err != 0) {
    if (checkUserInfoExists($usrId, $mysqli) == 1) {
      if (updateProfileInfo($usrId, $bio, $imgPath, $mysqli)) {
        echo "gasa";
      } else {
        echo "oh no";
      }
    } else {
      if (insertNewUserInfo($usrId, $bio, $imgPath, $mysqli)) {
        echo "inserito";
      } else {
        echo "nooooooo";
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
                  <label for="title">Immagine profilo</label><input type="file" name="propic" id="propic" required />
              </li>
              <li>
                  <label for="bio">Biografia</label><textarea name="bio" id="bio" required><?php echo $bio; ?></textarea>
              </li>
            </ul>
          <input type="button" name="cancel-button" value="Annulla" class="btn" onclick="window.location.href='index.php'" />
          <input type="button" name="creation-button" value="Modifica" class="btn btn-primary" onclick="form.submit()"/>
        </form> 
    </div>