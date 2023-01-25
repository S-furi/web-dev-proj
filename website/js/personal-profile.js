function createInfoBox(user, userInfo, followers, following) {
    let form = ` 
        <section class="timeline">
            <div class="profile-infobox">
              <div class="profile-infobox-head">
                <div class="profile-usr-info">
                  <img src="${userInfo['profileImg']}" alt="post author profile picture" class="big-profile-picture">
                  <div class="nametags">
                    <h3>${user["firstName"]} ${user["lastName"]}</h3>
                    <p class="usertag">@${user["username"]}</p>
                  </div>
                </div>
                <label for="modify-btn"><input type="button" name="modify button" id="modify-btn"
                  class="btn btn-secondary" value="Modifica Profilo" onClick="parent.location='edit-profile.php'" /></label>
                <span class="material-symbols-outlined" id="manage_accounts" onclick="parent.location='edit-profile.php'">manage_accounts</span>
              </div>
              <div class="profile-infobox-body">
                <p class="profile-descr">${userInfo['bio']}</p>
                <div class="follow-info">
                  <span data-usr-id="${user['usrId']}" data-session-id="${user['usrId']}" class="info-tag followers-link" ><p>Seguaci: </p></span>
                  <p class="followers-data">${followers}</p>
                  <span data-usr-id="${user['usrId']}" data-session-id="${user['usrId']}" class="info-tag following-link" ><p>Seguiti: </p></span>
                  <p>${following}</p>
                </div>
              </div>
            </div>
        </section>
    `;
    return form;
}

function generatePosts(posts) {
    let result = "";

    if(posts.length > 0) {
        for(let i=0; i<posts.length; i++) {
          let eventDate = new Date(posts[i]['eventDate']).toLocaleDateString('it-IT', {
            day: 'numeric',
            month: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
          });
          
        let form = `
            <article class="post">
              <div class="post-head">
                <a href="post.php?usrId=${posts[i]['usrId']}&postId=${posts[i]['postId']}"><h3>${posts[i]['title']}</h3></a>
                <div class="dropdown actions-dropdown">
                  <span class="material-symbols-outlined pop-options" >more_vert</span>
                  <ul class="dropdown-content inactive">
                    <li><a href="#">Modifica</a></li>
                    <li><a style="color: var(--color-secondary)" onclick="deletePost(${posts[i]['postId']})">Elimina</a></li>
                  </ul>
                </div>
              </div>
              <div class="post-body">
                <div class="date-location">
                  <div class="date-location-item">
                    <p class="info-tag">Data:</p>
                    <p>${eventDate}</p>
                  </div>
                  <div class="date-location-item">
                    <p class="info-tag">Luogo:</p>
                    <p>${posts[i]['location']}</p>
                  </div>
                </div>
                <p>${posts[i]['caption']}</p>
                <div class="post-stats">
                  <span data-post-id="${posts[i]['postId']}" data-session-id="${posts[i]['sessionId']}" class="likesLink" ><p>Mi Piace: ${posts[i]['likes']}</p></span>
                  <span data-post-id="${posts[i]['postId']}" data-session-id="${posts[i]['sessionId']}" class="participantsLink" ><p>Partecipanti: ${posts[i]['participants']}</p></span>
                </div>
                <div class="profile-interaction-buttons">
                    <a href="post.php?usrId=${posts[i]['usrId']}&postId=${posts[i]['postId']}#comment-text-area" target="_self">
                      <span class="material-symbols-outlined">comment</span>
                    </a>
                </div>
              </div>
            </article>
        `;
        result += form;
       } 
    } else {
      let form = `
            <article class="post">
              <div class="post-not-present">
                <p>Nessun post caricato!</p>
              </div>
            </article>
      `;
      result += form;
    }

    let modal = `
            <div id="modal" class="modal">
              <div class="modal-content">
              </div>
            </div>
    `;
    result += modal;

    return result;
}

function handleDropDown() {
  const dropdown = document.querySelectorAll(".dropdown.actions-dropdown");

  if (typeof(dropdown) != 'undefined' && dropdown != null) {
    dropdown.forEach(t => t.addEventListener('click', (event) => {
      const content = event.target.nextElementSibling;
      if (content === null) {
        return;
      }
      content.classList.toggle("inactive");
      content.classList.toggle("active");
    }))
  }
}

function deletePost(postId) {
  const formData = new FormData();
  formData.append('postId', postId);

  axios.post("api/api-post.php?action=4", formData)
    .then(res => {
      if (res.data.postDeleted) {
        window.location.reload();
      }
    })
}

axios.get('api/api-personal-profile.php?azione=1')
  .then(response => {
    const infoBox = createInfoBox(response.data[0], response.data[1], response.data[2], response.data[3]);
    const timeline = document.querySelector("main .middle");
    timeline.innerHTML = infoBox;

  })
  .then( () => {
    axios.get('api/api-personal-profile.php?azione=0')
      .then(response => {
          const posts = generatePosts(response.data);
          const timeline = document.querySelector("main .middle .timeline");
          timeline.innerHTML += posts;
          handleDropDown();
          
          const participantsLink = document.querySelectorAll(".participantsLink");
          participantsLink.forEach(e => {
            e.addEventListener("click", () => {
              showParticipantsUsers(e.dataset.postId, e.dataset.sessionId);
            });
          });
          
          const likesLink = document.querySelectorAll(".likesLink");
          likesLink.forEach(e => {
            e.addEventListener("click", () => {
              if (parseInt(e.innerHTML.split(':')[1]) > 0) {
                showLikeUsers(e.dataset.postId, e.dataset.sessionId);
              }
            });
          });

          const followersLink = document.querySelectorAll(".followers-link");
          followersLink.forEach(e => {
            e.addEventListener("click", () => {
              debugger;
              const followersData = e.nextElementSibling;
              if (parseInt(followersData.innerHTML) > 0) {
                showFollowingUsers(e.dataset.usrId, e.dataset.sessionId);
              }
            });
          });

          const followingLink = document.querySelectorAll(".following-link");
          followingLink.forEach(e => {
            e.addEventListener("click", () => {
              const followingData = e.nextElementSibling;
              if (parseInt(followingData.innerHTML) > 0) {
                showFollowedUsers(e.dataset.usrId, e.dataset.sessionId);
              }
            });
          });
      })
      .catch(error => {
        console.log(error);
      });
  })
  .catch(error => {
    console.log(error);
  });