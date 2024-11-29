<?php

session_start();

// to get the directory path for require purposes
if (!isset($_SESSION['directory']) || !isset($_SESSION['ip_address']) || !isset($_SESSION['website_link'])) {
    header("Location: /TAPNLOG/Starting_Folder/index.php");
    exit();
}

// if already logged in, pupunta na sa kani-kanilang dashboard
if (isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['record_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Main_Admin/Dashboard/dashboard_home.php");
    exit();
}

// Include system log helper
require_once $_SESSION['directory'] . '\Database\dbcon.php';
require_once $_SESSION['directory'] . '\Database\system_log_helper.php';

// Log successful landing page access
logSystemActivity(
    $conn,
    "Landing page access",
    "SUCCESS",
    "User accessed the landing page"
);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Jquery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <style>
        /* Keeping existing SweetAlert2 styles */
        .swal2-popup {
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            background-color: #ffffff;
        }

        .swal2-html-container {
            color: #4a4a4a !important;
            font-size: 16px;
        }

        .swal2-confirm {
            background-color: #1877f2 !important;
            color: #ffffff !important;
            border-radius: 8px;
            padding: 10px 20px;
        }

        .swal2-confirm:hover {
            background-color: #145dbd !important;
        }

        body {
            min-height: 100vh;
            background: url('/tapnlog/image/logo_and_icons/bsu-bg.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
        }

        /* Glassmorphism effect */
        .glass-container {
            background: rgba(255, 255, 255, 0.33);
            backdrop-filter: blur(2.8px);
            -webkit-backdrop-filter: blur(2.8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }

        .landing-container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .logo-container {
            text-align: center;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
            width: clamp(150px, 80%, 300px);
        }

        .logo-container h1 {
            color: #1877f2;
            font-family: 'Segoe UI', Arial, sans-serif;
            font-weight: 700;
            font-size: clamp(1.5rem, 5vw, 2.5rem);
            margin-top: 1rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .btn-custom {
            width: 100%;
            margin: 0.5rem 0;
            padding: 1rem;
            border-radius: 50px;
            font-weight: 600;
            background-color: #1877f2;
            border: none;
            color: white;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            background-color: #145dbd;
        }

        /* Add specific styling for the QR button */
        #showQRButton {
            width: 80%;
            /* Make button 10% smaller */
            margin: 0.5rem auto;
            /* Center the smaller button */
        }

        @media (max-width: 768px) {
            .glass-container {
                padding: 1rem;
            }

            .logo-container {
                padding: 1rem;
            }

            .logo-container h1 {
                font-size: 2rem;
            }
        }
    </style>

    <title>Landing Page</title>
</head>

<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="glass-container py-xl-5 py-lg-5">
            <div class="row landing-container">
                <!-- Left Panel with Logo -->
                <div class="col-md-6 logo-container">
                    <img src="/tapnlog/image/logo_and_icons/logo_icon.png" alt="TAP-N-LOG Logo" class="img-fluid">
                    <h1>TAP-N-LOG</h1>
                </div>

                <!-- Right Panel with Buttons -->
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <a href="../Main_Admin/Auth/login.php" class="btn btn-custom">MAIN ADMIN</a>
                    <a href="../Co_Admin/Vehicle_Post/Auth/login.php" class="btn btn-custom">VEHICLE POST</a>
                    <a href="../Co_Admin/Record_Post/Auth/login.php" class="btn btn-custom">RECORD POST</a>
                    <a href="../RFID_Registration/main_page.php" class="btn btn-custom">RFID REGISTRATION</a>
                    <button id="showQRButton" class="btn btn-custom mt-4">WEBSITE LINK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Event listener for the "Show QR Code" button
            $('#showQRButton').click(function() {
                // Fetch the website link from the backend
                $.ajax({
                    url: '/tapnlog/fetch_link.php', // Ensure this endpoint is correct
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.website_link) {
                            const websiteLink = response.website_link;

                            // Generate QR code dynamically
                            QRCode.toDataURL(
                                websiteLink, {
                                    width: 200,
                                    color: {
                                        dark: "#1877f2", // QR code color
                                        light: "#ffffff", // Background color
                                    },
                                },
                                function(error, url) {
                                    if (!error) {
                                        Swal.fire({
                                            title: 'Scan this QR Code',
                                            html: `
                                    <div style="text-align: center;">
                                        <img src="${url}" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;">
                                        <p style="margin-top: 10px;">Or type this link:</p>
                                        <a href="${websiteLink}" target="_blank" style="color: #1877f2; font-weight: bold;">${websiteLink}</a>
                                    </div>
                                `,
                                            showConfirmButton: true,
                                            confirmButtonText: 'Close',
                                            showClass: {
                                                popup: 'animate__animated animate__zoomIn animate__faster',
                                            },
                                            hideClass: {
                                                popup: 'animate__animated animate__zoomOut animate__faster',
                                            },
                                        });
                                    } else {
                                        console.error("QR Code generation error:", error);
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Failed to generate QR Code.',
                                            icon: 'error',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });
                                    }
                                }
                            );
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message || 'Failed to fetch website link.',
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error occurred while fetching the website link.',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
                });
            });
        });
    </script>
</body>

</html>