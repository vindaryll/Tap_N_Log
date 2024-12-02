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

// Function to sanitize input
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve search, filters, and sort from POST
$search = sanitizeInput($_POST['search'] ?? '');
$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];

// Base query
$query = "
    SELECT 
        ea.ojt_attendance_id,
        ep.ojt_id,
        ep.ojt_img,
        CONCAT(ep.first_name, ' ', ep.last_name) AS full_name,
        ep.ojt_rfid,
        ep.status,
        ep.date_approved,
        ea.date_att,
        ea.time_in,
        ea.time_out
    FROM ojt_attendance ea
    INNER JOIN ojt_profile ep ON ea.ojt_id = ep.ojt_id
    WHERE ea.is_archived = 0
";

// Apply search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (CONCAT(ep.first_name, ' ', ep.last_name) LIKE '%$search%' OR ep.ojt_rfid LIKE '%$search%' OR ea.ojt_attendance_id LIKE '%$search%')";
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
        $query .= " AND ep.ojt_rfid IS NOT NULL";
    } elseif ($filters['rfid_filter'] === 'without_rfid') {
        $query .= " AND ep.ojt_rfid IS NULL";
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
    $query .= " ORDER BY ea.date_att DESC"; // Default sorting
}

// Execute query
$result = $conn->query($query);

// Generate table rows
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $img = '/TAPNLOG/Image/OJT/' . ($row['ojt_img'] ?? '/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png');

        $attendance_id = $row['ojt_attendance_id'];
        $profile_id = $row['ojt_id'];
        $full_name = $row['full_name'];
        $rfid = $row['ojt_rfid'] ?? 'None';
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
                    <div class='col-lg-12 my-1'>
                        <button class='btn btn-info btn-custom w-100 h-100 p-2 view-details-btn'
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
