<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/newStyle.css" />
    <title>Brogram</title>
</head>
<body>
    <nav class="navbar">
        <div class="nav-wrapper">
            <!-- <img src="../img/logo.PNG" class="brand-img" alt=""> -->
            <h1 class="brand-img">Brogram</h1>
            <input type="text" class="search-box" placeholder="search">
        </div>
    </nav>
    <section class="main">
    <?php
        require($templateParams["nome"]);
    ?>
    </section>
    <nav class="options-bar">
        <nav class="nav-wrapper">
             <div class="nav-items">
                <img src="img/home.PNG" class="icon" alt="">
                <img src="img/messenger.PNG" class="icon" alt="">
                <img src="img/add.PNG" class="icon" alt="">
                <img src="img/explore.PNG" class="icon" alt="">
                <img src="img/like.PNG" class="icon" alt="">
                <div class="icon user-profile"></div>
            </div>
        </nav>
    </nav>
</body>
</html>