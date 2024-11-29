<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['record_guard_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

function sanitizeArray($array)
{
    return array_map('sanitizeInput', $array);
}

$filters = isset($_POST['filters']) && is_array($_POST['filters']) ? sanitizeArray($_POST['filters']) : [];
$sort = isset($_POST['sort']) && is_array($_POST['sort']) ? sanitizeArray($_POST['sort']) : [];
$search = sanitizeInput($_POST['search'] ?? '');

// Base query
$query = "
    SELECT 
        ea.cfw_attendance_id,
        ep.cfw_id,
        ep.cfw_img,
        CONCAT(ep.first_name, ' ', ep.last_name) AS full_name,
        ep.cfw_rfid,
        ep.status,
        ep.date_approved,
        ea.date_att,
        ea.time_in,
        ea.time_out
    FROM cfw_attendance ea
    INNER JOIN cfw_profile ep ON ea.cfw_id = ep.cfw_id
    WHERE ea.is_archived = 1
";

// Apply search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (CONCAT(ep.first_name, ' ', ep.last_name) LIKE '%$search%' OR ep.cfw_rfid LIKE '%$search%' OR ea.cfw_attendance_id LIKE '%$search%')";
}

// Apply filters
if (!empty($filters['from_date'])) {
    $from_date = $conn->real_escape_string($filters['from_date']);
    $query .= " AND ea.date_att >= '$from_date'";
}
if (!empty($filters['to_date'])) {
    $to_date = $conn->real_escape_string($filters['to_date']);
    $query .= " AND ea.date_att <= '$to_date'";
}
if (!empty($filters['status'])) {
    $status = $conn->real_escape_string($filters['status']);
    $query .= " AND ep.status = '$status'";
}
if (!empty($filters['rfid_filter'])) {
    if ($filters['rfid_filter'] === 'with_rfid') {
        $query .= " AND ep.cfw_rfid IS NOT NULL";
    } elseif ($filters['rfid_filter'] === 'without_rfid') {
        $query .= " AND ep.cfw_rfid IS NULL";
    }
}

// Apply sorting
$order_by = [];
if (!empty($sort['date'])) {
    $order_by[] = "ea.date_att " . strtoupper($sort['date']);
}
if (!empty($sort['time_in'])) {
    $order_by[] = "ea.time_in " . strtoupper($sort['time_in']);
}
if (!empty($sort['time_out'])) {
    $order_by[] = "ea.time_out " . strtoupper($sort['time_out']);
}
if (!empty($sort['name'])) {
    $order_by[] = "full_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(', ', $order_by);
} else {
    $query .= " ORDER BY ea.cfw_attendance_id DESC"; // Default sorting
}

// Execute query
$result = $conn->query($query);

// Generate table rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $img = '/TAPNLOG/Image/CFW/' . ($row['cfw_img'] ?? '/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png');

        $attendance_id = $row['cfw_attendance_id'];
        $profile_id = $row['cfw_id'];
        $full_name = $row['full_name'];
        $rfid = $row['cfw_rfid'] ?? 'None';
        $status = $row['status'];
        $date_approved = date("F j, Y", strtotime($row['date_approved']));
        $date = date("F j, Y", strtotime($row['date_att']));
        $time_in = date("g:i A", strtotime($row['time_in']));
        $time_out = $row['time_out'] ? date("g:i A", strtotime($row['time_out'])) : 'NOT COMPLETED';

        echo "
        <tr>
            <td>$attendance_id</td>
            <td>$date</td>
            <td>$full_name</td>
            <td>$time_in</td>
            <td>$time_out</td>
            <td>
                <div class='row d-flex justify-content-center align-items-center m-0 p-0'>
                    <div class='col-12 my-1'>
                        <button
                            class='btn btn-info view-details-btn h-100 w-100'
                            data-bs-img='$img'
                            data-bs-date-approved='$date_approved'
                            data-bs-name='$full_name'
                            data-bs-status='$status'
                            data-bs-rfid='$rfid'>
                            VIEW DETAILS
                        </button>
                    </div>
                </div>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No records found.</td></tr>";
}

$conn->close();
?>

