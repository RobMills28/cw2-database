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

$pageTitle = "File New Report - Police Database";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Navigation Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">File New Incident Report</h1>
        <div class="tw-space-x-4">
            <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700">Dashboard</a>
            <a href="logout.php" class="tw-text-blue-600 hover:tw-text-blue-700">Logout</a>
        </div>
    </div>

    <?php if ($message): ?>
        <div class="<?php echo strpos($message, 'Error') !== false 
            ? 'tw-bg-red-50 tw-text-red-600 tw-border-red-200' 
            : 'tw-bg-green-50 tw-text-green-600 tw-border-green-200' ?> 
            tw-border tw-rounded-md tw-p-4 tw-mb-6">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="tw-max-w-2xl tw-mx-auto">
        <div class="card">
            <form method="POST" class="tw-space-y-6">
                <!-- Vehicle Selection -->
                <div>
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                        Vehicle:
                    </label>
                    <select name="vehicle_id" required class="form-input">
                        <option value="">Select Vehicle</option>
                        <?php foreach ($vehicles as $vehicle): ?>
                            <option value="<?php echo $vehicle['Vehicle_ID']; ?>">
                                <?php echo htmlspecialchars($vehicle['Vehicle_plate'] . ' - ' . $vehicle['Vehicle_type']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Person Selection -->
                <div>
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                        Person Involved:
                    </label>
                    <select name="people_id" required class="form-input">
                        <option value="">Select Person</option>
                        <?php foreach ($people as $person): ?>
                            <option value="<?php echo $person['People_ID']; ?>">
                                <?php echo htmlspecialchars($person['People_name'] . ' - ' . $person['People_licence']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Offence Selection -->
                <div>
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                        Offence:
                    </label>
                    <select name="offence_id" required class="form-input">
                        <option value="">Select Offence</option>
                        <?php foreach ($offences as $offence): ?>
                            <option value="<?php echo $offence['Offence_ID']; ?>">
                                <?php echo htmlspecialchars($offence['Offence_description']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date Input -->
                <div>
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                        Date:
                    </label>
                    <input type="date" name="date" required class="form-input">
                </div>

                <!-- Statement Textarea -->
                <div>
                    <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                        Statement:
                    </label>
                    <textarea 
                        name="statement" 
                        required 
                        rows="4" 
                        class="form-input"
                    ></textarea>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" class="btn tw-w-full">
                        File Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>