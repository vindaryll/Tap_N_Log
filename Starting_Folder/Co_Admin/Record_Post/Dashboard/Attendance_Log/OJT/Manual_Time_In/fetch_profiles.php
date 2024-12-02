<?php

session_start();
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

// Base SQL query to fetch ojt without attendance today and not archived
$query = "
    SELECT 
        ep.ojt_id, ep.first_name, ep.last_name, ep.ojt_rfid, ep.ojt_img, ep.status 
    FROM 
        ojt_profile ep 
    LEFT JOIN 
        ojt_attendance ea 
    ON 
        ep.ojt_id = ea.ojt_id 
        AND ea.date_att = CURDATE() 
        AND ea.is_archived = FALSE
    WHERE 
        ea.ojt_id IS NULL AND ep.status = 'ACTIVE'
";

// Apply search functionality
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (ep.first_name LIKE '%$search%' OR ep.last_name LIKE '%$search%' OR ep.ojt_rfid LIKE '%$search%')";
}

// Apply filters
if (!empty($filters['rfid_filter'])) {
    if ($filters['rfid_filter'] === 'with_rfid') {
        $query .= " AND ep.ojt_rfid IS NOT NULL";
    } elseif ($filters['rfid_filter'] === 'without_rfid') {
        $query .= " AND ep.ojt_rfid IS NULL";
    }
}

// Apply sorting
$order_by = [];
if (!empty($sort['name'])) {
    $order_by[] = "ep.first_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
}

// Execute query
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rfid = $row['ojt_rfid'] ?? 'None';
        $img = '/TAPNLOG/Image/OJT/' . ($row['ojt_img'] ?? '/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png');

        echo "
            <div class='col-xl-3 col-lg-4 col-md-6 mt-2 card-container'>
                <div class='card'>
                    <div class='profile-image-container'>
                        <img src='$img' class='card-img-top' alt='Profile Image'>
                    </div>
                    <div class='card-body'>
                        <h6 class='card-title'><strong>{$row['first_name']} {$row['last_name']}</strong></h6>
                        <p class='card-text'><strong>RFID:</strong> $rfid</p>
                    </div>
                    <div class='card-footer'>
                        <button type='button' class='btn btn-success btn-custom w-100 time-in-btn'
                            data-profile-id='{$row['ojt_id']}'
                            data-name='{$row['first_name']} {$row['last_name']}'
                            data-rfid='$rfid'
                            data-img='$img'>
                            Time In
                        </button>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p class='text-center'>No profiles to time in.</p>";
}
?>
