<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/base-style.css" />
    <link rel="stylesheet" href="../styles/post-creation.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../styles/calendar.css" />
    <link rel="stylesheet" href="../styles/like-transitions.css" />
    <link rel="stylesheet" href="../styles/map-style.css" />
    <!-- OpenLayers default map style -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/openlayers/openlayers.github.io/master/en/v5.3.0/css/ol.css" type="text/css">
    <title>Brogram - Home</title>
</head>

<body>
    <header>
        <div class="container">
            <a href="index.php" class="logo">Brogram</a>
            <div class="dropdown search-result">
                <div class="search-bar">
                    <label for="main-search-bar">
                        <!-- by now it's queries only users -->
                        <span class="material-symbols-outlined">search</span>
                        <input type="search" name="search" id="main-search-bar" placeholder="Cerca" onkeyup="searchUser(this.value)" />
                    </label>
                </div>
                <ul class="dropdown-content inactive">
                </ul>
            </div>
            <div class="dropdown usr-actions">
                <span class="notification-badge"></span>
                <img src="<?php echo preg_replace("/\s/","%20", $templateParams['userInfo'][0]['profileImg']) ?>" alt="user profile picture" class="profile-picture dropbtn" />
                <ul class="dropdown-content inactive">
                    <li><a href="personal-profile.php">Profilo</a></li>
                    <li>
                        <span class="notification-badge"></span>
                        <a onclick="showNotificationCenter()">Notifiche</a>
                    </li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="main">
        <div class="container">
            <!-- Left Panel -->
            <div class="left">
                <!-- Section should need a heading -->
                <div class="profile">
                    <a href="personal-profile.php"><img src="<?php echo $templateParams['userInfo'][0]['profileImg']?>" alt="user profile picture" class="profile-picture" /></a>
                    <div class="handle">
                        <a href="personal-profile.php"><p class="user-name"><?php echo $templateParams['user']['firstName'] . " " . $templateParams['user']['lastName']; ?></p></a>
                        <p class="usertag">@<?php echo $templateParams['user']['username']; ?></p>
                    </div>
</div>
                <ul class="sidebar">
                    <li class="menu-item <?php if (isset($templateParams["active-home"])) echo $templateParams["active-home"] ?>" onclick="window.location.href='index.php'">
                      <span class="material-symbols-outlined">Home</span>
                      <h2>Home</h2>
                    </li>
                    <li class="menu-item <?php if (isset($templateParams["active-explore"])) echo $templateParams["active-explore"] ?>" onclick="window.location.href='discover.php'">
                      <span class="material-symbols-outlined">Explore</span>
                      <h2>Explore</h2>
                    </li>
                    <li class="menu-item <?php if (isset($templateParams["active-radar"])) echo $templateParams["active-radar"] ?>" onclick="window.location.href='map.php'">
                      <span class="material-symbols-outlined">radar</span>
                      <h2>Radar</h2>
                    </li>
                </ul>
                <!-- End of Sidebar -->
                <div id="create-post-xl" class="btn btn-primary medium-btn">Crea Post</div>
                <div id="create-post-sm" class="btn btn-primary small-btn"><span class="material-symbols-outlined">add</span></div>
            </div>

            <!-- Center Panel -->
            <main>
                <div class="middle">
                    <?php
                    if (isset($templateParams["template_name"])) :
                        require($templateParams["template_name"]);
                    ?>
                    <?php endif; ?>
                </div>
            </main>
            <!-- Right Panel -->
            <div class="right">
                <div class="users-suggestions-wrapper">
                    <h2>Account Consigliati</h2>
                    <ul>
                        <?php
                        if (isset($templateParams["suggested_users"])) :
                            foreach ($templateParams["suggested_users"] as $sugg_user) :
                        ?>
                        <?php 
                        if (checkUserInfoExists($sugg_user['usrId'], $mysqli)) {
                          $usrInfo = getUserInfo($sugg_user['usrId'], $mysqli)[0];
                          $usrInfo['profileImg'] = 'img/' . $sugg_user['username'] . "/propic/" . $usrInfo['profileImg'];
                        } else {
                          $usrInfo['profileImg'] = 'img/no-profile-pic.png';
                        }
                        
                        ?>
                                <li class="user-suggestion <?php echo "usr-" . $sugg_user['usrId'] ?>">
                                    <div class="userinfo">
                                        <!-- to add in DB an image reference -->
                                        <img src="<?php echo $usrInfo['profileImg']; ?>" alt="suggested account profile picture" class="profile-picture">
                                        <div class="user-name">
                                            <a href="user-profile.php?usrId=<?php echo $sugg_user['usrId']; ?>"><h3><?php echo $sugg_user["firstName"] . " " . $sugg_user["lastName"]; ?></h3></a>
                                            <p class="usertag">@<?php echo $sugg_user["username"]; ?></p>
                                        </div>
                                    </div>
                                    <label for="follow-btn-usr-<?php echo $sugg_user['usrId']; ?>"><input type="button" value="Segui" class="btn btn-primary" id="follow-btn-usr-<?php echo $sugg_user['usrId']; ?>" onclick="followUser(<?php echo $templateParams['user']['usrId']; ?>, <?php echo $sugg_user['usrId']; ?>)" /></label>
                                </li>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </ul>
                </div>
                <div class="calendar-wrapper"></div>
            </div>
        </div>
    </div>
    <div id="calendar-events-modal" class="modal" tabindex="-1">
        <div class="modal-content">
            <div class="modal-head">
              <p class="title">Eventi della giornata</p>
              <span id="calendar-closeBtn" class="material-symbols-outlined closeBtn" tabindex="0" title="Chiudi">close</span>
            </div>
            <ul class="events-of-day">
            </ul>
        </div>
    </div>
    <div id="notifications-modal" class="modal" tabindex="-1">
        <div class="modal-content">
            <div class="modal-head">
              <p class="title">Centro Notifiche</p>
              <span id="notifications-closeBtn" class="material-symbols-outlined closeBtn" tabindex="0" title="Chiudi">close</span>
            </div>
            <ul class="notifications"></ul>
        </div>
    </div>
    <?php
    if (isset($templateParams["js"])) :
        foreach ($templateParams["js"] as $script) :
    ?>
            <script src="<?php echo $script; ?>"></script>
    <?php
        endforeach;
    endif;
    ?>
</body>

</html>
