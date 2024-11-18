<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileId = intval(sanitizeInput($_POST['cfw_id']));
    $rfid = isset($_POST['rfid']) ? sanitizeInput($_POST['rfid']) : null;

    try {
        $query = $conn->prepare("SELECT cfw_rfid FROM cfw_profile WHERE cfw_id = ?");
        $query->bind_param("i", $profileId);
        $query->execute();
        $result = $query->get_result();
        $profile = $result->fetch_assoc();

        if ($profile) {
            $assignedRFID = trim($profile['cfw_rfid']); // Clean assigned RFID from DB

            if (!empty($assignedRFID)) {
                if ($rfid && $assignedRFID === $rfid) {
                    echo json_encode([
                        'success' => true,
                        'hasRFID' => true,
                        'match' => true,
                        'rfid' => $assignedRFID
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'hasRFID' => true,
                        'match' => false,
                        'rfid' => $assignedRFID
                    ]);
                }
            } else {
                // staff has no RFID assigned
                echo json_encode(['success' => true, 'hasRFID' => false]);
            }
        } else {
            // staff not found
            echo json_encode(['success' => false, 'message' => 'Profile not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error occurred.', 'error' => $e->getMessage()]);
    }
}
