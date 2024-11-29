<?php

session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

header('Content-Type: application/json');

// SQL query to fetch all stations
$sql = "SELECT station_id, station_name FROM stations";

$result = $conn->query($sql);

$stations = [];
if ($result->num_rows > 0) {
    // Fetch each station and add it to the array
    while ($row = $result->fetch_assoc()) {
        $stations[] = [
            'station_id' => $row['station_id'],
            'station_name' => $row['station_name']
        ];
    }
} else {
    echo json_encode(['error' => 'No stations found']);
    exit();
}

// Return stations as JSON
echo json_encode($stations);

// Close the database connection
$conn->close();
?>
