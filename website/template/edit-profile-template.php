    <div class="wrapper">
        <h1>Modifica Profilo</h1>
        <p><?php if (isset($_GET["err"])) echo $_GET["err"]; ?></p>
        <form action="edit-profile.php" method="post" enctype="multipart/form-data">
            <ul>
              <li>
                  <label for="propic">Immagine profilo</label><input type="file" name="propic" id="propic" />
              </li>
              <li>
                  <label for="bio">Biografia</label><textarea name="bio" id="bio" required><?php echo $bio; ?></textarea>
              </li>
            </ul>
          <input type="button" name="cancel-button" value="Annulla" class="btn" onclick="window.location.href='index.php'" />
          <input type="submit" name="creation-button" value="Modifica" class="btn btn-primary" />
          
          <div class="error">
            <?php echo $error; ?>
          </div>
        </form> 
    </div>
