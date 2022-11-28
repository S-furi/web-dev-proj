<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/style.css" />
    <title>Document</title>
</head>
<body>
   <header>
        <h1>Brogram</h1>
   </header> 
   <nav>
        <ul>
            <li><a href="#">Timeline (passato)</a></li><li><a href="#">Discover (futuro)</a></li><li><a href="#">Radar</a></li>
        </ul>
   </nav>
   <main>
        <?php
            require($templateParams["nome"]);
        ?>
   </main><aside>
        <h2>Suggested</h2>
        <ul>
            <li>
                <a href="#">Tuo babbo</a>
            </li>
        </ul>
   </aside>
   <div>
        <ul>
            <li><a href="#">Home</a></li><li><a href="#">Radar</a></li><li><a href="#">Profilo</a></li><li><a href="login.php">Login</a></li><li><a href="signup.php">Sign Up</a></li>
        </ul>
    </div>
   <!-- <footer>
        <ul>
            <li><a href="#">Home</a></li><li><a href="#">Radar</a></li><li><a href="#">Profilo</a></li>
        </ul>
   </footer> -->
</body>
</html>