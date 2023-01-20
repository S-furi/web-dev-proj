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
                displayNotification(notifications[0]['forUser']);
            } else {
                notificationBadge.forEach(t => t.style.display = "none");
                modalContent.innerHTML = `<li>Nessuna nuova notifica...</li>`
            }

            // Make another request
            // poll();
        }).catch(error => {
            if (error.code = "ECONNABORTED" || error.response.status == 204) {
                // If the request returns no new notifications, wait for a specified 
                // period of time before making another request
                setTimeout(poll, 5000);
            }
        });
}


function displayNotification(forUser) {
    const formData = new FormData();
    formData.append("usrId", forUser);

    axios.post("api/api-notification-center.php", formData)
        .then(res => {
            if (res.data !== null) {
                for (const i in res.data) {
                    const n = res.data[i];

                    modalContent.innerHTML +=
                    `<li>
                        <a onclick='redirectToNotificationSource("${n["reference"]}", ${n['notificationId']})'>
                        <span class="material-symbols-outlined">${notificationsIcons[n['type']]}</span>
                        <img src="img/no-profile-pic.png" alt="" class="profile-picture" />
                        <p class="usertag">@${n['fromUser']['username']}</p>
                        <p> ${n['msg']} </p>
                        </a>
                    </li>`
                }
            }
        }).catch(err => console.log(err));
}

function redirectToNotificationSource(reference, notificationId) {
  markReadNotification(notificationId).then(() => window.location.href = reference);
}

function markReadNotification(notificationId) {
  const formData = new FormData();
  formData.append('notificationId', notificationId);

  return axios.post('api/api-notification-center.php', formData)
    .then(res => {
      console.log(res.data)
      if (res.ok) {
        // notification has been read
      } else {
        // something failed
        console.log(res.data)
      }

    }).catch(err => console.log(err));
}

// used for retrieving the string "*some* minutes/hours/days"
function dateDiff(date1, date2) {
    // Calculate the difference in milliseconds
    const diffInMilliseconds = Math.abs(date1.getTime() - date2.getTime());
    // Calculate the number of minutes that have passed
    const diffInMinutes = Math.floor(diffInMilliseconds / 1000 / 60);
    // If the difference is less than 59 minutes, return the number of minutes that have passed
    if (diffInMinutes < 59) {
        return diffInMinutes + ' minuti fa';
    }
    // Otherwise, calculate the number of hours that have passed
    const diffInHours = Math.floor(diffInMinutes / 60);
    // If the difference is less than 24 hours, return the number of hours that have passed
    if (diffInHours < 24) {
        return diffInHours + ' ore fa';
    }
    // Otherwise, calculate the number of days that have passed and return the result
    const diffInDays = Math.floor(diffInHours / 24);
    // If the difference is less than 7 days, return the number of weeks that have passed
    if (diffInDays >= 7) {
        return Math.floor(diffInDays / 7) + ' settimane fa'
    }

    // Otherwise, return the number of days
    return diffInDays + ' giorni fa';
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

