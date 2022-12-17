<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../styles/base-style.css" />
  <link rel="stylesheet" href="../styles/post-creation.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <title>Brogram - Home</title>
</head>

<body>
  <header>
    <div class="container">
      <a href="index.php" class="logo">Brogram</a>
      <div class="search-bar">
        <label for="main-search-bar"><span class="material-symbols-outlined">search</span><input type="search" name="search" id="main-search-bar" placeholder="Cerca"></label>
      </div>
      <div class="dropdown">
        <!-- When ready in DB, put this in php statement -->
        <img src="img/no-profile-pic.png" alt="user profile picture" class="profile-picture dropbtn" />
        <ul class="dropdown-content">
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </header>
  <div class="main">
    <div class="container">
      <!-- Left Panel -->
      <div class="left">
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
        <label for="create-post-xl"><input type="button" value="Crea Post" id="create-post-xl" class="btn btn-primary medium-btn" /></label>
        <label for="create-post-sm"><input type="button" value="+" id="create-post-sm" class="btn btn-primary small-btn" /></label>
      </div>

      <!-- Center Panel -->
      <main>
        <div class="middle">

          <?php
          // require($templateParams["nome"]);
          ?>
        </div>
      </main>
      <!-- Right Panel -->
      <div class="right">
        <h2>Account Consigliati</h2>
        <ul>
          <?php
          if (isset($templateParams["suggested_users"])) :
            foreach ($templateParams["suggested_users"] as $sugg_user) :
          ?>
              <li class="user-suggestion">
                <div class="userinfo">
                  <!-- to add in DB an image reference -->
                  <img src="img/no-profile-pic.png" alt="suggested account profile picture" class="profile-picture">
                  <div class="user-name">
                    <h3><?php echo $sugg_user["firstName"] . " " . $sugg_user["lastName"]; ?></h3>
                    <p class="usertag">@<?php echo $sugg_user["username"]; ?></p>
                  </div>
                </div>
                <!-- add a reference to the user for not having same id's in final HTML -->
                <label for="follow-btn"><input type="button" value="Segui" class="btn btn-primary" id="follow-btn" /></label>
              </li>
          <?php
            endforeach;
          endif;
          ?>
        </ul>
      </div>
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
