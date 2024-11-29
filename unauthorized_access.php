<?php
// Check if this file is being accessed directly
if (!defined('UNAUTHORIZED_ACCESS')) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: url('/tapnlog/image/logo_and_icons/bsu-bg.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .error-container {
            background: rgba(255, 255, 255, 0.33);
            backdrop-filter: blur(2.8px);
            -webkit-backdrop-filter: blur(2.8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            text-align: center;
            max-width: 400px;
        }
        .error-icon {
            font-size: 48px;
            margin-bottom: 1rem;
            color: #dc3545;
        }
        .error-title {
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-message {
            color: #4a4a4a;
            margin-bottom: 1.5rem;
        }
        .back-button {
            background-color: #1877f2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .back-button:hover {
            background-color: #145dbd;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1 class="error-title">Unauthorized Access</h1>
            <p class="error-message">You don't have permission to access this resource. Please log in with appropriate credentials.</p>
            <a href="/TAPNLOG/Starting_Folder/Landing_page/index.php" class="back-button">Back to Login</a>
        </div>
    </div>
</body>
</html><?php exit(); ?>
