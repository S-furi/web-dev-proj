<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/base-style.css" />
  <link rel="stylesheet" href="../styles/post-creation.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <link rel="stylesheet" href="../styles/calendar.css" />
  <title>Brogram - Home</title>
</head>

<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Brogram</a>
      <div class="dropdown search-result">
        <div class="search-bar">
          <span class="material-symbols-outlined">search</span>
          <label for="main-search-bar">
            <!-- by now it's queries only users -->
            <input type="search" name="search" id="main-search-bar" placeholder="Cerca" onkeyup="searchUser(this.value)" />
          </label>
        </div>
        <ul class="dropdown-content inactive">
        </ul>
      </div>
      <div class="dropdown">
        <!-- When ready in DB, put this in php statement -->
        <img src="img/no-profile-pic.png" alt="user profile picture" class="profile-picture dropbtn" />
        <ul class="dropdown-content">
          <li><a href="profile.php">Profilo</a></li>
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
        <section class="profile">
          <img src="img/no-profile-pic.png" alt="user profile picture" class="profile-picture" />
          <div class="handle">
            <p class="user-name"><?php echo $templateParams['user']['firstName'] . " " . $templateParams['user']['lastName']; ?></p>
            <p class="usertag">@<?php echo $templateParams['user']['username']; ?></p>
          </div>
        </section>
        <ul class="sidebar">
          <li class="menu-item">
            <span class="material-symbols-outlined">Home</span>
            <h2>Home</h2>
          </li>
          <li class="menu-item">
            <span class="material-symbols-outlined">Explore</span>
            <h2>Explore</h2>
          </li>
          <li class="menu-item">
            <span class="material-symbols-outlined">radar</span>
            <h2>Radar</h2>
          </li>
          <li class="menu-item">
            <span class="material-symbols-outlined">mail</span>
            <h2>Messaggi</h2>
          </li>
        </ul>
        <!-- End of Sidebar -->
        <label for="create-post-xl"><input type="button" value="Crea Post" id="create-post-xl" class="btn btn-primary medium-btn" onclick="showForm()" /></label>
        <label for="create-post-sm"><input type="button" value="+" id="create-post-sm" class="btn btn-primary small-btn" onclick="showForm()" /></label>
      </div>

      <!-- Center Panel -->
      <main>
        <div class="middle">

          <?php
          require($templateParams["nome"]);
          ?>
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
                <li class="user-suggestion <?php echo "usr-" . $sugg_user['usrId'] ?>">
                  <div class="userinfo">
                    <!-- to add in DB an image reference -->
                    <img src="img/no-profile-pic.png" alt="suggested account profile picture" class="profile-picture">
                    <div class="user-name">
                      <h3><?php echo $sugg_user["firstName"] . " " . $sugg_user["lastName"]; ?></h3>
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
  <?php
  if (isset($templateParams["js"])) :
    foreach ($templateParams["js"] as $script) :
  ?>
      <script type="text/javascript" src="<?php echo $script; ?>"></script>
  <?php
    endforeach;
  endif;
  ?>
</body>

</html>
