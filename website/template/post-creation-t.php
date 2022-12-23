    <div class="wrapper">
        <h1>Creazione Post</h1>
        <p><?php if (isset($_GET["err"])) echo $_GET["err"]; ?></p>
        <form action="api/api-post.php" method="post" enctype="multipart/form-data">
            <ul>
              <li>
                  <label for="title">Titolo Post</label><input type="text" name="title" id="title" required />
              </li>
              <li>
                  <label for="image">Foto</label><input type="file" name="photo" id="image" required />
              </li>
              <li>
                  <label for="description">Descrizione</label><input type="text" id="description" name="description" required/>
              </li>
              <li>
                  <label for="location">Luogo</label><input type="text" id="location" name="location" required />
              </li>
              <li>
                  <label for="event-datetime">Data e Ora dell'Evento</label><input type="datetime-local" name="event-datetime" id="event-datetime" required />
              </li> 
            </ul>
          <input type="button" name="cancel-button" value="Annulla" class="btn" onclick="window.location.href='index.php'" />
          <input type="button" name="creation-button" value="Crea" class="btn btn-primary" onclick="form.submit()"/>
        </form> 
    </div>
