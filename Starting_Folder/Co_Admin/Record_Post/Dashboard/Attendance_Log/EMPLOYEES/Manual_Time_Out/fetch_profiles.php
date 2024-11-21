<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];
$search = sanitizeInput($_POST['search'] ?? '');

$query = "
    SELECT 
        ep.employee_id, ep.first_name, ep.last_name, ep.employee_rfid, ep.employee_img, 
        ea.date_att, ea.time_in, ea.employee_attendance_id
    FROM employees_profile ep
    INNER JOIN employees_attendance ea 
        ON ep.employee_id = ea.employee_id 
    WHERE ea.date_att = CURDATE() 
        AND ea.time_out IS NULL 
        AND ea.is_archived = FALSE
        AND ep.status = 'ACTIVE'
";

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (ep.first_name LIKE '%$search%' OR ep.last_name LIKE '%$search%' OR ep.employee_rfid LIKE '%$search%')";
}

if (!empty($filters['rfid_filter'])) {
    if ($filters['rfid_filter'] === 'with_rfid') {
        $query .= " AND ep.employee_rfid IS NOT NULL";
    } elseif ($filters['rfid_filter'] === 'without_rfid') {
        $query .= " AND ep.employee_rfid IS NULL";
    }
}

$order_by = [];
if (!empty($sort['name'])) {
    $order_by[] = "ep.first_name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
}

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rfid = $row['employee_rfid'] ?? 'None';
        $img = '/TAPNLOG/Image/EMPLOYEES/' . ($row['employee_img'] ?? '/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png');

        $formattedDate = $row['date_att'] ? date("F j, Y", strtotime($row['date_att'])) : 'NONE';
        $formattedTimeIn = $row['time_in'] ? date("g:i A", strtotime($row['time_in'])) : 'NONE';

        echo "
            <div class='col-xl-3 col-lg-4 col-md-6 card-container'>
                <div class='card'>
                    <div class='profile-image-container'>
                        <img src='$img' class='card-img-top' alt='Profile Image'>
                    </div>
                    <div class='card-body'>
                        <h6 class='card-title'><strong>{$row['first_name']} {$row['last_name']}</strong></h6>
                        <p class='card-text'><strong>RFID:</strong> $rfid</p>
                        <p class='card-text'><strong>Date:</strong> $formattedDate</p>
                        <p class='card-text'><strong>Time In:</strong> $formattedTimeIn</p>
                    </div>
                    <div class='card-footer d-flex'>
                        <button type='button' class='btn btn-danger mx-1 w-50 archive-btn' 
                            data-attendance-id='{$row['employee_attendance_id']}'
                            data-profile-id='{$row['employee_id']}'
                            data-name='{$row['first_name']} {$row['last_name']}'
                            data-rfid='$rfid'
                            data-date='$formattedDate'
                            data-time-in='$formattedTimeIn'>
                            ARCHIVE
                        </button>
                        <button type='button' class='btn btn-success w-50 mx-1 time-out-btn' 
                            data-attendance-id='{$row['employee_attendance_id']}'
                            data-profile-id='{$row['employee_id']}'
                            data-name='{$row['first_name']} {$row['last_name']}'
                            data-rfid='$rfid'
                            data-img='$img'
                            data-date='{$row['date_att']}'
                            data-time-in='{$row['time_in']}'>
                            TIME OUT
                        </button>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p class='text-center'>No profiles to time out.</p>";
}
?>
