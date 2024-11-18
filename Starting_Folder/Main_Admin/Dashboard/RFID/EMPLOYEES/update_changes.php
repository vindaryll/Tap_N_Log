<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profileId = intval(sanitizeInput($_POST['profileId']));
    $firstName = sanitizeInput($_POST['firstName']);
    $lastName = sanitizeInput($_POST['lastName']);
    $rfid = sanitizeInput($_POST['rfid']);
    $croppedImage = $_POST['croppedImage'] ?? null; // Base64 string
    $adminId = $_SESSION['admin_id']; // Assume this is set when admin is logged in

    // Convert empty RFID to NULL
    $rfid = $rfid === '' ? null : $rfid;

    if (empty($profileId) || empty($firstName) || empty($lastName)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current data for the profile
        $query = $conn->prepare("SELECT first_name, last_name, employee_rfid, employee_img FROM employees_profile WHERE employee_id = ?");
        $query->bind_param("i", $profileId);
        $query->execute();
        $result = $query->get_result();
        $currentData = $result->fetch_assoc();

        if (!$currentData) {
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
            exit();
        }

        $currentRFID = $currentData['employee_rfid'];
        $currentFirstName = $currentData['first_name'];
        $currentLastName = $currentData['last_name'];
        $currentImageName = $currentData['employee_img'];
        $imageDirectory = $_SESSION['directory'] . '/Image/EMPLOYEES/';
        $updates = [];
        $logDetails = [];

        // Build log header
        $updatedName = "$firstName $lastName"; // Reflect updated name
        $logHeader = "Update Employee Profile\n\nId: $profileId\nName: $updatedName\n\n";

        // Merge activity log for name changes
        if ($currentFirstName !== $firstName) {
            $updates[] = "first_name = '$firstName'";
            $logDetails[] = "Set First Name\nFrom: $currentFirstName\nTo: $firstName";
        }
        if ($currentLastName !== $lastName) {
            $updates[] = "last_name = '$lastName'";
            $logDetails[] = "Set Last Name\nFrom: $currentLastName\nTo: $lastName";
        }

        // Handle RFID changes
        if ($currentRFID !== $rfid) {
            if ($currentRFID === null) {
                $logDetails[] = "Add RFID\nFrom: None\nTo: $rfid";
            } elseif ($rfid === null) {
                $logDetails[] = "Remove RFID\nFrom: $currentRFID\nTo: None";
            } else {
                $logDetails[] = "Change RFID\nFrom: $currentRFID\nTo: $rfid";
            }
            $updates[] = "employee_rfid = " . ($rfid ? "'$rfid'" : "NULL");
        }

        // Handle cropped image
        if ($croppedImage) {
            $uniqueName = uniqid("IMG-", true) . '.png';

            // Delete old image if it exists
            if ($currentImageName && file_exists($imageDirectory . $currentImageName)) {
                unlink($imageDirectory . $currentImageName);
            }

            // Save the new cropped image
            $imagePath = $imageDirectory . $uniqueName;
            $croppedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
            file_put_contents($imagePath, $croppedImage);

            $updates[] = "employee_img = '$uniqueName'";
            $logDetails[] = "Change Profile Picture";
        }

        // Update the database
        if (!empty($updates)) {
            $updateSQL = "UPDATE employees_profile SET " . implode(", ", $updates) . " WHERE employee_id = $profileId";
            $conn->query($updateSQL);
        }

        // Insert a single activity log
        if (!empty($logDetails)) {
            $logDetailsText = $logHeader . implode("\n\n", $logDetails);
            $logSQL = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
            $logStmt = $conn->prepare($logSQL);
            $section = 'RFID';
            $category = 'UPDATE';
            $logStmt->bind_param("sssi", $section, $logDetailsText, $category, $adminId);
            $logStmt->execute();
        }

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request!']);
}
