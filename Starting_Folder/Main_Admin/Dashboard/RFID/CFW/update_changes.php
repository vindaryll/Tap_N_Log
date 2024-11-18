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

    if (empty($profileId) || empty($firstName) || empty($lastName)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
        exit();
    }

    try {
        $conn->begin_transaction();

        // Fetch current data for the profile
        $query = $conn->prepare("SELECT first_name, last_name, cfw_rfid, cfw_img FROM cfw_profile WHERE cfw_id = ?");
        $query->bind_param("i", $profileId);
        $query->execute();
        $result = $query->get_result();
        $currentData = $result->fetch_assoc();

        if (!$currentData) {
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
            exit();
        }

        $currentRFID = $currentData['cfw_rfid'];
        $currentFirstName = $currentData['first_name'];
        $currentLastName = $currentData['last_name'];
        $currentImageName = $currentData['cfw_img'];
        $imageDirectory = $_SESSION['directory'] . '/Image/CFW/';
        $logEntries = [];
        $updates = [];

        // Merge activity log for name changes
        $nameChanges = [];
        if ($currentFirstName !== $firstName) {
            $updates[] = "first_name = '$firstName'";
            $nameChanges[] = "First Name\n\nFrom: $currentFirstName\nTo: $firstName";
        }
        if ($currentLastName !== $lastName) {
            $updates[] = "last_name = '$lastName'";
            $nameChanges[] = "Last Name\n\nFrom: $currentLastName\nTo: $lastName";
        }
        if (!empty($nameChanges)) {
            // Updated name should reflect the new changes
            $updatedName = "$firstName $lastName";
            $logEntries[] = [
                'section' => 'RFID',
                'details' => "Update Cash for Work Staff Profile\n\nId: $profileId\nName: $updatedName\n\n" . implode("\n\n", $nameChanges),
                'category' => 'UPDATE',
                'admin_id' => $adminId
            ];
        }

        // Handle RFID changes
        if ($currentRFID !== $rfid) {
            if ($currentRFID === null) {
                // Log for adding RFID
                $rfidDetails = "Add RFID\n\nFrom: None\nTo: $rfid";
            } elseif ($rfid === null) {
                // Log for removing RFID
                $rfidDetails = "Remove RFID\n\nFrom: $currentRFID\nTo: None";
            } else {
                // Log for changing RFID
                $rfidDetails = "Change RFID\n\nFrom: $currentRFID\nTo: $rfid";
            }

            $updatedName = "$firstName $lastName"; // Reflect updated name
            $logEntries[] = [
                'section' => 'RFID',
                'details' => "Update Cash for Work Staff Profile\n\nId: $profileId\nName: $updatedName\n\n$rfidDetails",
                'category' => 'UPDATE',
                'admin_id' => $adminId
            ];
            $updates[] = "cfw_rfid = " . ($rfid ? "'$rfid'" : "NULL");
        }

        // Handle cropped image
        if ($croppedImage) {
            // Generate unique name for the new file
            $uniqueName = uniqid("IMG-", true) . '.png';

            // Delete old image if it exists
            if ($currentImageName && file_exists($imageDirectory . $currentImageName)) {
                unlink($imageDirectory . $currentImageName);
            }

            // Save the new cropped image
            $imagePath = $imageDirectory . $uniqueName;
            $croppedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $croppedImage));
            file_put_contents($imagePath, $croppedImage);

            $updates[] = "cfw_img = '$uniqueName'";

            // Log the profile picture change
            $updatedName = "$firstName $lastName"; // Reflect updated name
            $logEntries[] = [
                'section' => 'RFID',
                'details' => "Update Cash for Work Staff Profile\n\nId: $profileId\nName: $updatedName\n\nChange Profile Picture",
                'category' => 'UPDATE',
                'admin_id' => $adminId
            ];
        }

        // Update the database
        if (!empty($updates)) {
            $updateSQL = "UPDATE cfw_profile SET " . implode(", ", $updates) . " WHERE cfw_id = $profileId";
            $conn->query($updateSQL);
        }

        // Insert activity logs
        foreach ($logEntries as $entry) {
            $logSQL = "INSERT INTO admin_activity_log (section, details, category, admin_id) VALUES (?, ?, ?, ?)";
            $logStmt = $conn->prepare($logSQL);
            $logStmt->bind_param("sssi", $entry['section'], $entry['details'], $entry['category'], $entry['admin_id']);
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
