<?php
session_start();
require_once('db.inc.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$results = [];
$message = '';

if (isset($_GET['search']) && !empty($_GET['search'])) {
    try {
        $conn = getConnection();
        $stmt = $conn->prepare("
            SELECT p.*, GROUP_CONCAT(v.Vehicle_type, ' (', v.Vehicle_plate, ')') as vehicles
            FROM People p
            LEFT JOIN Ownership o ON p.People_ID = o.People_ID
            LEFT JOIN Vehicle v ON o.Vehicle_ID = v.Vehicle_ID
            WHERE p.People_name LIKE ? OR p.People_licence = ?
            GROUP BY p.People_ID
            ORDER BY p.People_name
        ");
        $searchTerm = "%" . $_GET['search'] . "%";
        $stmt->execute([$searchTerm, $_GET['search']]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($results)) {
            $message = "No people found matching your search.";
        }
    } catch(PDOException $e) {
        $message = "Error performing search: " . $e->getMessage();
    }
}

$pageTitle = "Search People - Police Database";
require_once('header.php');
?>

<div class="tw-container tw-mx-auto tw-px-4 tw-py-8">
    <!-- Header -->
    <div class="tw-flex tw-justify-between tw-items-center tw-mb-8">
        <h1 class="tw-text-2xl tw-font-bold tw-text-gray-900">Search People</h1>
        <div class="tw-space-x-4">
            <a href="dashboard.php" class="tw-text-blue-600 hover:tw-text-blue-700">Dashboard</a>
            <a href="logout.php" class="tw-text-blue-600 hover:tw-text-blue-700">Logout</a>
        </div>
    </div>

    <!-- Search Form -->
    <div class="card tw-mb-8">
        <form method="GET" class="tw-space-y-4">
            <div>
                <label class="tw-block tw-text-sm tw-font-medium tw-text-gray-700 tw-mb-2">
                    Name or License Number:
                </label>
                <div class="tw-flex tw-gap-4">
                    <input type="text" 
                           name="search" 
                           value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                           placeholder="Enter name or license number"
                           class="form-input">
                    <button type="submit" class="btn">
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="tw-bg-blue-50 tw-border tw-border-blue-200 tw-rounded-md tw-p-4 tw-mb-6 tw-text-blue-600">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <div class="tw-overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>License Number</th>
                        <th>Address</th>
                        <th>Vehicles</th>
                        <th>Incidents</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $person): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($person['People_name']); ?></td>
                            <td class="tw-font-mono"><?php echo htmlspecialchars($person['People_licence']); ?></td>
                            <td><?php echo htmlspecialchars($person['People_address']); ?></td>
                            <td><?php 
                                if (!empty($person['vehicles'])) {
                                    echo htmlspecialchars($person['vehicles']);
                                } else {
                                    echo '<span class="tw-text-gray-500">None registered</span>';
                                }
                            ?></td>
                            <td><span class="tw-text-gray-500">No incidents recorded</span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>