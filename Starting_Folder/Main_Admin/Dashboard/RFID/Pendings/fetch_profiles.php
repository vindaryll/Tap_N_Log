<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to map type abbreviations to full forms
function getProfileTypeFullForm($type) {
    $typeMapping = [
        'CFW' => 'CASH FOR WORK',
        'OJT' => 'ON THE JOB TRAINEE',
        'EMPLOYEE' => 'EMPLOYEE',
    ];
    return $typeMapping[$type] ?? $type; // Return full form if it exists, else return original type
}

// Get and sanitize filter parameters
$type = sanitizeInput($_GET['type'] ?? '');
$search = sanitizeInput($_GET['search'] ?? '');
$from_date = sanitizeInput($_GET['from_date'] ?? '');
$to_date = sanitizeInput($_GET['to_date'] ?? '');

// Build the base SQL query
$sql = "SELECT profile_id, date_att, CONCAT(first_name, ' ', last_name) AS name, type_of_profile
        FROM profile_registration WHERE 1";

// Add type filtering if provided
if (!empty($type)) {
    $sql .= " AND type_of_profile = '" . $conn->real_escape_string($type) . "'";
}

// Add search filtering if provided
if (!empty($search)) {
    $sql .= " AND (first_name LIKE '%" . $conn->real_escape_string($search) . "%' 
                  OR last_name LIKE '%" . $conn->real_escape_string($search) . "%')
                  OR profile_id = '". $conn->real_escape_string($search) ."'";
}

// Add date filtering if both dates are provided
if (!empty($from_date) && !empty($to_date)) {
    $sql .= " AND date_att BETWEEN '" . $conn->real_escape_string($from_date) . "' 
                               AND '" . $conn->real_escape_string($to_date) . "'";
}

// Execute the query
$result = $conn->query($sql);

// Check and output results
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $formattedDate = date('F d, Y', strtotime($row['date_att'])); // Format the date
        $typeFullForm = getProfileTypeFullForm($row['type_of_profile']); // Display type name

        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['profile_id']) . "</td>";
        echo "<td>" . htmlspecialchars($formattedDate) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($typeFullForm) . "</td>";
        echo "<td><button class='btn btn-info' onclick='viewDetails(" . $row['profile_id'] . ")'>View Details</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No results found</td></tr>";
}

$conn->close();
?>