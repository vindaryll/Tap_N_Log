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

    <!-- Cropper.js CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <title>Attendance Log - Employees | Co-Admin for Record Post</title>
    <style>
        /* CARD CONTAINER FOR PROFILES */
        #profile-container {
            height: 475px;
            overflow-y: auto;

        }

        /* General card styling that applies to all sizes */
        .card-container .card {
            margin-bottom: 20px;
            /* Adds space between cards */
            transition: transform 0.3s;
            /* Smooth transform effect for hover */
        }

        .card-container .card:hover {
            transform: scale(1.03);
            /* Slightly enlarges the card on hover */
        }

        /* END FOR PROFILE CONTAINER */

        /* FOR MODAL EDIT MODAL */
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

        /* Max Height for Modal to Prevent Overflow */
        .modal-dialog {
            max-height: 90vh;
        }

        .modal-content {
            overflow-y: auto;
        }

        /* END FOR MODALS */

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
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Record_Post\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container-fluid row col-sm-12 px-2">

            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-0 mt-4 p-0">
                <div class="container mt-3 p-0">
                    <h2 class="text-center w-100">EMPLOYEES ATTENDANCE LOG</h2>
                    <div class="mb-3">
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name or RFID">
                    </div>

                    <!-- Filter and Sort Buttons -->
                    <div class="w-100 d-flex justify-content-start p-0 mb-3">
                        <div class="col-md-6 col-12 m-0 p-0">
                            <div class="w-100 d-flex justify-content-start p-0 m-0">
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">FILTER</button>
                                </div>
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#sortModal">SORT</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">

                        <!-- Profiles Section -->
                        <div id="profile-container" class="row d-flex justify-content-center p-0">
                            <!-- Profile Cards will be inserted here dynamically -->
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>



    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Profiles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="rfidFilter" class="form-label">RFID Status</label>
                            <select id="rfidFilter" class="form-select">
                                <option value="">BOTH</option>
                                <option value="with_rfid">WITH RFID</option>
                                <option value="without_rfid">WITHOUT RFID</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="resetFilters">Reset</button>
                    <button type="button" class="btn btn-primary" id="applyFilters">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sort Modal -->
    <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sortModalLabel">Sort Profiles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mt-3">
                        <label class="form-label">SORT BY TIME:</label>
                        <div>
                            <input type="radio" id="sortTimeInAsc" name="sortTimeIn" value="asc">
                            <label for="sortTimeInAsc">EARLIEST FIRST</label><br>

                            <input type="radio" id="sortTimeInDesc" name="sortTimeIn" value="desc">
                            <label for="sortTimeInDesc">LATEST FIRST</label>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">SORT BY NAME:</label>
                        <div>
                            <input type="radio" id="sortNameAsc" name="sortName" value="asc">
                            <label for="sortNameAsc">A-Z</label><br>

                            <input type="radio" id="sortNameDesc" name="sortName" value="desc">
                            <label for="sortNameDesc">Z-A</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="resetSort">Reset</button>
                    <button type="button" class="btn btn-primary" id="applySort">Apply</button>
                </div>
            </div>
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
                                    <input type="text" class="form-control" value="EMPLOYEE" disabled>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        $(document).ready(function() {


            // BACK BUTTON
            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            let datePassing = null;
            let timePassing = null;

            // START

            let filters = {};
            let sort = {};
            let search = '';

            function fetchProfiles() {
                $.ajax({
                    url: 'fetch_profiles.php',
                    method: 'POST',
                    data: {
                        filters: filters,
                        sort: sort,
                        search: search,
                    },
                    success: function(data) {
                        $('#profile-container').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching profiles:", error);
                    },
                });
            }

            // Live Search
            $('#searchTextbox').on('keyup', function() {
                search = $(this).val().trim();
                fetchProfiles();
            });

            // Apply Filters
            $('#applyFilters').on('click', function() {
                filters = {
                    rfid_filter: $('#rfidFilter').val(),
                };
                fetchProfiles();
                $('#filterModal').modal('hide');
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                filters = {};
                $('#filterForm')[0].reset();
                fetchProfiles();
            });

            // Apply Sort
            $('#applySort').on('click', function() {
                sort = {
                    time_in: $('input[name="sortTimeIn"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchProfiles();
                $('#sortModal').modal('hide');
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortName"], input[name="sortTimeIn"]').prop('checked', false);
                fetchProfiles();
            });

            // Initial Fetch
            fetchProfiles();


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
                    }
                });
            });

            $(document).on('click', '.time-out-btn', function() {
                const attendanceId = $(this).data('attendance-id');
                const profileId = $(this).data('profile-id');
                const name = $(this).data('name');
                const rfid = $(this).data('rfid') || 'None';
                const img = $(this).data('img') || '/tapnlog/image/logo_and_icons/default_avatar.png';
                const date = $(this).data('date');
                const timeIn = $(this).data('time-in');

                // Current time for time-out
                const currentDate = new Date();
                timePassing = currentDate.toLocaleTimeString("en-CA", {
                    hour12: false
                });

                // FOR DISPLAY ONLY
                const formattedDate = new Intl.DateTimeFormat('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                }).format(currentDate);
                const formattedTimeOut = new Intl.DateTimeFormat('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                }).format(currentDate);

                const fetchedTime = timeIn;
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


                current = {
                    attendance_id: attendanceId,
                    profile_id: profileId,
                    rfid: rfid,
                    name: name,
                    method: 'MANUAL'
                };

                // Populate modal with the data
                $('#modal_1_profileImg').attr('src', img);
                $('#modal_1_rfid').val(rfid);
                $('#modal_1_name').val(name);
                $('#modal_1_date').val(formattedDate);
                $('#modal_1_timeIn').val(formattedTimeIn);
                $('#modal_1_timeOut').val(formattedTimeOut);

                // Show the modal
                $('#ProfileDetailsModal').modal('show');
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
                                    fetchProfiles();
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
                            },
                        });

                    }
                });
            });

            $(document).on('click', '.archive-btn', function() {
                const attendanceId = $(this).data('attendance-id');
                const profileId = $(this).data('profile-id');
                const name = $(this).data('name');
                const date = $(this).data('date');
                const timeIn = $(this).data('time-in');
                const timeOut = 'NOT COMPLETED';

                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to ARCHIVE the attendance of ${name}? This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send data to backend
                        $.ajax({
                            url: '../archive_attendance.php',
                            method: 'POST',
                            data: {
                                attendance_id: attendanceId,
                                profile_id: profileId,
                                name: name,
                                date: date,
                                time_in: timeIn,
                                time_out: timeOut,
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                    fetchProfiles(); // Refresh the profiles list
                                } else {
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                        timer: 1500,
                                        showConfirmButton: false,
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'An error occurred while archiving the record.',
                                    icon: 'error',
                                    timer: 1500,
                                    showConfirmButton: false,
                                });
                            },
                        });
                    }
                });
            });

        });
    </script>
</body>

</html>