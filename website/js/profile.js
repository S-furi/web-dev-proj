function show(following, res) {
  if (res.data['ok']) {
    const modal = document.getElementById("modal");
    const modalContent = document.querySelector(".modal .modal-content");
    const followedUsers = following ? res.data['following'] : res.data['followed'];

    modalContent.innerHTML = "";
    const list = document.createElement("ul");
    list.className = following ? "following-users-list" : "followed-users-list";
    followedUsers.forEach(user => {
      const item = document.createElement("li");
      item.textContent = user.username;
      list.appendChild(item);
    });

    modalContent.appendChild(list);
    modal.style.display = "block";
  } else {
    console.log('errore nella visualizzazione');
  }
}

function showFollowedUsers(usrId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=0`)
    .then(res => {
      show(false, res);  
    });
}

function showFollowingUsers(usrId) {
  axios.post(`api/api-profile.php?usrId=${usrId}&azione=1`)
    .then(res => {
      show(true, res);  
    });
}