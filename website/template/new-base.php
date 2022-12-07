<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/newStyle.css" />
    <script type="text/javascript" src="ws_functions.js"></script>
    <title><?php echo $templateParams["Titolo"]; ?></title>
</head>
<body>
    <nav class="navbar">
        <div class="nav-wrapper">
            <h1 class="brand-img">Brogram</h1>
            <input type="text" class="search-box" placeholder="search" id="search-field">
            <button type="button" onclick="trialButtonClick()" class="search-btn" id="search-btn">Search</button>
        </div>
    </nav>
    <section class="main">
    <div class="wrapper">
        <div class="left-col">
            <div class="col-items">
                <a href="index.php"><img src="img/home.PNG" class="icon" alt=""></a>
            </div> 
            <div class="col-items">
                <img src="img/messenger.PNG" class="icon" alt="">
            </div> 
            <div class="col-items">
                <a href="post-creation.php"><img src="img/add.PNG" class="icon" alt=""></a>
            </div> 
            <div class="col-items">
                <img src="img/explore.PNG" class="icon" alt="">
            </div> 
            <div class="col-items">
                <img src="img/like.PNG" class="icon" alt="">
            </div> 
            <div class="col-items">
                <div class="icon user-profile"></div>
            </div> 
        </div>

        <?php
            require($templateParams["nome"]);
        ?> 
        
        <div class="right-col">
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/profile-pic.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">username</p>
                    <button class="action-btn">switch</button>
                </div>
            </div>
            <p class="suggestion-text">Suggestions for you</p>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/cover 9.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">followed by user</p>
                    <button class="action-btn">follow</button>
                </div>
            </div>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/cover 10.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">followed by user</p>
                    <button class="action-btn">follow</button>
                </div>
            </div>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/cover 11.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">followed by user</p>
                    <button class="action-btn">follow</button>
                </div>
            </div>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/cover 12.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">followed by user</p>
                    <button class="action-btn">follow</button>
                </div>
            </div>
            <div class="profile-card">
                <div class="profile-pic">
                    <img src="img/cover 13.png" alt="">
                </div>
                <div>
                    <p class="username">username</p>
                    <p class="sub-text">followed by user</p>
                    <button class="action-btn">follow</button>
                </div>
            </div>
        </div>
    </div>

    </section>
    <nav class="options-bar">
        <nav class="nav-wrapper">
             <div class="nav-items">
                <a href="index.php"><img src="img/home.PNG" class="icon" alt=""></a>
                <img src="img/messenger.PNG" class="icon" alt="">
                <a href="post-creation.php"><img src="img/add.PNG" class="icon" alt=""></a>
                <img src="img/explore.PNG" class="icon" alt="">
                <img src="img/like.PNG" class="icon" alt="">
                <div class="icon user-profile"></div>
            </div>
        </nav>
    </nav>
</body>
</html>