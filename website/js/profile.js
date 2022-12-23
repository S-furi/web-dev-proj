function createInfoBox(user) {
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
              </div>
              <div class="profile-infobox-body">
                <p class="profile-descr">Descrizione</p>
                <div class="follow-info">
                  <p class="followers-tag">Followers: </p>
                  <p class="followers-data">116</p>
                  <p class="following-tag">Following: </p>
                  <p class="following-data">219</p>
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
        let form = `
            <article class="post">
              <div class="post-head">
                <h3>${posts[i]['title']}</h3>
              </div>
              <div class="post-body">
                <p>${posts[i]['caption']}</p>
                <div class="interaction-buttons">
                  <label for="like-btn"><input type="button" name="like button" id="like-btn" /><span
                      class="material-symbols-outlined">favorite</span></label>
                  <label for="comment-btn"><input type="button" name="comment button" id="comment-btn" /><span
                      class="material-symbols-outlined">comment</span></label>
                  <label for="join-btn"><input type="button" name="join event button" id="join-btn"
                    class="btn btn-primary" value="Partecipa" /></label>
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

axios.get('api/api-profile-user.php').then(response => {
    const infoBox = createInfoBox(response.data);
    const timeline = document.querySelector("main .middle");
    timeline.innerHTML = infoBox;
}).then( () => {
  axios.get('api/api-profile-posts.php').then(response => {
      const posts = generatePosts(response.data);
      const timeline = document.querySelector("main .middle .timeline");
      timeline.innerHTML += posts;
  });
});

