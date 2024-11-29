<?php

session_start();
// Include database connection

require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

$response = ['success' => false, 'message' => ''];

// Function to sanitize input
function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Validate input
if (isset($_POST['first_name'], $_POST['last_name'], $_POST['type_of_profile'], $_POST['profile_img'])) {
    // Sanitize inputs
    $first_name = sanitizeInput($_POST['first_name']);
    $last_name = sanitizeInput($_POST['last_name']);
    $type_of_profile = sanitizeInput($_POST['type_of_profile']);
    $profile_img = $_POST['profile_img'];

    if (!empty($profile_img)) {
        // Decode the base64 image
        $imageData = explode(',', $profile_img)[1];
        $decodedImage = base64_decode($imageData);

        // Validate image decoding
        if ($decodedImage === false) {
            logSystemActivity(
                $conn,
                "Failed RFID profile registration - Image decode error",
                "FAILED",
                "Type: $type_of_profile, Name: $first_name $last_name"
            );
            $response['message'] = "Failed to decode image.";
            echo json_encode($response);
            exit();
        }

        // Generate a unique image filename
        $uniqueName = uniqid("IMG-", true) . '.png';
        $filePath = "../../Image/Pending/" . $uniqueName;

        // Save the new image to the specified folder
        if (file_put_contents($filePath, $decodedImage) !== false) {
            // Prepare and execute the insert query using $conn
            $stmt = $conn->prepare("INSERT INTO profile_registration (profile_img, type_of_profile, first_name, last_name) VALUES (?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param("ssss", $uniqueName, $type_of_profile, $first_name, $last_name);

                if ($stmt->execute()) {
                    logSystemActivity(
                        $conn,
                        "Successful RFID profile registration",
                        "SUCCESS",
                        "Type: $type_of_profile, Name: $first_name $last_name, Image: $uniqueName"
                    );
                    $response['success'] = true;
                    $response['message'] = "Profile awaiting approval. Please notify the main admin.";
                } else {
                    logSystemActivity(
                        $conn,
                        "Failed RFID profile registration - Database error",
                        "FAILED",
                        "Type: $type_of_profile, Name: $first_name $last_name, Error: " . $stmt->error
                    );
                    $response['message'] = "Failed to register the profile: " . $stmt->error;
                }
                $stmt->close();
            } else {
                logSystemActivity(
                    $conn,
                    "Failed RFID profile registration - SQL preparation error",
                    "FAILED",
                    "Type: $type_of_profile, Name: $first_name $last_name, Error: " . $conn->error
                );
                $response['message'] = "Failed to prepare SQL statement: " . $conn->error;
            }
        } else {
            logSystemActivity(
                $conn,
                "Failed RFID profile registration - Image save error",
                "FAILED",
                "Type: $type_of_profile, Name: $first_name $last_name, Path: $filePath"
            );
            $response['message'] = "Failed to save image.";
        }
    } else {
        logSystemActivity(
            $conn,
            "Failed RFID profile registration - No image",
            "FAILED",
            "Type: $type_of_profile, Name: $first_name $last_name"
        );
        $response['message'] = "No image uploaded.";
    }
} else {
    logSystemActivity(
        $conn,
        "Failed RFID profile registration - Missing fields",
        "FAILED",
        "Missing required fields in form submission"
    );
    $response['message'] = "Missing required fields.";
}

// Close the database connection
$conn->close();

// Return response as JSON
header('Content-Type: application/json'); // Set the content type to JSON
echo json_encode($response);
exit();
