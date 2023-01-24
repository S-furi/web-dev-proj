const timelineLoading = true;

function getPostEntity(post, userId) {
  return `
            <article class="post">
                <div class="post-head">
                    <a href="post.php?usrId=${post['usrId']}&postId=${post['postId']}">
                        <h3>${post['title']}</h3>
                    </a>
                    <div class="usr-info">
                        <a href="user-profile.php?usrId=${post['usrId']}">
                            <img src="${post['userInfo']['profileImg']}" alt="post author profile picture" class="profile-picture">
                        </a>
                        <a href="user-profile.php?usrId=${post['usrId']}">
                            <p class="usertag">@${post['author']}</p>
                        </a>
                    </div>
                </div>
                <div class="post-body">
                    <div class="date-location">
                        <div class="date-location-item">
                            <p class="info-tag">Data:</p>
                            <p>${post['eventDate']}</p>
                        </div>
                        <div class="date-location-item">
                            <p class="info-tag">Luogo:</p>
                            <p>${post['location']}</p>
                        </div>
                    </div>
                    <img src="${post['image'].replace(/\s/g,'%20')}" alt="" />
                    <p>${post['caption']}</p>
                    <div class="post-stats">
                      <a href="#" onclick="showLikeUsers(${post['postId']}, ${userId})"><p class="likes-n">Mi Piace: ${post['likes']}</p></a>
                      <a href="#" onclick="showParticipantsUsers(${post['postId']}, ${userId})"><p>Partecipanti: ${post['participants']}</p></a>
                    </div>
                    <div class="interaction-buttons">
                        <div class="like-comment-div">
                            <label for="post-${post['postId']}-like-btn"><button type="button" value="like" name="like button" id="post-${post['postId']}-like-btn" onclick="likePost(${post['postId']}, ${userId}, this)"><span class="material-symbols-outlined like-btn">favorite</span></button></label>
                            <a href="post.php?usrId=${post['usrId']}&postId=${post['postId']}#comment-text-area" target="_self">
                                <input type="button" name="comment button" id="comment-btn" />
                                <span class="material-symbols-outlined">comment</span>
                            </a>
                        </div>
                        <label for="post-${post['postId']}-join-btn"><button type="button" name="join event button" id="post-${post['postId']}-join-btn" class="btn btn-primary" onclick="participateToEvent(this)">Partecipa</button></label>
                    </div>
                </div>
            </article>`
}

const timelineBody = document.querySelector("main .middle");

function appendToBody(postsElements) {
    let content = `<section class="timeline">`
    content += postsElements;
    content += `
              <div id="modal" class="modal">
                <div class="modal-content"></div>
              </div>
            </section>`;

    timelineBody.innerHTML = content;
}

function render(action) {
  axios.get("api/api-timeline.php", { params: { action: action } })
    .then(res => {
      const usrId = res.data['usrId'];
      let postsElements = "";
      res.data['posts'].forEach(post => {
        postsElements += getPostEntity(post, usrId);
      })

      appendToBody(postsElements);

    }).then(() => window.dispatchEvent(new Event("timelineFill")));
}

window.location.href.includes("index.php") ? render("home") : render("discover");

