<?php
session_start();

// Check if the session variable for the website link is set
if (isset($_SESSION['website_link'])) {
    // Return the website link as a JSON response
    echo json_encode([
        'success' => true,
        'website_link' => $_SESSION['website_link']
    ]);
}
