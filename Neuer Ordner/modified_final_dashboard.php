<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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
// No customer table fetch required, as new fields are for direct input
$employees = $mysqli->query("SELECT employee_id, name FROM employees");
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="script.js" defer></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="/checkin/jquery.signature.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="/checkin/jquery.signature.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
</head>
<body>
    <div class="container">
        <header>
            <div id="real-time-clock">00:00:00</div>
            <h1>Dashboard</h1>
            <div>Welcome, User #<?php echo $_SESSION['user_id']; ?></div>
        </header>
        <main>
            <button id="tagesbericht-btn" class="btn btn-primary">Tagesbericht</button>
            <button id="show-results-btn" class="btn btn-info">Ergebnisse anzeigen</button>

            <div id="tagesbericht-form" style="display: none;">
    <form method="post" action="upload.php">
        <!-- Kunde -->
        <div class="form-group">
            <label for="firstname">Vorname:</label>
            <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Vorname">
        </div>
        <div class="form-group">
            <label for="lastname">Nachname oder Firmenname:</label>
            <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Nachname oder Firmenname">
        </div>
        <div class="form-group">
            <label for="address">Adresse:</label>
            <input type="text" name="address" id="address" class="form-control" placeholder="Adresse">
        </div>
        <div class="form-group">
            <label for="zipcode">PLZ:</label>
            <input type="text" name="zipcode" id="zipcode" class="form-control" placeholder="Postleitzahl">
        </div>
        <div class="form-group">
            <label for="city">Stadt:</label>
            <input type="text" name="city" id="city" class="form-control" placeholder="Stadt">
        </div>
        
        <!-- Projekt -->
        <div class="form-group">
            <label for="project">Projekt:</label>
            <input type="text" name="project" id="project" class="form-control" placeholder="Projekt">
        </div>

        <!-- Mitarbeiter -->
        <div class="form-group">
            <label for="employee">Mitarbeiter:</label>
            <select name="employee" id="employee" class="form-control">
                <?php while ($row = $employees->fetch_assoc()) {
                    echo "<option value='{$row['employee_id']}'>{$row['name']}</option>";
                } ?>
            </select>
        </div>
        
        <!-- Datum -->
        <div class="form-group">
            <label for="date-select">Datum:</label>
            <input type="date" name="date" id="date-select" class="form-control">
        </div>

        <!-- Zeit-Erfassung -->
        <div class="form-group">
            <label for="start-time">Check-In Zeit:</label>
            <input type="time" name="start_time" id="start-time" class="form-control">
            <label for="end-time">Check-Out Zeit:</label>
            <input type="time" name="end_time" id="end-time" class="form-control">
            <label for="pause-time">Pause (in Minuten):</label>
            <input type="number" name="pause_time" id="pause-time" class="form-control">
            <label>Gesamtzeit:</label>
            <input type="text" id="total-time" class="form-control" readonly>
        </div>

        <!-- Signatur -->
        <div class="form-group">
            <label>Unterschrift:</label>
            <div id="signature-pad" class="kbw-signature"><canvas></canvas></div>
            <button type="button" id="clear" class="btn btn-secondary">Löschen</button>
            <textarea id="signature64" name="signature" style="display: none;"></textarea>
        </div>

        <!-- Speichern -->
        <div class="form-group">
            <button type="submit" class="btn btn-success">Speichern</button>
        </div>
    </form>
</div>

        </main>
        <script>
            $(document).ready(function() {
    // Zeigt den "Tagesbericht"-Formular an und versteckt die Ergebnisse
    $("#tagesbericht-btn").click(function() {
        $("#tagesbericht-form").show(); // Zeigt das Formular
        $("#results-container").hide(); // Versteckt die Ergebnisse
    });

    // Zeigt die Ergebnisse und versteckt den "Tagesbericht"-Formular
    $("#show-results-btn").click(function() {
        $("#results-container").show(); // Zeigt die Ergebnisse
        $("#tagesbericht-form").hide(); // Versteckt das Formular

        // Nur Daten laden, wenn der Container sichtbar wird
        if ($("#results-container").is(":visible")) {
            $.ajax({
                url: 'fetch_results.php', // Implementiere sicher, dass diese Datei korrekt funktioniert
                method: 'GET',
                success: function(data) {
                    $("#results-container tbody").html(data);
                },
                error: function() {
                    alert('Fehler beim Laden der Daten');
                }
            });
        }
    });
});

                var sig = $('#signature-pad').signature({
                    syncField: '#signature64', 
                    syncFormat: 'PNG'
                });

                $('#clear').click(function() {
                    sig.signature('clear');
                    $("#signature64").val('');
                });

                $("#start-time, #end-time, #pause-time").change(function() {
                    var startTime = $("#start-time").val();
                    var endTime = $("#end-time").val();
                    var pauseTime = parseInt($("#pause-time").val()) || 0;
                    if (startTime && endTime) {
                        var start = moment(startTime, "HH:mm");
                        var end = moment(endTime, "HH:mm");
                        if (end.isBefore(start)) {
                            $("#total-time").val("Check-Out Zeit muss nach Check-In Zeit sein.");
                            return;
                        }
                        var duration = moment.duration(end.diff(start));
                        var hours = duration.asHours() - (pauseTime / 60);
                        $("#total-time").val(hours.toFixed(2) + " Stunden");
                    } else {
                        $("#total-time").val("");
                    }
                });
        
        </script>
    </div>
</body>
</html>
