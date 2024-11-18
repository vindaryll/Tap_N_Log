<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileId = intval(sanitizeInput($_POST['ojt_id']));
    $rfid = sanitizeInput($_POST['rfid']);

    $query = $conn->prepare("SELECT ojt_rfid FROM ojt_profile WHERE ojt_id = ? AND ojt_rfid = ?");
    $query->bind_param("is", $profileId, $rfid);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>
