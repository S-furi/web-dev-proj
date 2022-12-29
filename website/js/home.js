function showForm() {
  window.location.href = "post-creation.php"
}

function followUser(user, followed, target=null) {
    const formData = new FormData();

    if (target) {
        target.disabled = true;
    }

    formData.append("user", user);
    formData.append("followed", followed);

    // action 2 registers a new following
    axios.post("api/api-users.php?action=2", formData)
        .then(res => {
            if (res.data["ok"]) {
                // follow went ok
                document.querySelector(".right li.user-suggestion.usr-"+followed + " input").value = "✔️";
                document.querySelector(".right li.user-suggestion.usr-"+followed).classList.add("disappearing-card");
                // asynchronous :)
                setTimeout(() => document.querySelector(".right li.user-suggestion.usr-"+followed).remove(), 500);
            } else {
                // temporary
                alert("Qualcosa è andato storto");
            }
        });
}

function menuItemSelectedEffect() {
    const menuItems = document.querySelectorAll(".menu-item");

    const changeActiveItems = () => {
        menuItems.forEach(item => {
            item.classList.remove('active');
        })
    }

    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            changeActiveItems();
            item.classList.add('active');
        })
    })
}

function searchUser(searchFragment) {
    // queries the database if only 3 letters are provided
    if (searchFragment.length > 2) {
        // selecting the html ul tag
        const searchResultsList = document.querySelector("header .dropdown.search-result .dropdown-content");
        const formData = new FormData();
        formData.append("queryFragment", searchFragment);

        axios.post("api/api-users.php?action=1", formData)
            .then(res => {
                let htmlResults = "";
                // query went ok
                if (res.data['ok'] && res.data['users'].length > 0) {
                    res.data['users'].forEach(user => {
                        htmlResults += `<li onclick=getSelectedText(this)>${user['username']}</li>`
                    });
                } else {
                    htmlResults = `<li>Nessun utente trovato...</li>`;
                }
                // check if the list is displayed 
                if (!searchResultsList.classList.contains("active")) {
                    searchResultsList.classList.add("active");
                    searchResultsList.classList.remove("inactive");
                }

                searchResultsList.innerHTML = htmlResults;
            })
    } else {
        // do not display the html element
        clearResultList();
    }
}

function getSelectedText(event) {
    document.querySelector(`header .search-bar input[type="search"]`).value = event.textContent;
    clearResultList();
}

function clearResultList() {
    const searchResultsList = document.querySelector("header .dropdown.search-result .dropdown-content");
    if (!searchResultsList.classList.contains("inactive")) {
        searchResultsList.classList.add("inactive");
        searchResultsList.classList.remove("active");
    }
    searchResultsList.innerHTML = "";
}

// selected effect on left menu item
menuItemSelectedEffect();

document.querySelectorAll(".left>.btn").forEach(btn => btn.addEventListener('click', showForm));


