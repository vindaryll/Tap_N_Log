<?php

// Andito ang card for design

session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get POST data
$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];
$search = sanitizeInput($_POST['search'] ?? '');

// Start building query
$query = "SELECT * FROM ojt_profile WHERE 1=1";

// Apply Filters
if (!empty($filters['from_date'])) {
    $from_date = $conn->real_escape_string($filters['from_date']);
    $query .= " AND date_approved >= '$from_date'";
}
if (!empty($filters['to_date'])) {
    $to_date = $conn->real_escape_string($filters['to_date']);
    $query .= " AND date_approved <= '$to_date'";
}
if (!empty($filters['status'])) {
    $status = $conn->real_escape_string($filters['status']);
    $query .= " AND status = '$status'";
}
if (!empty($filters['rfid_filter'])) {
    if ($filters['rfid_filter'] === 'with_rfid') {
        $query .= " AND ojt_rfid IS NOT NULL";
    } elseif ($filters['rfid_filter'] === 'without_rfid') {
        $query .= " AND ojt_rfid IS NULL";
    }
}

// Apply Search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR ojt_rfid LIKE '%$search%')";
}

// Apply Sorting
$order_by = [];
if (!empty($sort['date'])) {
    $order_by[] = "date_approved " . strtoupper($sort['date']);
}
if (!empty($sort['name'])) {
    $order_by[] = "first_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
}

// Execute query
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // If RFID is null, set it to an empty string for the modal
        $rfidForModal = $row['ojt_rfid'] ?? '';

        // Display "None" for RFID in the card view
        $rfidDisplay = $row['ojt_rfid'] ?? 'None';

        $buttonHtml = $row['status'] === 'ACTIVE' ? "
            <div class='col-6'>
                <button type='button' class='btn btn-primary btn-custom w-100 edit-btn'
                    data-id='{$row['ojt_id']}'
                    data-first-name='{$row['first_name']}'
                    data-last-name='{$row['last_name']}'
                    data-rfid='{$rfidForModal}'
                    data-img='/TAPNLOG/Image/OJT/{$row['ojt_img']}'>
                    EDIT
                </button>
            </div>
            <div class='col-6'>
                <button type='button' class='btn btn-danger btn-custom w-100 status-btn'
                    data-id='{$row['ojt_id']}' data-status='INACTIVE'>
                    DEACTIVATE
                </button>
            </div>
        " : "
            <div class='col-12'>
                <button type='button' class='btn btn-success btn-custom w-100 status-btn'
                    data-id='{$row['ojt_id']}' data-status='ACTIVE'>
                    REACTIVATE
                </button>
            </div>
        ";

        echo "
            <div class='col-xl-3 col-lg-4 col-md-6 mt-2 card-container'>
                <div class='card'>
                    <div class='profile-image-container'>
                        <img src='/TAPNLOG/Image/OJT/{$row['ojt_img']}' class='card-img-top' alt='Profile Image'>
                    </div>
                    <div class='card-body'>
                        <h6 class='card-title'><strong>{$row['first_name']} {$row['last_name']}</strong></h6>
                        <p class='card-text'><strong>Date Approved:</strong> {$row['date_approved']}</p>
                        <p class='card-text'><strong>RFID:</strong> {$rfidDisplay}</p>
                        <p class='card-text'><strong>Status:</strong> {$row['status']}</p>
                    </div>
                    <div class='card-footer'>
                        <div class='row d-flex justify-content-center'>
                            $buttonHtml
                        </div>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p class='text-center'>No profiles found.</p>";
}
