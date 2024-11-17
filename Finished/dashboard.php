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
$customers = $mysqli->query("SELECT customer_id, name FROM customers");
$locations = $mysqli->query("SELECT location_id, name FROM locations");
$projects = $mysqli->query("SELECT project_id, name FROM projects");
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

            <div id="tagesbericht-form" style="display:none;">
                <form method="post" action="upload.php">
                    <div class="form-group">
                        <label for="customer-select">Kunde:</label>
                        <select name="customer" id="customer-select" class="form-control">
                            <?php while ($row = $customers->fetch_assoc()) {
                                echo "<option value='{$row['customer_id']}'>{$row['name']}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date-select">Datum:</label>
                        <input type="date" name="date" id="date-select" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="location-select">Standort:</label>
                        <select name="location" id="location-select" class="form-control">
                            <?php while ($row = $locations->fetch_assoc()) {
                                echo "<option value='{$row['location_id']}'>{$row['name']}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="project-select">Projekt:</label>
                        <select name="project" id="project-select" class="form-control">
                            <?php while ($row = $projects->fetch_assoc()) {
                                echo "<option value='{$row['project_id']}'>{$row['name']}</option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">

                        <label for="start-time">Check-In Zeit:</label>
                        <input type="time" name="start_time" id="start-time" class="form-control">
                        <label for="end-time">Check-Out Zeit:</label>
                        <input type="time" name="end_time" id="end-time" class="form-control">
                        <label for="pause-time">Pause (in Minuten):</label>
                        <input type="number" name="pause_time" id="pause-time" class="form-control">
                        <label>Gesamtzeit:</label>
                        <input type="text" id="total-time" class="form-control" readonly>
                        <span type="test">Geben Sie Hinweis:</span> <!-- Hint -->
                        <input type="text" id="total-time" class="form-control" >


                    </div>
                    <div class="form-group">
                        <label>Unterschrift:</label>
                        <div id="signature-pad" class="kbw-signature"><canvas></canvas></div>
                        <button type="button" id="clear" class="btn btn-secondary">Clear</button>
                        <textarea id="signature64" name="signature" style="display: none;"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Speichern</button>
                    </div>
                </form>
            </div>

            <!-- Container for displaying results -->
            <div id="results-container" style="display:none;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kunde</th>
                            <th>Datum</th>
                            <th>Standort</th>
                            <th>Projekt</th>
                            <th>Signatur</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Results will be loaded here dynamically -->
                    </tbody>
                </table>
            </div>
        </main>
        <script>
            $(document).ready(function() {
                $("#tagesbericht-btn").click(function() {
                    $("#tagesbericht-form").toggle();
                });

                $("#show-results-btn").click(function() {
                    $("#results-container").toggle();
                    if ($("#results-container").is(":visible")) {
                        $.ajax({
                            url: 'fetch_results.php', // Make sure to implement this PHP script
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
            });
        </script>
    </div>
</body>
</html>
