const signupBtn = document.querySelector("div.signup input#signup-btn ");
const loginBtn =  document.querySelector("div.login input#login-btn");

function handleSignupAttempt() {
    const form = document.querySelector("div.signup form");

    if (form.checkValidity()) {
        const data = new FormData(form);

        axios.post("api/api-signup.php", data)
            .then(res => {
                if (res.data['ok']) {
                    clearAllFields();
                }
                console.log(res);
                displayMessage("signup", res.data['msg']);
            }).then( () => {
              axios.post("api/api-login.php", data)
                .then(res => {
                  if (res.data['ok']) {
                    window.location.href = 'edit-profile.php';
                  } else {
                    displayMessage("signup", res.data['msg']);
                  }
                });
            });
    } else {
        displayMessage("signup", "Riempi tutti i campi");
    }
}

function handleLoginAttempt() {
    const form = document.querySelector("div.login form");

    if (form.checkValidity()) {
        const data = new FormData(form);

        axios.post("api/api-login.php", data)
            .then(res => {
                if (res.data['ok']) {
                    document.location.href = "index.php";
                } else {
                    displayMessage("login", res.data['msg']);
                }
            }).catch(err => console.log(err));
    } else {
        displayMessage("login", "Riempi tutti i campi");
    }
}

function clearAllFields() {
    document.querySelectorAll(`div.signup input[type="text"]`).forEach(t => t.value = "");
    document.querySelector(`div.signup input[type="email"]`).value = "";
    document.querySelector(`div.signup input[type="password"]`).value = "";
}

function displayMessage(section, message) {
    document.querySelector(`div.${section} p`).innerText= message;
}

signupBtn.addEventListener('click', handleSignupAttempt);

loginBtn.addEventListener('click', handleLoginAttempt);

