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
                            <img src="${post['userInfo']['profileImg'].replace('/\s+/', '%20')}" alt="post author profile picture" class="profile-picture">
                        </a>
                        <a href="user-profile.php?usrId=${post['usrId']}">
                            <p class="usertag">@${post['author']}</p>
                        </a>
                    </div>
                </div>
                <div class="post-body">
                    <div class="date-location">
                        <div class="date-location-item">
                            <p class="nc-info-tag">Data:</p>
                            <p>${post['eventDate']}</p>
                        </div>
                        <div class="date-location-item">
                            <p class="nc-info-tag">Luogo:</p>
                            <p>${post['location']}</p>
                        </div>
                    </div>
                    <img src="${post['image'].replace(/\s/g,'%20')}" alt="" />
                    <p class="caption">${post['caption']}</p>
                    <div class="post-stats">
                      <a href="#" onclick="showLikeUsers(${post['postId']}, ${userId})"><p class="likes-n">Mi Piace: ${post['likes']}</p></a>
                      <a href="#" onclick="showParticipantsUsers(${post['postId']}, ${userId})"><p>Partecipanti: ${post['participants']}</p></a>
                    </div>
                    <div class="interaction-buttons">
                        <div class="like-comment-div">
                            <label for="post-${post['postId']}-like-btn"><button type="button" value="like" name="like button" id="post-${post['postId']}-like-btn" onclick="likePost(${post['postId']}, ${userId}, this)"><span class="material-symbols-outlined like-btn">favorite</span></button></label>
                            <a href="post.php?usrId=${post['usrId']}&postId=${post['postId']}#comment-text-area" target="_self">
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
    let content = `<div class="timeline">`
    content += postsElements;
    content += `</div>
                <div id="modal" class="modal">
                  <div class="modal-content"></div>
                </div>`;

    timelineBody.innerHTML = content;
}

function getPosts(action, offset, limit) {
  return axios.get("api/api-timeline.php", { params: { action: action } })
    .then(res => {
      if (offset === null && limit === null){ 
        return res.data 
      }
      
      const result = {
        "usrId": res.data['usrId'],
        "posts": res.data['posts'].slice(offset, limit + offset),
      }

      return result;
    }).catch(err => {
      if (err.code == 204) {
        // no posts are found
      }
    });
}

function render(action) {
  getPosts(action, postsOffset, postsLimit)
    .then(res => {
      const usrId = res['usrId'];
      let postsElements = "";
      res['posts'].forEach(post => {
        postsElements += getPostEntity(post, usrId);
      })

      appendToBody(postsElements);
      postsOffset += postsLimit;

    }).then(() => window.dispatchEvent(new Event("timelineFill")))
}

function loadMorePosts() {
  const timeline = document.querySelector("div.timeline");
  getPosts(action, postsOffset, postsLimit)
    .then(res => {
      if (res['posts'] == 0) {
        return;
      }
      const usrId = res['usrId'];
      let newPosts = ""
      res['posts'].forEach(post => {
        newPosts += getPostEntity(post, usrId);
      });

      timeline.innerHTML += newPosts;

      res['posts'].forEach(post => {
        reCheckLikes(post['postId']);
        reCheckParticipation(post['postId']);
      })
        
      activeLoading = false;
    })
}

function reCheckLikes(postId) {
  const target = document.querySelector(`button#post-${postId}-like-btn`);
  checkLikedPosts(target, postId);
}

function reCheckParticipation(postId) {
  const target = document.querySelector(`button#post-${postId}-join-btn`);
  disableAlreadyParticipating(target, postId);
}

let postsOffset = 0;
const postsLimit = 5;
let activeLoading = false;

window.addEventListener("scroll", () => {
  if (!activeLoading && window.innerHeight + window.scrollY >= document.body.offsetHeight) {
    activeLoading = true;
    loadMorePosts();
    postsOffset += postsLimit;
  }
})

const action = window.location.href.includes("index.php") ? "home" : "discover";
render(action);
