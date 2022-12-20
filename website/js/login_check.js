const signupBtn = document.querySelector("section.signup input#signup-btn ");
const loginBtn =  document.querySelector("section.login input#login-btn");

signupBtn.addEventListener('click', function() {
    const data = new FormData(document.querySelector("section.signup form"));
    const signup_parameters = Object.fromEntries(data.entries());

    if (checkParamIntegrity(signup_parameters)) {
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
});

loginBtn.addEventListener('click', function() {
    const data = new FormData(document.querySelector("section.login form"));
    const login_params = Object.fromEntries(data.entries());
    
    if (checkParamIntegrity(login_params)) {
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
});


function checkParamIntegrity(params) {
    let check = true;
    for (const [key, value] of Object.entries(params)) {
        if (value === null || value === "") {
            console.log(key + "Not found");
            check = false;
        }
    }
    return check;
}

function clearAllFields() {
    document.querySelectorAll(`section.signup input[type="text"]`).forEach( t => t.value = "");
    document.querySelector(`section.signup input[type="email"]`).value = "";
    document.querySelector(`section.signup input[type="password"]`).value = "";
}
