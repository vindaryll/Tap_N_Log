<?php
session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Content-Type: text/html');
    define('UNAUTHORIZED_ACCESS', true);
    require_once $_SESSION['directory'] . '/unauthorized_access.php';
    exit();
}

// Return JSON response for sections and categories
header('Content-Type: application/json');

$station_id = isset($_GET['station_id']) ? $_GET['station_id'] : ''; 

// Define the data structure based on station value
if ($station_id === '') {
    // Default case: when station_id is empty or not set
    $data = [
        'sections' => [
            ['value' => 'CFW', 'label' => 'CASH FOR WORK STAFFS'],
            ['value' => 'OJT', 'label' => 'ON THE JOB TRAINEES'],
            ['value' => 'EMPLOYEES', 'label' => 'EMPLOYEES'],
            ['value' => 'VISITORS', 'label' => 'VISITORS'],
            ['value' => 'ACCOUNTS', 'label' => 'ACCOUNTS'],
            ['value' => 'VEHICLES', 'label' => 'VEHICLES'],
        ],
        'categories' => [
            'CFW' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'OJT' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'EMPLOYEES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'VISITORS' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'VEHICLES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'ACCOUNTS' => ['UPDATE', 'LOGS'],
        ],
    ];
} elseif ($station_id == 1) {
    // Case when station_id is 1
    $data = [
        'sections' => [
            ['value' => 'CFW', 'label' => 'CASH FOR WORK STAFFS'],
            ['value' => 'OJT', 'label' => 'ON THE JOB TRAINEES'],
            ['value' => 'EMPLOYEES', 'label' => 'EMPLOYEES'],
            ['value' => 'VISITORS', 'label' => 'VISITORS'],
            ['value' => 'ACCOUNTS', 'label' => 'ACCOUNTS'],
        ],
        'categories' => [
            'CFW' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'OJT' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'EMPLOYEES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'VISITORS' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'ACCOUNTS' => ['UPDATE', 'LOGS'],
        ],
    ];
} elseif ($station_id == 2) {
    // Case when station_id is 2
    $data = [
        'sections' => [
            ['value' => 'VEHICLES', 'label' => 'VEHICLES'],
            ['value' => 'ACCOUNTS', 'label' => 'ACCOUNTS'],
        ],
        'categories' => [
            'VEHICLES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
            'ACCOUNTS' => ['UPDATE', 'LOGS'],
        ],
    ];
} else {
    // If station_id doesn't match any of the specified values, you can set a default or empty data structure
    $data = [
        'sections' => [],
        'categories' => [],
    ];
}

// Return JSON data
echo json_encode($data);
