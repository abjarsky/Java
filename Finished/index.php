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
        <div>&copy; Santos IT Lösungen UG</div>
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
            <div class="form-group">
                <label for="lkw-select">LKW Optionen:</label>
                <select id="lkw-select" name="lkw">
                    <option value="LKW 1">LKW 1</option>
                    <option value="LKW 2">LKW 2</option>
                    <option value="LKW 3">LKW 3</option>
                </select>
            </div>
            <div id="numeric-keypad">
                <button type="button" class="keypad" onclick="enterNumber('1')">1</button>
                <button type="button" class="keypad" onclick="enterNumber('2')">2</button>
                <button type="button" class="keypad" onclick="enterNumber('3')">3</button>
                <button type="button" class="keypad" onclick="enterNumber('4')">4</button>
                <button type="button" class="keypad" onclick="enterNumber('5')">5</button>
                <button type="button" class="keypad" onclick="enterNumber('6')">6</button>
                <button type="button" class="keypad" onclick="enterNumber('7')">7</button>
                <button type="button" class="keypad" onclick="enterNumber('8')">8</button>
                <button type="button" class="keypad" onclick="enterNumber('9')">9</button>
                <button type="button" class="keypad" onclick="enterNumber('0')">0</button>
                <button type="button" class="keypad delete" onclick="deleteLastChar()">⌫</button>
            </div>
            <div class="button-group">
                <button type="submit" name="action" value="signin">Anmelden</button>
                <button type="button" onclick="clearFields()">Löschen</button>
            </div>
        </form>
    </main>
</body>
</html>
