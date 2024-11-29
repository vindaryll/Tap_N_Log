<?php
session_start();

if (!isset($_SESSION['vehicle_guard_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Return JSON response for sections and categories
header('Content-Type: application/json');

// Define sections and categories
$data = [
    'sections' => [
        ['value' => 'VEHICLES', 'label' => 'VEHICLES'],
        ['value' => 'ACCOUNTS', 'label' => 'ACCOUNTS']
    ],
    'categories' => [
        'VEHICLES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
        'ACCOUNTS' => ['UPDATE', 'LOGS'],
    ],
];

// Return JSON data
echo json_encode($data);
