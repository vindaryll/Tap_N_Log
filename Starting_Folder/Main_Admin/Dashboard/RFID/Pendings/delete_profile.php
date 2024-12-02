<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_id'])) {
    $profile_id = intval($_POST['profile_id']);
    $response = ['success' => false, 'message' => ''];

    try {
        // Get the profile details to retrieve the image path
        $sql = "SELECT profile_img, first_name, last_name, type_of_profile FROM profile_registration WHERE profile_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $profile_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $profile = $result->fetch_assoc();
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/TAPNLOG/Image/Pending/' . $profile['profile_img']; // Use absolute path

            // Attempt to delete the image file
            if (file_exists($imagePath)) {
                if (unlink($imagePath)) {
                    // File deleted, proceed to delete profile from the database
                    $deleteSql = "DELETE FROM profile_registration WHERE profile_id = ?";
                    $deleteStmt = $conn->prepare($deleteSql);
                    $deleteStmt->bind_param("i", $profile_id);

                    if ($deleteStmt->execute()) {
                        logSystemActivity(
                            $conn,
                            "Profile deleted successfully",
                            "SUCCESS",
                            "Deleted profile ID: " . $profile_id . " - " .
                                $profile['first_name'] . " " . $profile['last_name'] .
                                " (" . $profile['type_of_profile'] . ")"
                        );
                        $response['success'] = true;
                        $response['message'] = 'Profile deleted successfully!';
                    } else {
                        logSystemActivity(
                            $conn,
                            "Failed to delete profile from database",
                            "FAILED",
                            "Profile ID: " . $profile_id . " - Database error: " . $conn->error
                        );
                        $response['message'] = 'Failed to delete profile from the database.';
                    }
                } else {
                    $response['message'] = 'Failed to delete the image file.';
                    error_log("Failed to delete file: $imagePath");
                }
            } else {
                $response['message'] = 'Image file not found.';
                error_log("File not found: $imagePath");
            }
        } else {
            $response['message'] = 'Profile not found.';
        }

        $stmt->close();
    } catch (Exception $e) {
        logSystemActivity(
            $conn,
            "Unexpected error during profile deletion",
            "FAILED",
            "Profile ID: " . $profile_id . " - Error: " . $e->getMessage()
        );
        $response['message'] = 'An unexpected error occurred.';
        error_log("Exception: " . $e->getMessage());
    } finally {
        $conn->close();
    }

    echo json_encode($response);
    exit();
}
