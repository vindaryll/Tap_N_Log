<?php
session_start();
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to map type abbreviations to full forms
function getProfileTypeFullForm($type)
{
    $typeMapping = [
        'CFW' => 'CASH FOR WORK',
        'OJT' => 'ON THE JOB TRAINEE',
        'EMPLOYEE' => 'EMPLOYEE',
    ];
    return $typeMapping[$type] ?? $type; // Return full form if it exists, else return original type
}


// Get input data
$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];
$search = $_POST['search'] ?? '';

$query = "SELECT profile_id, date_att, CONCAT(first_name, ' ', last_name) AS name, type_of_profile FROM profile_registration WHERE 1=1";

// Apply Filters
if (!empty($filters['from_date'])) {
    $from_date = $conn->real_escape_string($filters['from_date']);
    $query .= " AND date_att >= '$from_date'";
}
if (!empty($filters['to_date'])) {
    $to_date = $conn->real_escape_string($filters['to_date']);
    $query .= " AND date_att <= '$to_date'";
}
if (!empty($filters['type_of_profile'])) {
    $type_of_profile = $conn->real_escape_string($filters['type_of_profile']);
    $query .= " AND type_of_profile = '$type_of_profile'";
}

// Apply Search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $query .= " AND (first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR profile_id = '$search')";
}

// Apply Sorting
$order_by = [];
if (!empty($sort['date'])) {
    $order_by[] = "date_att " . strtoupper($sort['date']);
}
if (!empty($sort['name'])) {
    $order_by[] = "name " . strtoupper($sort['name']);
}
if (!empty($order_by)) {
    $query .= " ORDER BY " . implode(", ", $order_by);
}

// Execute the query
$result = $conn->query($query);


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
        echo "<td><button class='btn btn-info w-100 h-100' onclick='viewDetails(" . $row['profile_id'] . ")'>VIEW DETAILS</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No results found</td></tr>";
}

$conn->close();
