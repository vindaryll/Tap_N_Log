<?php

// Start session
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Including our database
require_once $_SESSION['directory'] . '\Database\dbcon.php';

function sanitizeInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

$filters = $_POST['filters'] ?? [];
$sort = $_POST['sort'] ?? [];
$search = sanitizeInput($_POST['search'] ?? '');

$sql = "SELECT g.guard_id, g.guard_name, g.station_id, s.station_name, ga.username, ga.email, ga.status, ga.date_created
        FROM guards g
        JOIN stations s ON g.station_id = s.station_id
        JOIN guard_accounts ga ON ga.guard_id = g.guard_id
        WHERE ga.status = 'ACTIVE'";

if (!empty($filters['fromDate'])) {
    $fromDate = $conn->real_escape_string($filters['fromDate']);
    $sql .= " AND ga.date_created >= '$fromDate'";
}
if (!empty($filters['toDate'])) {
    $toDate = $conn->real_escape_string($filters['toDate']);
    $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));
    $sql .= " AND ga.date_created <= '$toDate'";
}
if (!empty($filters['station'])) {
    $station = $conn->real_escape_string($filters['station']);
    $sql .= " AND g.station_id = '$station'";
}
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $sql .= " AND (g.guard_name LIKE '%$search%' OR g.guard_id LIKE '%$search%')";
}

$order_by = [];
if (!empty($sort['date'])) {
    $order_by[] = "ga.date_created " . strtoupper($sort['date']);
}
if (!empty($sort['name'])) {
    $order_by[] = "g.guard_name " . strtoupper($sort['name']);
}

if (!empty($order_by)) {
    $sql .= " ORDER BY " . implode(", ", $order_by);
} else {
    $sql .= " ORDER BY g.guard_id ASC";
}


$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $formattedDate = date("F j, Y", strtotime($row['date_created']));

        echo "<tr>";
        echo "<td>" . $row['guard_id'] . "</td>";
        echo "<td>" . $formattedDate . "</td>";
        echo "<td>" . $row['guard_name'] . "</td>";
        echo "<td>" . $row['station_name'] . "</td>";
        echo "<td>
                <div class='row d-flex justify-content-center align-items-center m-0 p-0'>
                    <div class='col-lg-6 my-1'>
                        <button class='btn btn-info btn-custom w-100 h-100 p-2' onclick='openDetailsModal(" . json_encode($row) . ")'>VIEW DETAILS</button>
                    </div>
                    <div class='col-lg-6 my-1'>
                        <button class='btn btn-danger btn-custom w-100 h-100 p-2' onclick='deactivateGuard(" . $row['guard_id'] . ")'>DEACTIVATE</button>
                    </div>
                </div>
            </td>";

        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No results found</td></tr>";
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


</body>

</html>