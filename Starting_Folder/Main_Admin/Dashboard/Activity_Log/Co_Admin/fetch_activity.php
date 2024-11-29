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

// Get POST data
$search = $_POST['search'] ?? '';
$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];

// Base query with station_id filter
$sql = "
    SELECT 
        al.activity_log_id,
        al.executed_timestamp,
        al.details,
        al.category,
        g.guard_id,
        CONCAT(g.guard_id, ' - ', g.guard_name) AS co_admin_name
    FROM activity_log al
    LEFT JOIN guards g ON al.guard_id = g.guard_id
    WHERE 1=1
";

if(!empty($filters['station_id'])){
    $station_id = $conn->real_escape_string($filters['station_id']);
    $sql .= " AND al.station_id = $station_id";
}

// Apply search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (al.details LIKE '%$search%' OR al.activity_log_id LIKE '%$search%')";
}

// Apply filters
if (!empty($filters['from_date'])) {
    $fromDate = $conn->real_escape_string($filters['from_date']);
    $sql .= " AND al.executed_timestamp >= '$fromDate'";
}

if (!empty($filters['to_date'])) {
    $toDate = $conn->real_escape_string($filters['to_date']);
    $toDate = date('Y-m-d', strtotime($toDate . ' +1 day')); // Include end date
    $sql .= " AND al.executed_timestamp <= '$toDate'";
}

if (!empty($filters['co_admin'])) {
    $coAdminId = intval($filters['co_admin']);
    $sql .= " AND g.guard_id = $coAdminId";
}

if (!empty($filters['section'])) {
    $section = $conn->real_escape_string($filters['section']);
    $sql .= " AND al.section = '$section'";
}

if (!empty($filters['category'])) {
    $category = $conn->real_escape_string($filters['category']);
    $sql .= " AND al.category = '$category'";
}

// Apply sorting
$orderBy = [];
if (!empty($sort['timestamp'])) {
    $orderBy[] = "al.executed_timestamp " . strtoupper($sort['timestamp']);
}

if (!empty($orderBy)) {
    $sql .= " ORDER BY " . implode(', ', $orderBy);
} else {
    $sql .= " ORDER BY al.executed_timestamp DESC"; // Default sort
}

// Execute query
$result = $conn->query($sql);

// Check for results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activityId = $row['activity_log_id'];
        $timestamp = $row['executed_timestamp'];
        $details = nl2br(htmlspecialchars($row['details']));
        $category = htmlspecialchars($row['category']);
        $coAdminName = htmlspecialchars($row['co_admin_name']);

        echo "
        <tr>
            <td>$activityId</td>
            <td>$timestamp</td>
            <td class='text-start'>$details</td>
            <td>$category</td>
            <td>$coAdminName</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center'>No records found.</td></tr>";
}

$conn->close();
