<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login - Check-In System</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <div id="real-time-clock">00:00:00</div>
        <div>&copy; KORIA GUTACHTER</div>
    </header>
    <main>
        <form action="login.php" method="post" id="login-form">
            <div class="form-group">
                <label for="user-id">Benutzer-ID:</label>
                <input type="text" id="user-id" name="user-id" required onclick="setActiveField('user-id')">
            </div>
            <div class="form-group">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required onclick="setActiveField('password')">
            </div>
            <div class="button-group">
                <button type="submit" name="action" value="signin">Anmelden</button>
            </div>
        </form>
    </main>
</body>
</html>
