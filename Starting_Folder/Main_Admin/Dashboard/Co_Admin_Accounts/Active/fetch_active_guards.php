<?php

// Start session
session_start();

// BACK-END || dedesignan ang table and same na sila ni search_active_guards

// Including our database
require_once $_SESSION['directory'] . '\Database\dbcon.php';


$stationId = isset($_GET['station_id']) ? $conn->real_escape_string($_GET['station_id']) : '';
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Build the SQL query based on filters
$sql = "SELECT g.guard_id, g.guard_name, g.station_id, s.station_name, ga.username, ga.email, ga.status
        FROM guards g
        JOIN stations s ON g.station_id = s.station_id
        JOIN guard_accounts ga ON ga.guard_id = g.guard_id
        WHERE ga.status = 'ACTIVE'";

if ($stationId != '') {
    $sql .= " AND g.station_id = '$stationId'";
}

if ($search != '') {
    $sql .= " AND g.guard_name LIKE '%$search%'";
}

$sql .= " ORDER BY g.guard_id ASC";


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['guard_id'] . "</td>";
        echo "<td>" . $row['guard_name'] . "</td>";
        echo "<td>" . $row['station_name'] . "</td>";
        echo "<td>
                    <button class='btn btn-info' onclick='openDetailsModal(" . json_encode($row) . ")'>View Details</button>
                    <button class='btn btn-danger' onclick='deactivateGuard(" . $row['guard_id'] . ")'>Deactivate</button>
                </td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No results found</td></tr>";
}

$conn->close();
?>



