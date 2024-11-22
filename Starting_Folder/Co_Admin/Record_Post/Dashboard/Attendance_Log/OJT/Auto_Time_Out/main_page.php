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

    <title>Attendance Log - On the job trainees | Co-Admin for Record Post</title>
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
                    <h2 class="text-center w-100">ON THE JOB TRAINEES ATTENDANCE LOG</h2>
                </div>
            </div>
        </div>

        <div id="form-container" class="col-12 d-flex align-items-center p-0 m-0">
            <form id="time_out" class="w-100 text-center">
                <input type="text" id="rfidInput" autofocus>
                <button type="submit" id="submitButton">PLEASE TAP YOUR RFID FOR <span style="color:red">TIME-OUT</span></button>
            </form>
        </div>

    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="ProfileDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ProfileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">APPROVE ATTENDANCE FOR <strong>TIME-OUT</strong></h5>
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
                                    <input type="text" class="form-control" value="ON THE JOB TRAINEE" disabled>
                                </div>

                                <!-- Full name -->
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label">NAME</label>
                                    <input type="text" class="form-control" id="modal_1_name" name="name" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="modal_1_date" class="form-label">DATE</label>
                                    <input type="text" class="form-control" id="modal_1_date" name="date" disabled>
                                </div>

                                <div class="row">
                                    <!-- Date -->
                                    <div class="col-lg-6">
                                        <label for="modal_1_date" class="form-label">TIME IN</label>
                                        <input type="text" class="form-control" id="modal_1_timeIn" name="time1" disabled>
                                    </div>

                                    <!-- Time -->
                                    <div class="col-lg-6">
                                        <label for="modal_1_date" class="form-label">TIME OUT</label>
                                        <input type="text" class="form-control" id="modal_1_timeOut" name="time2" disabled>
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
                                <button type="button" class="btn btn-success w-100" id="saveBtn">TIME-OUT</button>
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
            let timepassing = null;

            // Prevent form submission from reloading the page
            $('#time_out').on('submit', function(event) {
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
                const formattedTimeOut = new Intl.DateTimeFormat('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }).format(currentDate); // "1:40 AM"


                // AJAX call to check RFID
                $.ajax({
                    url: 'check_rfid.php',
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

                            const fetchedTime = profile.time_in;
                            const [hours, minutes] = fetchedTime.split(':'); // Split into hours and minutes
                            let period = "AM";
                            let hour12 = parseInt(hours, 10);

                            if (hour12 >= 12) {
                                period = "PM";
                                hour12 = hour12 > 12 ? hour12 - 12 : hour12; // Convert to 12-hour format
                            } else if (hour12 === 0) {
                                hour12 = 12; // Handle midnight case
                            }

                            const formattedTimeIn = `${hour12}:${minutes} ${period}`;

                            // STORED VALUES TO PREVENT MANIPULATION
                            current = {
                                attendance_id: profile.attendance_id,
                                profile_id: profile.id,
                                rfid: profile.rfid,
                                name: profile.name,
                                method: 'RFID'
                            };

                            $('#modal_1_profileImg').attr('src', profile.image || '/tapnlog/image/logo_and_icons/default_avatar.png');
                            $('#modal_1_rfid').val(profile.rfid);
                            $('#modal_1_name').val(profile.name);
                            $('#modal_1_date').val(formattedDate);
                            $('#modal_1_timeIn').val(formattedTimeIn);
                            $('#modal_1_timeOut').val(formattedTimeOut);

                            $('#ProfileDetailsModal').modal('show'); // Show modal
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error, xhr.responseText);
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
                    text: `Do you want to CANCEL the TIME-OUT attendance of this profile?`,
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

                // FOR ACTIVITY LOGS
                const currentDate = new Date();
                const formattedDate = new Intl.DateTimeFormat('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                }).format(currentDate);
                
                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to APPROVE the TIME-OUT attendance of this profile?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Prepare data to send
                        const attendanceData = {
                            attendance_id: current.attendance_id, 
                            profile_id: current.profile_id, 
                            rfid: current.rfid, 
                            name: current.name, 
                            date: formattedDate,
                            time_out: timePassing, // Current time for time-out
                            method: current.method, 
                        };

                        // AJAX request
                        $.ajax({
                            url: '../save_time_out.php', // Backend script to handle time-out updates
                            type: 'POST',
                            data: attendanceData,
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                    $('#ProfileDetailsModal').modal('hide'); // Close the modal
                                    $('#rfidInput').val('').focus(); // Reset the RFID input
                                } else {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                        timer: 1500,
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
                                    text: 'Failed to update time-out. Please try again.',
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