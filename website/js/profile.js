function generateList(users, imgSrc, list, sessionId) {
    users.forEach(user => {
      const item = document.createElement("li");
      // create a div element with class userinfo
      const userInfo = document.createElement("div");
      userInfo.className = "userinfo";
      // create an img element for the user's profile picture
      const img = document.createElement("img");
      img.src = imgSrc;
      img.alt = "suggested account profile picture";
      img.className = "profile-picture";
      userInfo.appendChild(img);
      // create a div element for the user's name and tag
      const userName = document.createElement("div");
      userName.className = "user-name";
      
      // create an a element with the user's name
      const a = document.createElement("a");
      if (user.usrId == sessionId) {
        a.href = `personal-profile.php`;
      } else {
        a.href = `user-profile.php?usrId=${user.usrId}`;
      }

      // create an h3 element with the username
      const h3 = document.createElement("h3");
      h3.textContent = user.firstName + " " + user.lastName;
      a.appendChild(h3);

      userName.appendChild(a);

      // create a p element with the user tag
      const p = document.createElement("p");
      p.className = "usertag";
      p.textContent = `@${user.username}`;
      userName.appendChild(p);

      userInfo.appendChild(userName);
      item.appendChild(userInfo);
      list.appendChild(item);
    });
}

function showLikes(res, sessionId) {
  debugger;
  if (res.data['ok']) {
    const modal = document.getElementById("modal");
    const modalContent = document.querySelector(".modal .modal-content");
    const likeUsers = res.data['likes'];

    modalContent.innerHTML = "";
    const list = document.createElement("ul");
    list.className = "likes-list";
    
    generateList(likeUsers, "img/no-profile-pic.png", list, sessionId);
    
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}

function showFollow(following, res, sessionId) {
  if (res.data['ok']) {
    const modal = document.getElementById("modal");
    const modalContent = document.querySelector(".modal .modal-content");
    const followUsers = following ? res.data['following'] : res.data['followed'];

    modalContent.innerHTML = "";
    const list = document.createElement("ul");
    list.className = following ? "following-users-list" : "followed-users-list";
    
    generateList(followUsers, "img/no-profile-pic.png", list, sessionId);
    
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}
 
function showLikeUsers(postId, sessionId) {
  axios.post(`api/api-profile.php?postId=${postId}&azione=2`)
    .then(res => {
      showLikes(res, sessionId);
    });
}
          
function showFollowedUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=0`)
    .then(res => {
      showFollow(false, res, sessionId);  
    });
}

function showFollowingUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=1`)
    .then(res => {
      showFollow(true, res, sessionId);  
    });
}