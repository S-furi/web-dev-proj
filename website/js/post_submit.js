// todo: inject main code for displaying post form
function fillMain() {

  const form = `
            <h1>Creazione Post</h1>
            <form action="POST">
                <ul>
                  <li>
                      <label for="title">Titolo Post</label><input type="text" name="title" id="title" />
                  </li>
                  <li>
                      <label for="photo">Foto</label><input type="file" name="photo" id="photo">
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
                  <!-- Non so se tenerli fuori dall'elenco puntato -->
                  <input type="button" name="cancel-button" value="Annulla" class="btn" />
                  <input type="button" name="creation-button" value="Crea" class="btn btn-primary"/>
                </ul>
    </form> `;

  document.querySelector("main .middle").innerHTML = form;
}

fillMain();

document.querySelector("input[name=cancel-button]")
.addEventListener('click', () => document.location.href = "index.php");



document.querySelector("input[name=creation-button]")
  .addEventListener('click', function () {
    const post_params = {
      "title": document.querySelector("input#title").value,
      "photo": document.querySelector("input#photo").value,
      "description": document.querySelector("input#description").value,
      "location": document.querySelector("input#location").value,
      "event-datetime": document.querySelector("input#event-datetime").value,
    }

    if (checkForm(post_params)) {
      sendPostData(post_params);
    } else {
      alert('Filla tutti i campi scemo');
    }
  });

function checkForm(post_params) {
  let err = true;
  for (const [key, value] of Object.entries(post_params)) {
    if (value == "") {
      // document.querySelector("input#"+key+">p")
      err = false;
    }
  }
  return err;
}

function sendPostData(post_params) {
  const formData = new FormData();
  for (const [key, value] of Object.entries(post_params)) {
    formData.append(key, value);
  }
  axios.post('api-post.php', formData)
    .then(res => {
      console.log(res.data);
      if (res.data["loginok"]) {
        alert("TUTTO OK FRATE");
        window.location.href = "index.php"
      } else {
        console.log("QULCOSA storto");
        console.log(res.data["errormsg"]);
      }
    })
}
