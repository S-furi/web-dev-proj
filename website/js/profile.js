function generateList(users, list, sessionId) {
    users.forEach(user => {
      const item = document.createElement("li");
      const userInfo = document.createElement("div");
      userInfo.className = "userinfo";
      const img = document.createElement("img");
      img.src = user.profileImg;
      img.alt = "suggested account profile picture";
      img.className = "profile-picture";
      userInfo.appendChild(img);
      const userName = document.createElement("div");
      userName.className = "user-name";
      
      const a = document.createElement("a");
      if (user.usrId == sessionId) {
        a.href = `personal-profile.php`;
      } else {
        a.href = `user-profile.php?usrId=${user.usrId}`;
      }

      const h3 = document.createElement("h3");
      h3.textContent = user.firstName + " " + user.lastName;
      a.appendChild(h3);

      userName.appendChild(a);

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
  if (res.data['ok']) {
    const modal = document.getElementById("modal");
    const modalContent = document.querySelector(".modal .modal-content");
    const likeUsers = res.data['likes'];

    modalContent.innerHTML = "";
    const list = document.createElement("ul");
    list.className = "likes-list";

    const modalHead = document.createElement("div");
    modalHead.className = "modal-head";

    const closeBtn = document.createElement('span');
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
    closeBtn.className = 'material-symbols-outlined';
    closeBtn.classList.add('closeBtn');
    closeBtn.tabIndex = '0';
    closeBtn.title = 'Chiudi';
    closeBtn.textContent = 'close';
    closeBtn.id = 'likes-closeBtn';

    const title = document.createElement("p");
    title.className = "title";
    title.textContent = "Mi Piace";

    modalHead.appendChild(title);
    modalHead.appendChild(closeBtn);
    
    generateList(likeUsers, list, sessionId);
    
    modalContent.appendChild(modalHead);
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}

function showFollow(following, res, sessionId) {
  if (res.data['ok']) {
    const modal = document.getElementById('modal');
    const modalContent = document.querySelector(".modal .modal-content");
    const followUsers = following ? res.data['following'] : res.data['followed'];

    modalContent.innerHTML = "";

    const modalHead = document.createElement("div");
    modalHead.className = "modal-head";

    const closeBtn = document.createElement('span');
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
    closeBtn.className = 'material-symbols-outlined';
    closeBtn.classList.add('closeBtn');
    closeBtn.tabIndex = '0';
    closeBtn.title = 'Chiudi';
    closeBtn.textContent = 'close';
    closeBtn.id = 'followers-closeBtn';

    const title = document.createElement("p");
    title.className = "title";
    title.textContent = following ? "Seguaci" : "Seguiti";

    modalHead.appendChild(title);
    modalHead.appendChild(closeBtn);

    const list = document.createElement("ul");
    list.className = following ? "following-users-list" : "followed-users-list";
    
    generateList(followUsers, list, sessionId);
    
    modalContent.appendChild(modalHead);
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}

function showParticipants(res, sessionId) {
  if (res.data['ok']) {
    const modal = document.getElementById('modal');
    const modalContent = document.querySelector(".modal .modal-content");
    const participants = res.data['participants'];

    modalContent.innerHTML = "";

    const modalHead = document.createElement("div");
    modalHead.className = "modal-head";
    
    const closeBtn = document.createElement('span');
    closeBtn.addEventListener("click", () => {
      modal.style.display = "none";
    });
    closeBtn.className = 'material-symbols-outlined';
    closeBtn.classList.add('closeBtn');
    closeBtn.tabIndex = '0';
    closeBtn.title = 'Chiudi';
    closeBtn.textContent = 'close';
    closeBtn.id = 'participants-closeBtn';

    const title = document.createElement("p");
    title.className = "title";
    title.textContent = "Partecipanti";

    modalHead.appendChild(title);
    modalHead.appendChild(closeBtn);

    const list = document.createElement("ul");
    list.className = "participants-users-list";

    generateList(participants, list, sessionId);

    modalContent.appendChild(modalHead);
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualzzazione del modale');
  }
}

function showParticipantsUsers(postId, sessionId) {
  axios.post(`api/api-profile.php?postId=${postId}&azione=3`)
    .then(res => {
      showParticipants(res, sessionId).catch(error => {
        console.log(error);
      });
    });
}
 
function showLikeUsers(postId, sessionId) {
  axios.post(`api/api-profile.php?postId=${postId}&azione=2`)
    .then(res => {
      showLikes(res, sessionId).catch(error => {
        console.log(error);
      });
    });
}
          
function showFollowedUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=0`)
    .then(res => {
      showFollow(false, res, sessionId).catch(error => {
        console.log(error);
      });  
    });
}

function showFollowingUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=1`)
    .then(res => {
      showFollow(true, res, sessionId).catch(error => {
        console.log(error);
      });  
    });
}

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
