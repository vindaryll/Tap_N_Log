<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Log logout activity
if (isset($_SESSION['guard_id'], $_SESSION['station_id'])) {
    $guard_id = $_SESSION['guard_id'];
    $station_id = $_SESSION['station_id'];
    $guard_name = $_SESSION['name'];
    $station_name = $_SESSION['station_name'];

    // Prepare the activity details for logging
    $details = "Logout for Co-Admin\n\n TERMINATED BY UPDATE\n\nId: $guard_id\nName: $guard_name\nStation: $station_name";

    // SQL to insert the activity log
    $sql = "INSERT INTO activity_log (section, details, category, station_id, guard_id)
               VALUES ('ACCOUNTS', ?, 'LOGS', ?, ?)";

    // Prepare and execute the statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $details, $station_id, $guard_id);
    $stmt->execute();
}

// Unset all variables and destroy the session
session_unset();
session_destroy();

header("Location: /TAPNLOG/index.php");

exit();
?>
