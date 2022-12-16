const signupBtn = document.querySelector("section.signup input#signup-btn ");
const loginBtn =  document.querySelector("section.login input#login-btn");

console.log(signupBtn);
console.log(loginBtn);

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

            console.log("{"+key+","+value+"}");
            formData.append(key, value);
        }
        axios.post("../api-signup.php", formData)
        .then(res => {
            if (res.data['ok']) {
                document.location.href = "login-form.php";
            }
            alert(res.data['msg']);
        });
    } else {
        alert("Filla tutti i campi");
    }
});

loginBtn.addEventListener('click', function() {
    const login_params = {
        "email": document.querySelector("section.login input#email").value,
        "password": document.querySelector("section.login input#password").value,
    }
    
    console.log(login_params);
    
    if (checkParamIntegrity(login_params)) {
        const formData = new FormData();
        for (const [key, value] of Object.entries(login_params)) {
            formData.append(key, value);
        }
        axios.post("../api-login.php", formData)
        .then(res => {
            if (res.data['ok']) {
                document.location.href = "../index.php";
            }
        });
    } else {
        alert("Filla tutti i campi");
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