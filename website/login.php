<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles/login.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet" />
    <title>Brogram - Login</title>
</head>

<body>
    <main>
        <input type="checkbox" id="checkbox" aria-hidden="true" />
        <div class="signup">
            <form action="POST">
                <label for="checkbox" aria-hidden="true">Registrati</label>
                <!-- Paragraph for info msgs or errors-->
                <p></p>
                <label for="first-name"><input type="text" name="first-name" id="first-name" placeholder="Nome"
                        required /></label>
                <label for="last-name"><input type="text" name="last-name" id="last-name" placeholder="Cognome"
                        required /></label>
                <label for="username"><input type="text" name="username" id="username" placeholder="Username"
                        required /></label>
                <label for="login-email"><input type="email" name="email" id="login-email" placeholder="Email"
                        required /></label>
                <label for="login-password"><input type="password" name="password" id="login-password" placeholder="Password"
                        required /></label>
                <input type="button" value="Registrati" id="signup-btn">
            </form>
          </div>
        <div class="login">
            <form action="POST">
                <label for="checkbox" aria-hidden="true">Login</label>
                <!-- Paragraph for error msgs -->
                <p></p>
                <label for="signup-email"><input type="email" name="email" id="signup-email" placeholder="Email"
                        required /></label>
                <label for="signup-password"><input type="password" name="password" id="signup-password" placeholder="Password"
                        required /></label>
               <input type="button" value="Accedi" id="login-btn" />
            </form>
          </div>
    </main>
<script src="js/login-check.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</body>
