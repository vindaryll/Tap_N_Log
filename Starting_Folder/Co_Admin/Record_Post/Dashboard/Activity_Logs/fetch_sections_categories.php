<?php
session_start();

if (!isset($_SESSION['record_guard_logged'])) {
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
        ['value' => 'CFW', 'label' => 'CASH FOR WORK STAFFS'],
        ['value' => 'OJT', 'label' => 'ON THE JOB TRAINEES'],
        ['value' => 'EMPLOYEES', 'label' => 'EMPLOYEES'],
        ['value' => 'VISITORS', 'label' => 'VISITORS'],
        ['value' => 'ACCOUNTS', 'label' => 'ACCOUNTS']
    ],
    'categories' => [
        'CFW' => ['INSERT', 'UPDATE', 'ARCHIVE'],
        'OJT' => ['INSERT', 'UPDATE', 'ARCHIVE'],
        'EMPLOYEES' => ['INSERT', 'UPDATE', 'ARCHIVE'],
        'VISITORS' => ['INSERT', 'UPDATE', 'ARCHIVE'],
        'ACCOUNTS' => ['UPDATE', 'LOGS'],
    ],
];

// Return JSON data
echo json_encode($data);
