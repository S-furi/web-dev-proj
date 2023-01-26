# Web Developement Project - University of Bologna A.D. 2022

# **BROGRAM**

## Introduction
This project simulates a social media platform, where the purpose is to create Events and make people around the world participate to them.
An event is created through a post, where other users can like or comment it.
Users can follow each others, and by doing this, the follower can see in his timeline all the events that will be created by the followed user.

## Instructions
Clone this repo and then move the `web-dev-proj` in **Apache Web Server** folder making it able to run. 

Go to `phpmyadmin` panel, and insert the queries inside [createDb](database/queries/createDb.sql) and run. Be sure that the database is createdy correctly and you **have the priviliges** to **craete a user** and ***grant*** him premissions.

Once this is done type in the address bar of the browser `localhost/web-dev-proj/website`, which will redirect you to the login and signup page. Here you will have to sign up or log in. Once registered you will be redirected to the profile editing page where you can change your profile picture and bio.

## Basic Functionalities
### Login and Registration
It is possibile to register to *Brogram* with an **unique username** and a a **unique email** per user. First/Last names are also required, as well as a password that will be safely stored in the local database.
Once a User is registered, it's possible to login with the provided email (not the username) and the choosen password. Once the login is done
the homepage is shown

### Profile
There are *two* types of profile:
- **Personal Profile**: your own profile where you can view post previews and select one of them, delete posts and edit your profile.
- **User Profile**: other users profiles, where you can view their post previews and select one of them.

#### Edit Profile
Once you have registered your account you will be redirected to the profile editing page where you can change your profile picture and bio. It can be accessed from the personal profile page too. Here you can edit your bio, profile picture, or both.

Note: Image size has to be be under 500Kb.

#### Search Profiles
In the top searchbar, it's possible to search for a user by it's first/last name, or by its username. A list of result is shown, and by clicking to one of the result, the page is redirected to the searched user profile's page.

### Timelines
There are *two* homes:
- **Home** (house icon) where it is possible to see al posts of followed users only
- **Explore** (compass icon) where are shown future posts from people that the user might be interested in

To select one of the two, the left sidebar makes it easy to access them.

### Post Creation Editor
The green button on the bottom of the left sidebar, makes you create a new post.
Every field of the post is required, expecially the location field has to be checked before submitting the form (with button `check` next to it). A tick will be shown if the location is valid, a cross otehrwise. See the [radar](#radar) section for more information on how location search works.

Once the post is submitted for creation, the output of the operation will be seen onscreen above the post creation title.

Note: Image size has to be be under 500Kb.

### Post
When you enter a post you will be able to view or post comments regarding the post / event. Inside a post there are informations regarding the number of likes and a list of users who have liked, the number of participants and the list of them, the place and date of the event and a link to the profile of the creator. Here you can decide to participate or leave an event.

### Radar
Every event has a location associated. It's possibile for a user to visualize a map of the events around the searched area.
In the search box, it is possibile to specify:
- A city name
- A town name (e.g. `Macerone, Cesenatico`, `Borello, Cesena`)
- An address
- A shop

A **Blue/Green Marker** will placed above the searched location.

A **Red Marker** will be placed above all events' locations, and by clicking on it, a popup will show containing the title of the event with the link to its associated post.

Under the hood, [Nominatim](https://nominatim.org/) and [OpenLayers](https://openlayers.org/) are being used for coordinates/locations search and the map displaying. 

Note: it's possibile to use the location search every 5/6 seconds for avoiding sending too many requests to OL and Nominatim (the same rules is being applied in the radar section)

### Notifications
Notifications are in real time, meaning that a long polling procedure retrieves new notifications every (worst case) 10 seconds.

A notification is sent to you if:
- Someone started following you.
- Someone liked your post.
- Someone commented your post.
- Someone is participating to one of your events.

By clicking on the profile image in the top right corner of the window, and by clicking on the notification sectiono, the notification center is displayed. Notifications with a white background are the unread ones (always on the top of the panel) and the grayish ones are the already read notifications. Here you can access the post / profile indicated in the notification.

If the action that triggers a notification is deleted (someone leaved an event, unfollows you or un-like your post), the notification is deleted.