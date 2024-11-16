<?php

// Andito ang card for design

session_start();

require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$status = isset($_GET['status']) ? sanitizeInput($_GET['status']) : '';
$from_date = isset($_GET['from_date']) ? sanitizeInput($_GET['from_date']) : '';
$to_date = isset($_GET['to_date']) ? sanitizeInput($_GET['to_date']) : '';

// SQL Query with optional filters
$query = "SELECT * FROM employees_profile WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR employee_rfid LIKE '%$search%')";
}
if (!empty($status)) {
    $query .= " AND status = '$status'";
}
if (!empty($from_date) && !empty($to_date)) {
    $query .= " AND date_approved BETWEEN '$from_date' AND '$to_date'";
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        
        // If RFID is null, set it to an empty string for the modal
        $rfidForModal = $row['employee_rfid'] ?? '';

        // Display "None" for RFID in the card view
        $rfidDisplay = $row['employee_rfid'] ?? 'None';

        $buttonHtml = $row['status'] === 'ACTIVE' ? "
            <div class='col-6'>
                <button type='button' class='btn btn-warning w-100 edit-btn'
                    data-id='{$row['employee_id']}'
                    data-first-name='{$row['first_name']}'
                    data-last-name='{$row['last_name']}'
                    data-rfid='{$rfidForModal}'
                    data-img='/TAPNLOG/Image/EMPLOYEES/{$row['employee_img']}'>
                    Edit
                </button>
            </div>
            <div class='col-6'>
                <button type='button' class='btn btn-danger w-100 status-btn'
                    data-id='{$row['employee_id']}' data-status='INACTIVE'>
                    Deactivate
                </button>
            </div>
        " : "
            <div class='col-12'>
                <button type='button' class='btn btn-success w-100 status-btn'
                    data-id='{$row['employee_id']}' data-status='ACTIVE'>
                    Reactivate
                </button>
            </div>
        ";

        echo "
            <div class='col-xl-3 col-lg-4 col-md-6 card-container'>
                <div class='card'>
                    <div class='profile-image-container'>
                        <img src='/TAPNLOG/Image/EMPLOYEES/{$row['employee_img']}' class='card-img-top' alt='Profile Image'>
                    </div>
                    <div class='card-body'>
                        <h5 class='card-title'>{$row['first_name']} {$row['last_name']}</h5>
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
?>
