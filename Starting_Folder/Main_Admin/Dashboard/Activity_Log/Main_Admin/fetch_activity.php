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

// Base query
$sql = "
    SELECT 
        aal.admin_activity_log_id,
        aal.executed_timestamp,
        aal.details,
        aal.category,
        aal.section,
        CONCAT(aal.admin_id, ' - ', aa.username) AS admin_name
    FROM admin_activity_log aal
    LEFT JOIN admin_account aa ON aal.admin_id = aa.admin_id
    WHERE 1=1
";

// Apply search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (aal.details LIKE '%$search%' OR aal.admin_activity_log_id LIKE '%$search%')";
}

// Apply filters
if (!empty($filters['from_date'])) {
    $fromDate = $conn->real_escape_string($filters['from_date']);
    $sql .= " AND aal.executed_timestamp >= '$fromDate'";
}

if (!empty($filters['to_date'])) {
    $toDate = $conn->real_escape_string($filters['to_date']);
    $toDate = date('Y-m-d', strtotime($toDate . ' +1 day')); // Include end date
    $sql .= " AND aal.executed_timestamp <= '$toDate'";
}

if (!empty($filters['section'])) {
    $section = $conn->real_escape_string($filters['section']);
    $sql .= " AND aal.section = '$section'";
}

if (!empty($filters['category'])) {
    $category = $conn->real_escape_string($filters['category']);
    $sql .= " AND aal.category = '$category'";
}

// Apply sorting
if (!empty($sort['timestamp'])) {
    $sql .= " ORDER BY aal.executed_timestamp " . strtoupper($sort['timestamp']);
} else {
    $sql .= " ORDER BY aal.executed_timestamp DESC"; // Default sort
}

// Execute query
$result = $conn->query($sql);

// Check for results
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activityId = $row['admin_activity_log_id'];
        $timestamp = $row['executed_timestamp'];
        $details = nl2br(htmlspecialchars($row['details']));
        $category = htmlspecialchars($row['category']);
        $adminName = htmlspecialchars($row['admin_name']);

        echo "
        <tr>
            <td>$activityId</td>
            <td>$timestamp</td>
            <td class='text-start'>$details</td>
            <td>$category</td>
            <td>$adminName</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center'>No records found.</td></tr>";
}

$conn->close();
