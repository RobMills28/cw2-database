<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            INSERT INTO Incident 
            (Vehicle_ID, People_ID, Incident_Date, Incident_Report, Offence_ID) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_POST['vehicle_id'],
            $_POST['people_id'],
            $_POST['date'],
            $_POST['statement'],
            $_POST['offence_id']
        ]);
        $message = "Report filed successfully!";
    } catch(PDOException $e) {
        $message = "Error filing report: " . $e->getMessage();
    }
}

// Get list of vehicles, people, and offences for dropdowns
try {
    $conn = getConnection();
    $vehicles = $conn->query("SELECT * FROM Vehicle")->fetchAll(PDO::FETCH_ASSOC);
    $people = $conn->query("SELECT * FROM People")->fetchAll(PDO::FETCH_ASSOC);
    $offences = $conn->query("SELECT * FROM Offence")->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $message = "Error loading data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>File New Report - Police Database</title>
    <style>
        form { max-width: 600px; margin: 0 auto; }
        .message { color: green; margin: 1rem 0; }
        .error { color: red; }
    </style>
</head>
<body>
    <main>
        <nav>
            <h1>File New Incident Report</h1>
            <div>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </div>
        </nav>

        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Vehicle:</label>
                <select name="vehicle_id" required>
                    <option value="">Select Vehicle</option>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <option value="<?php echo $vehicle['Vehicle_ID']; ?>">
                            <?php echo htmlspecialchars($vehicle['Vehicle_plate'] . ' - ' . $vehicle['Vehicle_type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Person Involved:</label>
                <select name="people_id" required>
                    <option value="">Select Person</option>
                    <?php foreach ($people as $person): ?>
                        <option value="<?php echo $person['People_ID']; ?>">
                            <?php echo htmlspecialchars($person['People_name'] . ' - ' . $person['People_licence']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Offence:</label>
                <select name="offence_id" required>
                    <option value="">Select Offence</option>
                    <?php foreach ($offences as $offence): ?>
                        <option value="<?php echo $offence['Offence_ID']; ?>">
                            <?php echo htmlspecialchars($offence['Offence_description']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label>Date:</label>
                <input type="date" name="date" required>
            </div>

            <div>
                <label>Statement:</label>
                <textarea name="statement" required rows="4"></textarea>
            </div>

            <button type="submit">File Report</button>
        </form>
    </main>
</body>
</html>