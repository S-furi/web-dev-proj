const signupBtn = document.querySelector("section.signup input#signup-btn ");
const loginBtn =  document.querySelector("section.login input#login-btn");

function handleSignupAttempt() {
    const form = document.querySelector("section.signup form");

    if (form.checkValidity()) {
        const data = new FormData(form);

        axios.post("api-signup.php", data)
            .then(res => {
                if (res.data['ok']) {
                    // clear all fields and display success
                    clearAllFields();
                    document.querySelector("section.signup p").innerText = "Utente inserito correttamente!";
                } else {
                    document.querySelector("section.signup p").innerText = "Nome utente o email già in uso!";
                }
            });
    } else {
        document.querySelector("section.signup p").innerText = "Riempi tutti i campi";
    }
}

function handleLoginAttempt() {
    const form = document.querySelector("section.login form");

    if (form.checkValidity()) {
        const data = new FormData(form);

        axios.post("api-login.php", data)
            .then(res => {
                if (res.data['ok']) {
                    // better doing this server side
                    document.location.href = "index.php";
                } else {
                    document.querySelector("section.login p").innerText = res.data["msg"];
                }
            });
    } else {
        document.querySelector("section.login p").innerText = "Riempi tutti i campi";
    }
}

// Worst way to do it in vanilla javascript
function clearAllFields() {
    document.querySelectorAll(`section.signup input[type="text"]`).forEach(t => t.value = "");
    document.querySelector(`section.signup input[type="email"]`).value = "";
    document.querySelector(`section.signup input[type="password"]`).value = "";
}

signupBtn.addEventListener('click', handleSignupAttempt);

loginBtn.addEventListener('click', handleLoginAttempt);

