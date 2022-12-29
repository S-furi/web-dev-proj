function createInfoBox(user, followers, following) {
    let form = ` 
        <section class="timeline">
            <div class="profile-infobox">
              <div class="profile-infobox-head">
                <div class="profile-usr-info">
                  <img src="img/no-profile-pic.png" alt="post author profile picture" class="big-profile-picture">
                  <div class="nametags">
                    <h3>${user["firstName"]} ${user["lastName"]}</h3>
                    <p class="usertag">@${user["username"]}</p>
                  </div>
                </div>
                <label for="modify-btn"><input type="button" name="modify button" id="modify-btn"
                  class="btn btn-secondary" value="Modifica Profilo" /></label>
                <span class="material-symbols-outlined" id="manage_accounts">manage_accounts</span>
              </div>
              <div class="profile-infobox-body">
                <p class="profile-descr">Descrizione</p>
                <div class="follow-info">
                  <p class="info-tag">Followers: </p>
                  <p class="followers-data">${followers}</p>
                  <p class="info-tag">Following: </p>
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
                <a href="comment.php?usrId=${posts[i]['usrId']}&postId=${posts[i]['postId']}"><h3>${posts[i]['title']}</h3></a>
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
                <p class="likes-n">Mi Piace: ${posts[i]['likes']}</p>
                <div class="profile-interaction-buttons">
                    <label for="like-btn"><input type="button" name="like button" id="like-btn" /><span
                        class="material-symbols-outlined">favorite</span></label>
                    <a href="comment.php?usrId=${posts[i]['usrId']}&postId=${posts[i]['postId']}#comment-text-area" target="_self">
                      <input type="button" name="comment button" id="comment-btn" />
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

    return result;
}

axios.get('api/api-personal-profile.php?azione=1')
  .then(response => {
    const infoBox = createInfoBox(response.data[0], response.data[1], response.data[2]);
    const timeline = document.querySelector("main .middle");
    timeline.innerHTML = infoBox;
  })
  .then( () => {
    axios.get('api/api-personal-profile.php?azione=0')
      .then(response => {
          const posts = generatePosts(response.data);
          const timeline = document.querySelector("main .middle .timeline");
          timeline.innerHTML += posts;
      })
      .catch(error => {
        console.log(error);
      });
  })
  .catch(error => {
    console.log(error);
  });
