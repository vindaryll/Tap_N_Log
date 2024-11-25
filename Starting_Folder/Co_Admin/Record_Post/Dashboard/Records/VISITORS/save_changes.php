<?php
session_start();

// Check if the guard is logged in
if (!isset($_SESSION['record_guard_logged'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $record_id = intval(sanitizeInput($_POST['record_id']));
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $phone_number = sanitizeInput($_POST['phone_number']);
    $pass = !empty($_POST['pass']) ? sanitizeInput($_POST['pass']) : null;
    $purpose = sanitizeInput($_POST['purpose']);
    $station_id = $_SESSION['station_id']; // Assume station ID is stored in session
    $guard_id = $_SESSION['guard_id'];     // Assume guard ID is stored in session

    if (empty($record_id) || empty($first_name) || empty($last_name)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current data for the visitor
        $query = $conn->prepare("SELECT first_name, last_name, phone_num, visitor_pass, purpose FROM visitors WHERE visitor_id = ?");
        $query->bind_param("i", $record_id);
        $query->execute();
        $result = $query->get_result();
        $currentData = $result->fetch_assoc();

        if (!$currentData) {
            echo json_encode(['success' => false, 'message' => 'Visitor not found.']);
            exit();
        }

        $currentFirstName = $currentData['first_name'];
        $currentLastName = $currentData['last_name'];
        $currentPhoneNumber = $currentData['phone_num'];
        $currentVisitorPass = $currentData['visitor_pass'];
        $currentPurpose = $currentData['purpose'];
        $updates = [];
        $logDetails = [];

        // Updated name for the log
        $updatedName = "$first_name $last_name";

        // Build log header
        $logHeader = "Update Visitor Details\n\nRecord Id: $record_id\nName: $updatedName\n\n";

        // Check changes and prepare updates
        if ($currentFirstName !== $first_name) {
            $updates[] = "first_name = '$first_name'";
            $logDetails[] = "Set First Name\nFrom: $currentFirstName\nTo: $first_name";
        }

        if ($currentLastName !== $last_name) {
            $updates[] = "last_name = '$last_name'";
            $logDetails[] = "Set Last Name\nFrom: $currentLastName\nTo: $last_name";
        }

        if ($currentPhoneNumber !== $phone_number) {
            $updates[] = "phone_num = '$phone_number'";
            $logDetails[] = "Set Phone Number\nFrom: $currentPhoneNumber\nTo: $phone_number";
        }

        if ($currentVisitorPass !== $pass) {
            if (empty($currentVisitorPass)) {
                $logDetails[] = "Add Visitor Pass\nFrom: None\nTo: $pass";
            } elseif (empty($pass)) {
                $logDetails[] = "Remove Visitor Pass\nFrom: $currentVisitorPass\nTo: None";
            } else {
                $logDetails[] = "Set Visitor Pass\nFrom: $currentVisitorPass\nTo: $pass";
            }
            $updates[] = "visitor_pass = " . ($pass ? "'$pass'" : "NULL");
        }

        if ($currentPurpose !== $purpose) {
            $updates[] = "purpose = '$purpose'";
            $logDetails[] = "Set Purpose\nFrom: $currentPurpose\nTo: $purpose";
        }

        // Update the database
        if (!empty($updates)) {
            $updateSQL = "UPDATE visitors SET " . implode(", ", $updates) . " WHERE visitor_id = $record_id";
            $conn->query($updateSQL);
        }

        // Insert activity log
        if (!empty($logDetails)) {
            $logDetailsText = $logHeader . implode("\n\n", $logDetails);
            $logSQL = "INSERT INTO activity_log (section, details, category, station_id, guard_id) VALUES (?, ?, ?, ?, ?)";
            $logStmt = $conn->prepare($logSQL);
            $section = 'VISITORS';
            $category = 'UPDATE';
            $logStmt->bind_param("sssii", $section, $logDetailsText, $category, $station_id, $guard_id);
            $logStmt->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Details updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request!']);
}
