<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

$station_id = isset($_GET['station_id']) ? $_GET['station_id'] : NULL;

$sql = "
    SELECT 
        ga.guard_id, 
        CONCAT(ga.guard_id, ' - ', g.guard_name) AS co_admin
    FROM guard_accounts ga
    JOIN guards g ON ga.guard_id = g.guard_id
    JOIN activity_log al ON g.guard_id = al.guard_id
    WHERE 1=1
";

if ($station_id !== NULL) {
    $sql .= " AND al.station_id = ?";
}

$sql .= " GROUP BY ga.guard_id";

// Execute the query
$stmt = $conn->prepare($sql);

// Bind the parameter only if station_id is set
if ($station_id !== NULL) {
    $stmt->bind_param("i", $station_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            'guard_id' => $row['guard_id'],
            'co_admin' => htmlspecialchars($row['co_admin'])
        );
    }
}

$stmt->close();
$conn->close();

// Set JSON header and output response
header('Content-Type: application/json');
echo json_encode($data);
