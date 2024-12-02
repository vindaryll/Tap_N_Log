<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['vehicle_guard_logged'])) {
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

$sort = isset($_POST['sort']) && is_array($_POST['sort']) ? sanitizeArray($_POST['sort']) : [];
$search = sanitizeInput($_POST['search'] ?? '');

// Build the query
$query = "
    SELECT 
        vehicle_id, first_name, last_name, date_att, plate_num, purpose, vehicle_pass, time_in 
    FROM vehicles
    WHERE date_att = CURDATE() 
        AND time_out IS NULL 
        AND is_archived = FALSE
";

// Apply search filter
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR CONCAT(first_name, ' ', last_name) LIKE '%$search%')";
}

// Apply sorting
$order_by = [];
if (!empty($sort['time_in'])) {
    $order_by[] = "time_in " . strtoupper($sort['time_in']);
}
if (!empty($sort['name'])) {
    $order_by[] = "first_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
} 

// Execute the query
$result = $conn->query($query);

// Output the results as cards
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date("F j, Y", strtotime($row['date_att']));
        $formattedTimeIn = date("g:i A", strtotime($row['time_in']));

        echo "
            <div class='col-xl-3 col-lg-4 col-md-6 card-container mt-1'>
                <div class='card'>
                    <div class='card-body'>
                        <h6 class='card-title'><strong>{$row['first_name']} {$row['last_name']}</strong></h6>
                        <p class='card-text'><strong>DATE:</strong> $formattedDate</p>
                        <p class='card-text'><strong>TIME-IN:</strong> $formattedTimeIn</p>
                    </div>
                    <div class='card-footer d-flex flex-column'>
                        <button type='button' 
                                class='row btn btn-primary btn-custom m-1 w-100 view-details-btn' 
                                data-id='{$row['vehicle_id']}'>
                            VIEW DETAILS
                        </button>
                        <button type='button' 
                                class='row btn btn-success btn-custom w-100 m-1 time-out-btn' 
                                data-id='{$row['vehicle_id']}'
                                data-name='{$row['first_name']} {$row['last_name']}'>
                            TIME OUT
                        </button>
                        <button type='button' 
                                class='row btn btn-danger btn-custom w-100 m-1 archive-btn' 
                                data-id='{$row['vehicle_id']}'
                                data-name='{$row['first_name']} {$row['last_name']}'>
                            ARCHIVE
                        </button>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p class='text-center'>No vehicles to display.</p>";
}

$conn->close();
?>
