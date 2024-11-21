<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Check-In System</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Login - Check-In System</h1>
        <div id="real-time-clock">00:00:00</div>
        <div>&copy; KORIA GUTACHTER</div>
    </header>

    <!-- Hauptinhalt -->
    <main class="container">
        <h2>Willkommen! Bitte anmelden</h2>
        <form action="login.php" method="post" id="login-form">
            <!-- Benutzer-ID -->
            <div class="form-group">
                <label for="user-id">Benutzer-ID:</label>
                <input type="text" id="user-id" name="user-id" required placeholder="Benutzer-ID eingeben">
            </div>
            <!-- Passwort -->
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required placeholder="Passwort eingeben">
            </div>
            <!-- Anmeldebutton -->
            <div class="button-group">
                <button type="submit" name="action" value="signin" class="btn btn-success">Anmelden</button>
            </div>
        </form>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 KORIA GUTACHTER. Alle Rechte vorbehalten.</p>
    </footer>
</body>
</html>
