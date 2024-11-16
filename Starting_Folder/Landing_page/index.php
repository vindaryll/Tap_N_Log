<?php


// GAWAN NG UI, and mahalaga lang rito ay yung href ng <a> tags, kayo na ang bahala kung button ang gagamitin niyo or a tags pa rin

session_start();

// to get the directory path for require purposes
if (!isset($_SESSION['directory']) || !isset($_SESSION['ip_address']) || !isset($_SESSION['website_link'])) {
    header("Location: /tapnlog/index.php");
    exit();
}


// if already logged in, pupunta na sa kani-kanilang dashboard
if (isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /tapnlog/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['record_guard_logged'])) {
    header("Location: /tapnlog/Starting_Folder/Co_Admin/Record_Post/Dashboard/dashboard_home.php");
    exit();
}

if (isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Main_Admin/Dashboard/dashboard_home.php");
    exit();
}


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
        /* Custom SweetAlert2 Facebook Theme */
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
    </style>


    <title>Landing Page</title>
</head>

<body>
    <div class="container mt-5">
        <h2>Welcome to the Landing Page</h2>
        <p>Please select an option:</p>

        <div class="btn-group-vertical d-flex" role="group" aria-label="Button group">
            <a href="../Co_Admin/Vehicle_Post/Auth/login.php" class="btn btn-primary">Vehicle Post</a>
            <a href="../Co_Admin/Record_Post/Auth/login.php" class="btn btn-success">Record Post</a>
            <a href="../Main_Admin/Auth/login.php" class="btn btn-info">Main Admin</a>
            <a href="../RFID_Registration/main_page.php" class="btn btn-danger">Apply for RFID</a>
        </div>

        <!-- Button to trigger the QR code SweetAlert -->
        <div class="text-center mt-4">
            <button id="showQRButton" class="btn btn-secondary">Show QR Code for Website Link</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            // Event listener for the "Show QR Code" button
            $('#showQRButton').click(function() {
                // Fetch the website link from the backend
                $.ajax({
                    url: '/tapnlog/fetch_link.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const websiteLink = response.website_link;

                            // Generate QR code dynamically
                            QRCode.toDataURL(websiteLink, {
                                width: 200,
                                color: {
                                    dark: "#1877f2", // QR code color
                                    light: "#ffffff" // Background color
                                }
                            }, function(error, url) {
                                if (!error) {
                                    Swal.fire({
                                        title: 'Scan QR Code',
                                        html: `
                                            <div style="text-align: center;">
                                                <p>Scan this QR code to access our website.</p>
                                                <img src="${url}" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;">
                                                <p style="margin-top: 10px;">Or type this link manually:</p>
                                                <a href="${websiteLink}" target="_blank" style="color: #1877f2; font-weight: bold;">${websiteLink}</a>
                                            </div>
                                        `,
                                        showConfirmButton: true,
                                        confirmButtonText: 'Close',
                                        showClass: {
                                            popup: `
                                                animate__animated
                                                animate__bounceIn
                                                animate__faster
                                                `
                                        },
                                        hideClass: {
                                            popup: `
                                                animate__animated
                                                animate__bounceOut
                                                animate__faster
                                                `
                                        }
                                    });
                                } else {
                                    console.error("QR Code generation error:", error);
                                    Swal.fire({
                                        title: 'Error',
                                        text: 'Failed to generate QR Code.',
                                        icon: 'error',
                                        confirmButtonText: 'Close',
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: response.message || 'Failed to fetch website link.',
                                icon: 'error',
                                confirmButtonText: 'Close',
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error occurred while fetching the website link.',
                            icon: 'error',
                            confirmButtonText: 'Close',
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>