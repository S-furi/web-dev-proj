// Initialize variables to track the state of the long polling process
let lastNotificationId = 0;
const notificationBadge = document.querySelectorAll("header .notification-badge");

const notificationsModal = document.querySelector(".modal#notifications-modal");
const modalContent = document.querySelector(".modal#notifications-modal ul");
const notificationsSpan = document.querySelector(".modal#notifications-modal span");

// https://fonts.google.com/icons
const notificationsIcons = {
  'like': 'favorite',
  'comment': 'comment',
  'follow': 'person_add',
  'participation': 'event_available',
}

// Make a long polling request to the server
function poll() {
  const formData = new FormData();
  formData.append("lastNotificationId", lastNotificationId);

  // Make an Axios request to the PHP script
  axios.post('api/api-notification-polling.php', formData)
    .then(response => {
      // If the request returns new notifications, update the lastNotificationId variable and display the notifications
      const notifications = response.data;

      if (notifications.length > 0) {
        notificationBadge.forEach(t => t.style.display = "block");
        notificationBadge.forEach(badge => badge.innerText = notifications.length < 10 ? notifications.length : "9+");

        lastNotificationId = notifications[notifications.length - 1].notificationId;
        // re fetch notifications if only new ones are detected
        displayNotification();
      } else {
        notificationBadge.forEach(t => t.style.display = "none");
      }

      // Make another request
      setTimeout(poll, 5000);
    }).catch(error => {
      if (error.code = "ECONNABORTED" || error.response.status == 204) {
        // If the request returns no new notifications, wait for a specified 
        // period of time before making another request
        setTimeout(poll, 5000);
      }
    });
}


function displayNotification() {
  axios.post("api/api-notification-center.php?action=1")
    .then(res => {
      if (res.data !== null) {
        if (res.data.length == 0) {
          modalContent.innerHTML = `<li>Nessuna nuova notifica...</li>`
        } else {
          modalContent.innerHTML = "";
          for (const i in res.data) {
            const n = res.data[i];

            modalContent.innerHTML +=
              `<li>
                            <a ${n['read'] ? `class="read"` : ""} onclick='redirectToNotificationSource("${n["reference"]}", ${n['notificationId']})'>
                            <p>${dateDiff(new Date(), new Date(n['datetime']))}<p>
                            <span class="material-symbols-outlined">${notificationsIcons[n['type']]}</span>
                            <img src="${n['fromUserInfo']['profileImg']}" alt="" class="profile-picture" />
                            <p class="usertag">@${n['fromUser']['username']}</p>
                            <p class="msg"> ${n['msg']} </p>
                            </a>
                        </li>`
          }
        }
      }
    }).catch(error => {
      if (error.code = "ECONNABORTED" || error.response.status == 204) {
        // If the request returns no new notifications, wait for a specified 
        // period of time before making another request
        setTimeout(poll, 5000);
      }
    });
}

function redirectToNotificationSource(reference, notificationId) {
  markReadNotification(notificationId).then(() => window.location.href = reference);
}

function markReadNotification(notificationId) {
  const formData = new FormData();
  formData.append('notificationId', notificationId);

  return axios.post('api/api-notification-center.php?action=2', formData)
    .then(res => {
      if (!res.ok) {
        // something failed
        console.log(res.data)
      }
    }).catch(err => console.log(err));
}

// used for retrieving the string "*some* minutes/hours/days ago"
function dateDiff(date1, date2) {
  // fix server date saving (1 hour earlier)
  date2.setHours(date2.getHours() + 1);
  const diffInMilliseconds = Math.abs(date1.getTime() - date2.getTime());
  const diffInMinutes = Math.floor(diffInMilliseconds / (1000*60));


  if (diffInMinutes < 59) {
    if (diffInMinutes == 0) {
      return "ora";
    }
    return diffInMinutes + 'm';
  }

  const diffInHours = Math.floor(diffInMinutes / 60);

  if (diffInHours < 24) {
    return diffInHours + 'h';
  }

  const diffInDays = Math.floor(diffInHours / 24);

  if (diffInDays >= 7) {
    return Math.floor(diffInDays / 7) + 'w'
  }

  return diffInDays + 'd';
}

function showNotificationCenter() {
  notificationsModal.style.display = "block";
}

function setModalListeners() {
  notificationsModal.addEventListener("keydown", function(event) {
    if (event.keyCode === 27) {
      notificationsModal.style.display = "none";
    }
  });

  notificationsSpan.onclick = () => {
    notificationsModal.style.display = "none";
  };
}

setModalListeners();

// Start the long polling process
poll();
displayNotification();

