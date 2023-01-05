function show(following, res, sessionId) {
  if (res.data['ok']) {
    const modal = document.getElementById("modal");
    const modalContent = document.querySelector(".modal .modal-content");
    const followedUsers = following ? res.data['following'] : res.data['followed'];

    modalContent.innerHTML = "";
    const list = document.createElement("ul");
    list.className = following ? "following-users-list" : "followed-users-list";
    
    followedUsers.forEach(user => {
      const item = document.createElement("li");
      // crea un elemento div con la classe userinfo
      const userInfo = document.createElement("div");
      userInfo.className = "userinfo";
      // crea un elemento img per l'immagine del profilo dell'utente
      const img = document.createElement("img");
      img.src = "img/no-profile-pic.png";
      img.alt = "suggested account profile picture";
      img.className = "profile-picture";
      userInfo.appendChild(img);
      // crea un elemento div per il nome e il tag dell'utente
      const userName = document.createElement("div");
      userName.className = "user-name";
      
      // crea un elemento a con il nome dell'utente
      const a = document.createElement("a");
      if (user.usrId == sessionId) {
        a.href = `personal-profile.php`;
      } else {
        a.href = `user-profile.php?usrId=${user.usrId}`;
      }

      // crea un elemento h3 con il nome dell'utente
      const h3 = document.createElement("h3");
      h3.textContent = user.firstName + " " + user.lastName;
      a.appendChild(h3);

      userName.appendChild(a);

      // crea un elemento p con il tag dell'utente
      const p = document.createElement("p");
      p.className = "usertag";
      p.textContent = `@${user.username}`;
      userName.appendChild(p);

      userInfo.appendChild(userName);
      item.appendChild(userInfo);
      list.appendChild(item);
    });
    
    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}
    
          
function showFollowedUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=0`)
    .then(res => {
      show(false, res, sessionId);  
    });
}

function showFollowingUsers(usrId, sessionId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=1`)
    .then(res => {
      show(true, res, sessionId);  
    });
}