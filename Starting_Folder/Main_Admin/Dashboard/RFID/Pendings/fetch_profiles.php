<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Get filter parameters
$type = $_GET['type'] ?? '';
$search = $_GET['search'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

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
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['profile_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_att']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['type_of_profile']) . "</td>";
        echo "<td><button class='btn btn-info' onclick='viewDetails(" . $row['profile_id'] . ")'>View Details</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No results found</td></tr>";
}

$conn->close();
?>