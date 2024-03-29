function followUser(user, followed, target = null) {
    const formData = new FormData();

    formData.append("user", user);
    formData.append("followed", followed);

    // action 2 registers a new following
  axios.post("api/api-users.php?action=2", formData)
    .then(res => {
      if (res.data["ok"]) {
        // if target isn't specified, it means we are in the sidebar
        // otherwise we the button is the one conained in the user profile
        if (target == null) {
          document.querySelector(".right li.user-suggestion.usr-" + followed).classList.add("disappearing-card");
          document.querySelector(".right li.user-suggestion.usr-" + followed + " input").value = "✔️";
          setTimeout(() => document.querySelector(".right li.user-suggestion.usr-" + followed).remove(), 500);
        } else {
          location.reload();
        }
      }
    });
}

function unfollowUser(user, followed) {
  const formData = new FormData();

  formData.append("user", user);
  formData.append("followed", followed);

  axios.post("api/api-users.php?action=3", formData)
    .then(res => {
      console.log(res.data);
      if (res.data.ok) {
        location.reload();
      }
    })
}

function likePost(postId, userId, target) {
    const formData = new FormData();
    formData.append("postId", postId);
    formData.append("userId", userId);

    axios.post("api/api-post.php?action=2", formData)
        .then(res => {
            if (res.data["ok"]) {
                renderLikeAnimation(target);
            }
        }).catch(err => console.log(err));
}

function checkLikedPosts(target, postId=null) {
    if (postId === null) {
        postId = target.id.split("-")[1];
    }
    const formData = new FormData();
    formData.append("postId", postId);

    axios.post("api/api-post.php?action=3", formData)
        .then(res => {
            if (res.data["hasAlreadyLikedPost"]) {
                target.firstElementChild.classList.add("is-expanded");
            }
        }).catch(err => console.log(err));
}

function searchUser(searchFragment) {
    // queries the database if at least 3 letters are provided
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
    const formData = new FormData();
    formData.append("queryFragment", event.textContent);
    axios.post("api/api-users.php?action=1", formData)
        .then(res => {
            if (res.data['ok']) {
                console.log(res.data["users"][0]["usrId"]);
                if (res.data['pid'] == res.data['users'][0]['usrId']) {
                  window.location.href = "personal-profile.php";
                } else {
                  window.location.href = "user-profile.php?usrId=" + res.data["users"][0]["usrId"];
                }
            }
        });
    clearResultList();
}

function participateToEvent(target, postId=null) {
    if (postId === null) {
      postId = target.id.split("-")[1];
    }

    const formData = new FormData();
    formData.append("postId", postId);

    axios.post("api/api-events.php?action=0", formData)
        .then(res => {
            if (res.data["ok"]) {
              target = target.parentNode;
              target.setAttribute("for", `post-${postId}-leave-btn`);

              target.innerHTML = 
              `<button type="button" name="leave event button" id="post-${postId}-leave-btn" style="background-color: var(--color-secondary)"
                class="btn btn-primary" onclick="leaveEvent(this)">Abbandona</button>`;
            }
        }).catch(err => console.log(err));
}

function leaveEvent(target, postId=null) {
  if (postId === null) {
      postId = target.id.split("-")[1];
  }

  const formData = new FormData();
  formData.append("postId", postId);

  axios.post("api/api-events.php?action=2", formData)
    .then(res => {
            target = target.parentNode
            target.setAttribute("for", `post-${postId}-leave-btn`)
              target.innerHTML = 
            `<button type="button" name="join event button" id="post-${postId}-join-btn"
              class="btn btn-primary" onclick="participateToEvent(this)">Partecipa</button>`;

    })  .catch(err => {
      if (err.status == "No Content") {
        console.log("Unkwon function were requested to server");
      }
    })
}

function disableAlreadyParticipating(target, postId = null) {
    if (postId === null) {
        postId = target.id.split("-")[1];
    }
    const formData = new FormData();
    formData.append("postId", postId);

    axios.post("api/api-events.php?action=1", formData)
        .then(res => {
            if (res.data["isUserAuthor"]) {
              target.parentNode.remove();
              target.remove();
              return
            }
            if (res.data["isParticipating"]) {
              target = target.parentNode
              target.setAttribute("for", `post-${postId}-leave-btn`)
                target.innerHTML = 
              `<button type="button" name="leave event button" id="post-${postId}-leave-btn" style="background-color: var(--color-secondary)"
                class="btn btn-primary" onclick="leaveEvent(this)">Abbandona</button>`;

            }
        }).catch(err => console.log(err));
}


// from now on, there are mainly visual functions 

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

function showForm() {
    window.location.href = "post-creation.php"
}

function clearResultList() {
    const searchResultsList = document.querySelector("header .dropdown.search-result .dropdown-content");
    if (!searchResultsList.classList.contains("inactive")) {
        searchResultsList.classList.add("inactive");
        searchResultsList.classList.remove("active");
    }
    searchResultsList.innerHTML = "";
}

function renderLikeAnimation(target) {
    target.firstElementChild.classList.toggle("is-expanded");
}

function renderButtons() {
    document.querySelectorAll(`.post .interaction-buttons button[name="join event button"]`)
        .forEach(btn => disableAlreadyParticipating(btn));

    document.querySelectorAll(`.post .interaction-buttons button[name="like button"]`)
        .forEach(btn => checkLikedPosts(btn));

    // selected effect on left menu item
    menuItemSelectedEffect();

    document.querySelectorAll(".left>.btn").forEach(btn => btn.addEventListener('click', showForm));
}

function activateDropDowns() {
    document.querySelector(".dropdown.usr-actions").addEventListener('click', () => {
        document.querySelector(".dropdown.usr-actions .dropdown-content").classList.toggle("inactive");
        document.querySelector(".dropdown.usr-actions .dropdown-content").classList.toggle("active");
    })    
}

if (typeof timelineLoading !== 'undefined') { 
    window.addEventListener("timelineFill", () => {
        renderButtons();
        activateDropDowns();
    })
} else {
    renderButtons();
    activateDropDowns();
}

