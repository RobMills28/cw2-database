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
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="mvp.css">
    <title>Search People - Police Database</title>
    <style>
        body {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .header a {
            color: purple;
            text-decoration: none;
            margin-left: 15px;
        }

        .search-container {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .search-container input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .search-button {
            background-color: #4285f4;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .search-button:hover {
            background-color: #357abd;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .results-table th {
            background-color: #4285f4;
            color: white;
            text-align: left;
            padding: 15px;
            font-weight: 500;
        }

        .results-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .results-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .results-table tr:last-child td {
            border-bottom: none;
        }

        /* Fixed column widths */
        .results-table th:nth-child(1), 
        .results-table td:nth-child(1) { width: 20%; }
        .results-table th:nth-child(2), 
        .results-table td:nth-child(2) { width: 20%; }
        .results-table th:nth-child(3), 
        .results-table td:nth-child(3) { width: 35%; }
        .results-table th:nth-child(4), 
        .results-table td:nth-child(4) { width: 15%; }
        .results-table th:nth-child(5), 
        .results-table td:nth-child(5) { width: 10%; }

        /* Message styling */
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="search-container">
        <form method="GET">
            <label>Name or License Number:</label>
            <input type="text" 
                   name="search" 
                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
                   placeholder="Enter name or license number">
            <button type="submit" class="search-button">Search</button>
        </form>
    </div>

    <?php if ($message): ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <table class="results-table">
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
                        <td><?php echo htmlspecialchars($person['People_licence']); ?></td>
                        <td><?php echo htmlspecialchars($person['People_address']); ?></td>
                        <td><?php 
                            if (!empty($person['vehicles'])) {
                                echo htmlspecialchars($person['vehicles']);
                            } else {
                                echo 'None registered';
                            }
                        ?></td>
                        <td>No incidents recorded</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>