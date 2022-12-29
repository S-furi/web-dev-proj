    <div class="wrapper">
        <h1>Creazione Post</h1>
        <p><?php if (isset($_GET["err"])) echo $_GET["err"]; ?></p>
        <form action="api/api-post.php?action=0" method="post" enctype="multipart/form-data">
            <ul>
              <li>
                  <label for="title">Titolo Post</label><input type="text" name="title" id="title" required />
              </li>
              <li>
                  <label for="image">Foto</label><input type="file" name="photo" id="image" required />
              </li>
              <li>
                  <label for="description">Descrizione</label><textarea id="description" name="description" required></textarea>
              </li>
              <li>
                    <label for="location">Luogo</label>
                    <div class="location-search">
                        <span class="material-symbols-outlined"></span><input type="text" id="location" name="location" required />
                        <button type="button" class="btn btn-primary" onclick="osmSearch()">Check</button>
                    </div>
              </li>
              <li>
                  <label for="event-datetime">Data e Ora dell'Evento</label><input type="datetime-local" name="event-datetime" id="event-datetime" required />
              </li> 
            </ul>
          <input type="button" name="cancel-button" value="Annulla" class="btn" onclick="window.location.href='index.php'" />
          <input type="button" name="creation-button" value="Crea" class="btn btn-primary" onclick="form.submit()"/>
        </form> 
        <script src="js/post-creation.js"></script>
    </div>
