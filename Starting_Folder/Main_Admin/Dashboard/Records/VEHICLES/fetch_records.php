<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '/Database/dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve filters, sort, and search parameters from POST request
$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];
$search = sanitizeInput($_POST['search'] ?? '');

// Base SQL query
$query = "SELECT * FROM vehicles WHERE is_archived = 0";

// Apply filters
if (!empty($filters['from_date'])) {
    $from_date = $conn->real_escape_string($filters['from_date']);
    $query .= " AND date_att >= '$from_date'";
}
if (!empty($filters['to_date'])) {
    $to_date = $conn->real_escape_string($filters['to_date']);
    $query .= " AND date_att <= '$to_date'";
}

// Apply search functionality
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR CONCAT(first_name, ' ', last_name) LIKE '%$search%' OR vehicle_id LIKE '%$search%')";
}

// Apply sorting
$order_by = [];
if (!empty($sort['date'])) {
    $order_by[] = "date_att " . strtoupper($sort['date']);
}
if (!empty($sort['time_in'])) {
    $order_by[] = "time_in " . strtoupper($sort['time_in']);
}
if (!empty($sort['time_out'])) {
    $order_by[] = "time_out " . strtoupper($sort['time_out']);
}
if (!empty($sort['name'])) {
    $order_by[] = "first_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
} else {
    $query .= " ORDER BY vehicle_id DESC"; // Default sorting by date
}

// Execute query
$result = $conn->query($query);

// Generate table rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $formattedDate = date("F j, Y", strtotime($row['date_att']));
        $formattedTimeIn = date("g:i A", strtotime($row['time_in']));
        $formattedTimeOut = !empty($row['time_out']) ? date("g:i A", strtotime($row['time_out'])) : "NOT COMPLETED";
        $vehicle_pass = isset($row['vehicle_pass']) && !empty($row['vehicle_pass']) ? $row['vehicle_pass'] : null;

        echo "
        <tr>
            <td>{$row['vehicle_id']}</td>
            <td>{$formattedDate}</td>
            <td>{$row['first_name']} {$row['last_name']}</td>
            <td>{$formattedTimeIn}</td>
            <td>{$formattedTimeOut}</td>
            <td>
                <div class='row d-flex justify-content-center align-items-center m-0 p-0'>
                    <div class='col-12 h-100 my-1'>
                        <button class='btn btn-info btn-custom w-100 h-100 p-2 view-details-btn'
                            data-id='{$row['vehicle_id']}'
                            data-first-name='{$row['first_name']}'
                            data-last-name='{$row['last_name']}'
                            data-plate-num='{$row['plate_num']}'
                            data-purpose='{$row['purpose']}'
                            data-vehicle-pass='{$row['vehicle_pass']}'>
                            VIEW DETAILS
                        </button>
                    </div>
                </div>
            </td>
        </tr>
        ";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
}
?>


