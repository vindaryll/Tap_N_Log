<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Check if the guard is logged in
if (isset($_SESSION['guard_id'])) {
    $coadmin_id = $_SESSION['guard_id'];
    $current_station_id = $_SESSION['station_id'];

    // Prepare SQL query to check guard status and station ID from the 'guards' table
    $sql = "SELECT ga.status, g.station_id 
        FROM guard_accounts ga
        JOIN guards g ON ga.guard_id = g.guard_id
        WHERE ga.guard_id = ? LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $coadmin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the guard exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the station ID has changed
        if ($row['station_id'] !== $current_station_id) {
            echo json_encode(['status' => 'INACTIVE']);
            exit();
        }

        // Return status as JSON response
        echo json_encode(['status' => $row['status']]);
    } else {
        echo json_encode(['status' => 'INACTIVE']);
    }

    $stmt->close();
} else {
    // No session, guard is logged out, send inactive response
    echo json_encode(['status' => 'INACTIVE']);
}

// Close the database connection
mysqli_close($conn);
