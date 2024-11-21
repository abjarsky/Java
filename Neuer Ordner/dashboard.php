<?php
session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: index.html'); // Redirect to login if not logged in
    exit();
}

// Database connection setup
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';
$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch data for dropdowns
$employees = $mysqli->query("SELECT employee_id, name FROM employees");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery.signature@1.2.1/jquery.signature.css">
    <style>
        /* General Design */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #c4ff00;
            color: #000000;
            text-align: center;
            padding: 20px;
            position: sticky;
            top: 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .container {
            width: 90%;
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
        }
        button {
            background-color: #c4ff00;
            color: #000000;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background-color: #a3e000;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #c4ff00;
            border-radius: 5px;
            font-size: 14px;
        }
        #signature-pad {
            border: 2px solid #000000;
            width: 100%;
            height: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #c4ff00;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #c4ff00;
            color: #000000;
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.signature@1.2.1/jquery.signature.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <div id="real-time-clock"></div>
        <p>Willkommen, Benutzer #<?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
    </header>
    <main class="container">
        <button id="tagesbericht-btn">Tagesbericht</button>
        <button id="show-results-btn">Ergebnisse anzeigen</button>

        <!-- Tagesbericht Formular -->
        <div id="tagesbericht-form" style="display: none;">
            <form method="post" action="upload.php">
                <div class="form-group">
                    <label for="firstname">Vorname:</label>
                    <input type="text" name="firstname" id="firstname" placeholder="Vorname">
                </div>
                <div class="form-group">
                    <label for="lastname">Nachname oder Firmenname:</label>
                    <input type="text" name="lastname" id="lastname" placeholder="Nachname oder Firmenname">
                </div>
                <div class="form-group">
                    <label for="address">Adresse:</label>
                    <input type="text" name="address" id="address" placeholder="Adresse">
                </div>
                <div class="form-group">
                    <label for="zipcode">PLZ:</label>
                    <input type="text" name="zipcode" id="zipcode" placeholder="Postleitzahl">
                </div>
                <div class="form-group">
                    <label for="city">Stadt:</label>
                    <input type="text" name="city" id="city" placeholder="Stadt">
                </div>
                <div class="form-group">
                    <label for="project">Projekt:</label>
                    <input type="text" name="project" id="project" placeholder="Projekt">
                </div>
                <div class="form-group">
                    <label for="employee">Mitarbeiter:</label>
                    <select name="employee" id="employee">
                        <?php while ($row = $employees->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['employee_id']) . "'>" . htmlspecialchars($row['name']) . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Datum:</label>
                    <input type="date" name="date" id="date">
                </div>
                <div class="form-group">
                    <label for="start-time">Check-In Zeit:</label>
                    <input type="time" name="start_time" id="start-time">
                    <label for="end-time">Check-Out Zeit:</label>
                    <input type="time" name="end_time" id="end-time">
                    <label for="pause-time">Pause (in Minuten):</label>
                    <input type="number" name="pause_time" id="pause-time">
                    <label>Gesamtzeit:</label>
                    <input type="text" id="total-time" readonly>
                </div>
                <div class="form-group">
                    <label>Unterschrift:</label>
                    <div id="signature-pad"></div>
                    <button type="button" id="clear">Löschen</button>
                    <textarea id="signature64" name="signature" style="display: none;"></textarea>
                </div>
                <button type="submit">Speichern</button>
            </form>
        </div>
        <!-- Ergebnisse Container -->
        <div id="results-container" style="display: none;">
            <h2>Ergebnisse</h2>
            <div id="results-content"></div>
        </div>
    </main>
    <script>
        function updateClock() {
            const now = moment().format('HH:mm:ss');
            document.getElementById('real-time-clock').textContent = now;
        }
        setInterval(updateClock, 1000);

        $(document).ready(function () {
            $("#tagesbericht-btn").click(function () {
                $("#tagesbericht-form").show();
                $("#results-container").hide();
            });

            $("#show-results-btn").click(function () {
                $("#results-container").show();
                $("#tagesbericht-form").hide();
                $("#results-content").load("fetch_results.php");
            });

            var sigPad = $('#signature-pad').signature({
                syncField: '#signature64',
                syncFormat: 'PNG',
                color: '#000000',
            });

            $('#clear').click(function () {
                sigPad.signature('clear');
                $("#signature64").val('');
            });

            $("#start-time, #end-time, #pause-time").change(function () {
                const start = $("#start-time").val();
                const end = $("#end-time").val();
                const pause = parseInt($("#pause-time").val()) || 0;

                if (start && end) {
                    const startTime = moment(start, "HH:mm");
                    const endTime = moment(end, "HH:mm");
                    if (endTime.isBefore(startTime)) {
                        $("#total-time").val("Ungültige Zeit");
                        return;
                    }
                    const duration = moment.duration(endTime.diff(startTime));
                    const totalMinutes = duration.asMinutes() - pause;
                    $("#total-time").val(totalMinutes > 0 ? `${totalMinutes} Minuten` : "Ungültige Zeit");
                }
            });
        });
    </script>
</body>
</html>
