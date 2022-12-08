document.querySelector("input[name=creation-button]")
.addEventListener('click', function() {
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
    for(const [key, value] of Object.entries(post_params)) {
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
            console.log("TUTTO OK FRATE");
        } else {
            console.log("QULCOSA storto");
            console.log(res.data["errormsg"]);
        }
    })
}