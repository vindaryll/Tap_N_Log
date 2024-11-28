<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Return JSON response for sections and categories
header('Content-Type: application/json');

// Define the data structure
$data = [
    'sections' => [
        ['value' => 'CO-ADMIN', 'label' => 'CO-ADMIN'],
        ['value' => 'RFID', 'label' => 'RFID'],
        ['value' => 'RECORD', 'label' => 'RECORD'],
        ['value' => 'PERSONAL ACCOUNT', 'label' => 'PERSONAL ACCOUNT']
    ],
    'categories' => [
        'CO-ADMIN' => ['INSERT', 'UPDATE', 'DEACTIVATE', 'REACTIVATE'],
        'RFID' => ['INSERT', 'UPDATE', 'DEACTIVATE', 'REACTIVATE'],
        'RECORD' => ['RESTORE'],
        'PERSONAL ACCOUNT' => ['UPDATE']
    ]
];

// Return data as JSON
echo json_encode($data);