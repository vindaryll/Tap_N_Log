<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

if (!isset($_SESSION['record_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (isset($_SESSION['vehicle_guard_logged']) || isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
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

    <!-- Real time session checker -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\status_script.php'; ?>

    <title>Attendance Log - Cash for work | Co-Admin for Record Post</title>
    <style>
        /* BACK BUTTON */
        .back-icon {
            color: #1877f2;
            font-size: 2rem;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .back-icon:hover {
            color: #145dbf;
            transform: scale(1.1);
        }

        /* END FOR BACK BUTTON */


        /* Hidden input */
        #rfidInput {
            opacity: 0;
            position: absolute;
        }

        /* Submit button text */
        #submitButton {
            font-size: 3rem;
            font-weight: bold;
            background: none;
            border: none;
            cursor: context-menu;
            color: #000;
        }

        #form-container {
            position: absolute;
            top: 45%;
        }

        #form-container button {
            color: #1877f2;
        }


        /* FOR MODAL MODAL */
        .profile-image-container {
            width: 100%;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
            position: relative;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .profile-image-container img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>

    <div class="container-fluid vh-100 p-0 m-0">

        <!-- Nav Bar -->
        <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Record_Post\Dashboard\navbar.php'; ?>

        <!-- START OF CONTAINER -->
        <div class="d-flex justify-content-center px-2">

            <div class="container-fluid row p-0">
                <div class="container col-sm-12">
                    <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
                </div>

                <div class="container-fluid col-sm-12 mt-sm-1 mt-5">
                    <h2 class="text-center w-100">CASH FOR WORK ATTENDANCE LOG</h2>
                </div>
            </div>
        </div>

        <div id="form-container" class="col-12 d-flex align-items-center p-0 m-0">
            <form id="time_in" class="w-100 text-center">
                <input type="text" id="rfidInput" autofocus>
                <button type="submit" id="submitButton" class="shadoww text-stroke">PLEASE TAP YOUR RFID FOR <span style="color: green;">TIME-IN</span></button>
            </form>
        </div>

    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="ProfileDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ProfileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">APPROVE ATTENDANCE FOR <strong>TIME-IN</strong></h5>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <!-- Profile Image Column (1:1 Ratio) -->
                        <div class="col-lg-6 d-flex justify-content-center align-items-center">
                            <div class="profile-image-container">
                                <img id="modal_1_profileImg" src="/tapnlog/Image/LOGO_AND_ICONS/default_avatar.png" alt="Profile Picture" class="img-thumbnail">
                            </div>
                        </div>

                        <!-- Details Column -->
                        <div class="col-lg-6">
                            <form id="profileForm">

                                <!-- RFID number -->
                                <div class="mb-3">
                                    <label for="modal_1_rfid" class="form-label">RFID Number</label>
                                    <input type="text" class="form-control" id="modal_1_rfid" name="rfid" disabled>
                                </div>

                                <!-- Type of profile -->
                                <div class="mb-3">
                                    <label for="modal_1_firstName" class="form-label">TYPE OF PROFILE</label>
                                    <input type="text" class="form-control" value="CASH FOR WORK" disabled>
                                </div>

                                <!-- Full name -->
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label">NAME</label>
                                    <input type="text" class="form-control" id="modal_1_name" name="name" disabled>
                                </div>

                                <div class="row">
                                    <!-- Date -->
                                    <div class="col-lg-6">
                                        <label for="modal_1_date" class="form-label">DATE</label>
                                        <input type="text" class="form-control" id="modal_1_date" name="date" disabled>
                                    </div>

                                    <!-- Time -->
                                    <div class="col-lg-6">
                                        <label for="modal_1_date" class="form-label">TIME-IN</label>
                                        <input type="text" class="form-control" id="modal_1_time" name="time" disabled>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="container d-flex justify-content-around align-items-center p-0">
                        <div class="row w-100 p-0">

                            <!-- For Edit Buttons -->
                            <div id="cancel_cont" class="col-md-6 col-sm-12 p-1">
                                <button type="button" class="btn btn-danger w-100" id="cancelBtn">CANCEL</button>
                            </div>
                            <div id="saveBtn_cont" class="col-md-6 col-sm-12 p-1">
                                <button type="button" class="btn btn-success w-100" id="saveBtn">TIME-IN</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            // Keep the RFID input focused
            $('#rfidInput').focus();

            // Refocus if focus is lost
            $(document).on('click', function() {
                $('#rfidInput').focus();
            });

            let datePassing = null;
            let timePassing = null;

            // Prevent form submission from reloading the page
            $('#time_in').on('submit', function(event) {
                event.preventDefault();

                const rfidValue = $('#rfidInput').val(); // Get RFID input value
                if (!rfidValue) {
                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'Please input your RFID',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }


                // Get current date and time from the laptop
                const currentDate = new Date();
                datePassing = currentDate.toLocaleDateString("en-CA");
                timePassing = currentDate.toLocaleTimeString("en-CA", {
                    hour12: false
                });

                // FOR DISPLAY ONLY
                const formattedDate = new Intl.DateTimeFormat('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                }).format(currentDate); // "November 2, 2024"
                const formattedTime = new Intl.DateTimeFormat('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }).format(currentDate); // "1:40 AM"

                // AJAX call to check RFID
                $.ajax({
                    url: 'check_rfid.php', // Replace with the actual path to the PHP file
                    type: 'POST',
                    data: {
                        rfid: rfidValue,
                        client_date: datePassing,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                            $('#rfidInput').val('').focus(); // Clear input
                        } else {
                            // Populate modal with profile data
                            const profile = response.profile;

                            // STORED VALUES TO PREVENT MANIPULATION
                            current = {
                                profile_id: profile.id,
                                rfid: profile.rfid,
                                name: profile.name,
                                method: 'RFID'
                            };
                            
                            console.log(current);

                            $('#modal_1_profileImg').attr('src', profile.image || '/tapnlog/image/logo_and_icons/default_avatar.png');
                            $('#modal_1_rfid').val(profile.rfid);
                            $('#modal_1_name').val(profile.name);

                            $('#modal_1_date').val(formattedDate);
                            $('#modal_1_time').val(formattedTime);

                            $('#ProfileDetailsModal').modal('show'); // Show modal
                        }
                    },
                    error: function() {
                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: 'Error checking RFID. Please try again.',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        $('#rfidInput').val('').focus();
                    }
                });
            });

            $('#cancelBtn').on('click', function() {
                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to CANCEL the TIME-IN attendance of this profile?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#ProfileDetailsModal').modal('hide');
                        $('#rfidInput').val('').focus();
                    }
                });
            });

            $('#saveBtn').on('click', function() {
                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to APPROVE the TIME-IN attendance of this profile?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const profile_id = current.profile_id;
                        const rfid = current.rfid || 'None';
                        const name = current.name;
                        const method = current.method;
                        const date = datePassing; 
                        const time = timePassing;

                        $.ajax({
                            url: '../save_time_in.php',
                            type: 'POST',
                            data: {
                                profile_id: profile_id,
                                rfid: rfid,
                                name: name,
                                method: method,
                                date: date,
                                time: time
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                    $('#ProfileDetailsModal').modal('hide');
                                    $('#rfidInput').val('').focus(); // Reset the RFID input
                                } else {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                        timer: 2000,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX Error:', status, error, xhr.responseText);
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'Failed to update time-in. Please try again.',
                                    icon: 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>