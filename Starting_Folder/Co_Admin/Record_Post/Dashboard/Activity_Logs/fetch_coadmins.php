<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';


$station_id = 1; // RECORD POST
$sql = "
    SELECT 
        ga.guard_id, 
        CONCAT(ga.guard_id, ' - ', g.guard_name) AS co_admin 
    FROM guard_accounts ga
    JOIN guards g ON ga.guard_id = g.guard_id
    WHERE g.station_id = ? AND ga.status = 'ACTIVE'
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();

// Generate options for the select dropdown
$options = '<option value="">ALL</option>'; // Default option
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= '<option value="' . $row['guard_id'] . '">' . htmlspecialchars($row['co_admin']) . '</option>';
    }
}

$stmt->close();
$conn->close();

// Output options for the dropdown
echo $options;
