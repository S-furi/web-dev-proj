// Initialize variables to track the state of the long polling process
let lastNotificationId = 0;
const notificationBadge = document.querySelectorAll("header .notification-badge");

// Make a long polling request to the server
function poll() {
    const formData = new FormData();
    formData.append("lastNotificationId", lastNotificationId);

    // Make an Axios request to the PHP script
    axios.post('api/api-notification-polling.php', formData)
        .then(response => { 
            // If the request returns new notifications, update the lastNotificationId variable and display the notifications
            const notifications = response.data;

            notificationBadge.forEach(badge => badge.innerText = notifications.length < 10 ? notifications.length : "9+");

            if (notifications.length > 0) {
                lastNotificationId = notifications[notifications.length - 1].id;
                notificationBadge.innerText = notifications.length < 10 ? notifications.length : "9+";

                // To decide if there's another file or it's made on a float window
                // displayNotification(notifications[i]);
            } else {
                console.log("niente di nuovo");
            }
            // Make another request
            // poll();
        }).catch(error => {
            console.log(error);
            if (error.response.status == 204) {
                // If the request returns no new notifications, wait for a specified period of time before making another request
                setTimeout(poll, 5000);
            }
    });
}

// Start the long polling process
poll();

