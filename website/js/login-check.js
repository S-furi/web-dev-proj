const signupBtn = document.querySelector("section.signup input#signup-btn ");
const loginBtn =  document.querySelector("section.login input#login-btn");

signupBtn.addEventListener('click', function() {
    const signup_parameters = {
        "first-name": document.querySelector("section.signup input#first-name").value,
        "last-name": document.querySelector("section.signup input#last-name").value,
        "username": document.querySelector("section.signup input#username").value,
        "email": document.querySelector("section.signup input#email").value,
        "password":  document.querySelector("section.signup input#password").value,
    };
    
    if (checkParamIntegrity(signup_parameters)) {
        const formData = new FormData();
        for (const [key, value] of Object.entries(signup_parameters)) {
            formData.append(key, value);
        }

        axios.post("api-signup.php", formData)
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
    const login_params = {
        "email": document.querySelector("section.login input#email").value,
        "password": document.querySelector("section.login input#password").value,
    }
    
    if (checkParamIntegrity(login_params)) {
        const formData = new FormData();
        for (const [key, value] of Object.entries(login_params)) {
            formData.append(key, value);
        }
        axios.post("api-login.php", formData)
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
        if (value == "") {
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
