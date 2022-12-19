function fillMain() {
    const form = `
            <h1>Creazione Post</h1>
            <p></p>
            <form action="post-creation.php" method="post" enctype="multipart/form-data">
                <ul>
                  <li>
                      <label for="title">Titolo Post</label><input type="text" name="title" id="title" />
                  </li>
                  <li>
                      <label for="image">Foto</label><input type="file" name="photo" id="image" />
                  </li>
                  <li>
                      <label for="description">Descrizione</label><input type="text" id="description" name="description"/>
                  </li>
                  <li>
                      <label for="location">Luogo</label><input type="text" id="location" name="location" />
                  </li>
                  <li>
                      <label for="event-datetime">Data e Ora dell'Evento</label><input type="datetime-local" name="event-datetime" id="event-datetime" />
                  </li> 
                </ul>
              <input type="button" name="cancel-button" value="Annulla" class="btn" />
              <input type="button" name="creation-button" value="Crea" class="btn btn-primary"/>
            </form> `;

    document.querySelector("main .middle").innerHTML = form;
}

fillMain();

// cancel button, redirects to home
document.querySelector("input[name=cancel-button]")
.addEventListener('click', () => document.location.href = "index.php");

// before submitting the form, check fields
document.querySelector("input[name=creation-button]")
    .addEventListener('click', function () {
        const post_params = {
            "title": document.querySelector("input#title").value,
            "photo": document.querySelector("input#image").value,
            "description": document.querySelector("input#description").value,
            "location": document.querySelector("input#location").value,
            "event-datetime": document.querySelector("input#event-datetime").value,
        }

        if (checkForm(post_params)) {
            document.querySelector(".middle form").submit();
        } else {
            document.querySelector(".middle p").innerText = "Riempi tutti i campi.";
        }
    });

function checkForm(post_params) {
  let err = true;
  for (const [key, value] of Object.entries(post_params)) {
    if (value == "") {
      err = false;
    }
  }
  return err;
}
