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

    <title>Attendance Log - Cash for work | Co-Admin for Record Post</title>
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
                    <h2 class="text-center w-100">CASH FOR WORK ATTENDANCE LOG</h2>
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
                        <label class="form-label">Sort by Name:</label>
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
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchProfiles();
                $('#sortModal').modal('hide');
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortName"]').prop('checked', false);
                fetchProfiles();
            });

            // Initial Fetch
            fetchProfiles();

            // Time In Button Click
            $(document).on('click', '.time-in-btn', function() {

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



                const profileId = $(this).data('profile-id');
                const name = $(this).data('name');
                const rfid = $(this).data('rfid') || 'None';
                const img = $(this).data('img');

                current = {
                    profile_id: profileId,
                    rfid: rfid,
                    name: name,
                    method: "MANUAL",
                    date: datePassing,
                    time: timePassing
                };

                // Populate modal with profile data
                $('#modal_1_profileImg').attr('src', img);
                $('#modal_1_rfid').val(rfid);
                $('#modal_1_name').val(name);

                $('#modal_1_date').val(formattedDate);
                $('#modal_1_time').val(formattedTime);

                $('#ProfileDetailsModal').modal('show');
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
                                    fetchProfiles();
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