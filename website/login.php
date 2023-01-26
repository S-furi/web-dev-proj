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
                <input type="text" aria-label="Inserisci il tuo nome" name="first-name" id="first-name" placeholder="Nome" title="Inserisci il tuo nome" required />
                <input type="text" aria-label="Inserisci il tuo cognome" name="last-name" id="last-name" placeholder="Cognome" title="Inserisci il tuo cognome" required />
                <input type="text" aria-label="Inserisci il tuo username" name="username" id="username" placeholder="Username" title="Inserisci il tuo username" required />
                <input type="email" aria-label="Inserisci la tua email" name="email" id="login-email" placeholder="Email" title="Inserisci la tua email" required />
                <input type="password" aria-label="Inserisci la tua password" name="password" id="login-password" placeholder="Password" title="Inserisci la tua password" required />
                <input type="button" value="Registrati" id="signup-btn">
            </form>
          </div>
        <div class="login">
            <form action="POST">
                <label for="checkbox" aria-hidden="true">Login</label>
                <!-- Paragraph for error msgs -->
                <p></p>
                <input type="email" aria-label="Inserisci la tua email" name="email" id="signup-email" placeholder="Email" title="Inserisci la tua email" required />
                <input type="password" aria-label="Inserisci la tua password" name="password" id="signup-password" placeholder="Password" title="Inserisci la tua password" required />
               <input type="button" value="Accedi" id="login-btn" />
            </form>
          </div>
    </main>
<script src="js/login-check.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</body>
