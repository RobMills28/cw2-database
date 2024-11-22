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
            SELECT v.*, p.People_name as owner_name, p.People_licence
            FROM Vehicle v
            LEFT JOIN Ownership o ON v.Vehicle_ID = o.Vehicle_ID
            LEFT JOIN People p ON o.People_ID = p.People_ID
            WHERE v.Vehicle_plate LIKE ?
        ");
        $searchTerm = "%" . $_GET['search'] . "%";
        $stmt->execute([$searchTerm]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($results)) {
            $message = "No vehicles found matching your search.";
        }
    } catch(PDOException $e) {
        $message = "Error performing search: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Search Vehicles</title>
</head>
<body>
    <main>
        <nav>
            <h1>Vehicle Search</h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <form method="GET">
            <label>Registration Number:</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" 
                   placeholder="Enter registration number">
            <button type="submit">Search</button>
        </form>

        <?php if ($message): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (!empty($results)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Registration</th>
                        <th>Type</th>
                        <th>Color</th>
                        <th>Owner</th>
                        <th>Owner License</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $vehicle): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($vehicle['Vehicle_plate']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['Vehicle_type']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['Vehicle_colour']); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['owner_name'] ?? 'Unknown'); ?></td>
                            <td><?php echo htmlspecialchars($vehicle['People_licence'] ?? 'N/A'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>