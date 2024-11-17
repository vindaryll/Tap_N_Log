<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

// Include the database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Check if the required fields are set in the POST request
if (!isset($_POST['profile_id'], $_POST['first_name'], $_POST['last_name'], $_POST['type_of_profile'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit();
}

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Retrieve and sanitize inputs
$profile_id = intval($_POST['profile_id']);
$first_name = sanitizeInput($_POST['first_name']);
$last_name = sanitizeInput($_POST['last_name']);
$type_of_profile = sanitizeInput($_POST['type_of_profile']);

// Validate inputs
if (empty($first_name) || !preg_match("/^[A-Za-z.\-'\s]+$/", $first_name)) {
    echo json_encode(['success' => false, 'message' => 'Invalid first name.']);
    exit();
}

if (empty($last_name) || !preg_match("/^[A-Za-z.\-'\s]+$/", $last_name)) {
    echo json_encode(['success' => false, 'message' => 'Invalid last name.']);
    exit();
}

$valid_profile_types = ['OJT', 'CFW', 'EMPLOYEE'];
if (empty($type_of_profile) || !in_array($type_of_profile, $valid_profile_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid profile type.']);
    exit();
}

// Fetch the current profile details from the database
$fetchQuery = "SELECT first_name, last_name, type_of_profile FROM profile_registration WHERE profile_id = ?";
$stmt = $conn->prepare($fetchQuery);
$stmt->bind_param("i", $profile_id);
$stmt->execute();
$result = $stmt->get_result();
$currentProfile = $result->fetch_assoc();

if (!$currentProfile) {
    echo json_encode(['success' => false, 'message' => 'Profile not found.']);
    exit();
}

// Check if there are changes
if (
    $currentProfile['first_name'] === $first_name &&
    $currentProfile['last_name'] === $last_name &&
    $currentProfile['type_of_profile'] === $type_of_profile
) {
    echo json_encode(['success' => false, 'message' => 'No changes detected.']);
    exit();
}

// Update the profile in the database
$updateQuery = "UPDATE profile_registration SET first_name = ?, last_name = ?, type_of_profile = ? WHERE profile_id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("sssi", $first_name, $last_name, $type_of_profile, $profile_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile.']);
}

$stmt->close();
$conn->close();
