<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Kapag hindi pa sila nakakalogin
if (!isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

// kapag hindi main admin, redirect sa landing page
if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged'])) {
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

    <title>Pending RFID Profiles | Main Admin</title>
    <style>
        /* Profile Image with 1:1 Ratio */
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

        /* FOR TABLE */
        table.table {
            min-width: 1000px;
        }

        table.table td {
            text-align: center;
            vertical-align: middle;
        }

        table.table th {
            text-align: center;
            vertical-align: middle;
        }

        .table-responsive {
            height: calc(100vh - 280px);
            overflow-y: auto;
            margin-bottom: 25px;
            background-color: white;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #217AEA;
            color: white;
            z-index: 1;
        }

        table.table tbody tr:hover {
            background-color: #DBE7FF;
        }

        .table-pre {
            white-space: pre;
        }

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

        body {
            background: url('/tapnlog/image/logo_and_icons/bsu-bg.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center px-2">

        <div class="container-fluid row col-sm-12 p-0">

            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-1 mt-5 p-0">
                <div class="container-fluid text-center p-0">
                    <h2 class="page-title text-center w-100">PENDING PROFILES</h2>
                    <div class="mb-3">
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name or ID">
                    </div>

                    <!-- Filter and Sort Buttons -->
                    <div class="w-100 d-flex justify-content-start p-0 mb-3">
                        <div class="col-md-6 col-12 m-0 p-0">
                            <div class="w-100 d-flex justify-content-start p-0 m-0">
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-primary btn-custom w-100" data-bs-toggle="modal" data-bs-target="#filterModal">FILTER</button>
                                </div>
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-secondary btn-custom w-100" data-bs-toggle="modal" data-bs-target="#sortModal">SORT</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>DATE</th>
                                    <th>NAME</th>
                                    <th>TYPE OF PROFILE</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="resultTableBody">
                                <!-- Results will be dynamically inserted here -->
                            </tbody>
                        </table>
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
                    <h5 class="modal-title" id="filterModalLabel"><strong>FILTER PROFILES</strong></h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="from_dateInput" class="form-label"><strong>FROM</strong></label>
                            <input type="date" id="from_dateInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="to_dateInput" class="form-label"><strong>TO</strong></label>
                            <input type="date" id="to_dateInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="profileType" class="form-label"><strong>PROFILE TYPE</strong></label>
                            <select id="profileType" class="form-select">
                                <option value="">ALL</option>
                                <option value="OJT">ON THE JOB TRAINEES</option>
                                <option value="CFW">CASH FOR WORK</option>
                                <option value="EMPLOYEE">EMPLOYEES</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <div class="w-100 mt-3">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="resetFilters" class="btn btn-danger btn-custom text-uppercase w-100">RESET</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button id="applyFilters" class="btn btn-primary btn-custom text-uppercase w-100">APPLY</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Sort Modal -->
    <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sortModalLabel"><strong>SORT PROFILES</strong></h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="form-label"><strong>SORT BY DATE</strong></label>
                        <div>
                            <input type="radio" name="sortDate" value="asc"> Ascending<br>
                            <input type="radio" name="sortDate" value="desc"> Descending
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label"><strong>SORT BY NAME</strong></label>
                        <div>
                            <input type="radio" name="sortName" value="asc"> A-Z<br>
                            <input type="radio" name="sortName" value="desc"> Z-A
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <div class="w-100 mt-3">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="resetSort" class="btn btn-danger btn-custom text-uppercase w-100">RESET</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button id="applySort" class="btn btn-primary btn-custom text-uppercase w-100">APPLY</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details Modal 1 -->
    <div class="modal fade" id="profileDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="profileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>PROFILE DETAILS</strong></h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Profile Image Column (1:1 Ratio) -->
                        <div class="col-lg-6 d-flex justify-content-center align-items-center">
                            <div class="profile-image-container">
                                <img id="modal_1_profileImg" src="/tapnlog/Image/LOGO_AND_ICONS/default_avatar.png" alt="Profile Picture" class="img-thumbnail" data-bs-toggle="modal" data-bs-target="#cropImageModal">
                            </div>
                        </div>

                        <!-- Details Column -->
                        <div class="col-lg-6">
                            <form id="profileForm">

                                <!-- hidden Id -->
                                <input type="hidden" id="modal_1_profileId">

                                <!-- Type of profile -->
                                <div class="mb-3">
                                    <label for="modal_1_profileType" class="form-label"><strong>TYPE OF PROFILE</strong></label>
                                    <select class="form-select" id="modal_1_profileType" name="type_of_profile" required disabled>
                                        <option value="OJT">ON THE JOB TRAINEE</option>
                                        <option value="CFW">CASH FOR WORK</option>
                                        <option value="EMPLOYEE">EMPLOYEES</option>
                                    </select>
                                </div>

                                <!-- First name -->
                                <div class="mb-3">
                                    <label for="modal_1_firstName" class="form-label"><strong>FIRST NAME</strong></label>
                                    <input type="text" class="form-control" id="modal_1_firstName" name="firstName" disabled>
                                    <div id="firstName-feedback" class="invalid-feedback"></div>
                                </div>

                                <!-- Last name -->
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label"><strong>LAST NAME</strong></label>
                                    <input type="text" class="form-control" id="modal_1_lastName" name="lastName" disabled>
                                    <div id="lastName-feedback" class="invalid-feedback"></div>
                                </div>

                                <!-- RFID number -->
                                <div class="mb-3">
                                    <label for="modal_1_rfid" class="form-label"><strong>RFID NUMBER</strong></label>
                                    <input type="text" class="form-control" id="modal_1_rfid" name="rfid">
                                    <div id="rfid-feedback" class="invalid-feedback"></div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="container d-flex justify-content-around align-items-center p-0">
                        <div class="row w-100 p-0">


                            <div id="discardBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-danger btn-custom w-100" id="discardBtn">DELETE</button>
                            </div>
                            <div id="editBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-primary btn-custom w-100" id="editBtn">EDIT</button>
                            </div>
                            <div id="approveBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-success btn-custom w-100" id="approveBtn">APPROVE</button>
                            </div>

                            <!-- For Edit Buttons -->
                            <div id="cancelEditBtn_cont" class="col-md-6 col-sm-12 p-1" style="display:none;">
                                <button type="button" class="btn btn-danger btn-custom w-100" id="cancelEditBtn">CANCEL</button>
                            </div>
                            <div id="saveEditBtn_cont" class="col-md-6 col-sm-12 p-1" style="display:none;">
                                <button type="button" class="btn btn-success btn-custom w-100" id="saveEditBtn">SAVE CHANGES</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Profile Modal -->
    <div class="modal fade" id="duplicateProfileModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="duplicateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateProfileModalLabel">SIMILAR PROFILES</h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                    <!-- RESULTS -->
                    <div class="row d-flex justify-content-center" id="same_result">


                    </div>
                </div>
                <div class="modal-footer">

                    <div class="row p-0 w-100">
                        <div id="discardSimilarBtn_cont" class="col-md-6 col-sm-12 p-1">
                            <button type="button" class="btn btn-danger btn-custom w-100" id="discardSimilarBtn">DELETE</button>
                        </div>
                        <div id="approveSimilarBtn_cont" class="col-md-6 col-sm-12 p-1">
                            <button type="button" class="btn btn-success btn-custom w-100" id="approveSimilarBtn">APPROVE ANYWAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            $('#modal_1_firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Add event listener for the last name input
            $('#modal_1_lastName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Get today's date in local time (correcting for time zone)
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(today.getDate()).padStart(2, '0'); // Ensures the day is two digits
            const todayFormatted = `${year}-${month}-${day}`; // Format as YYYY-MM-DD

            // Set initial value and max attribute for both inputs
            $('#from_dateInput, #to_dateInput').attr('max', todayFormatted);


            // VALIDATION OF DATE INPUTS
            function validateDateInput1() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                // Convert dates only if both fields have values
                if (fromDate && toDate) {
                    const fromDateValue = new Date(fromDate);
                    const toDateValue = new Date(toDate);
                    const todayDate = new Date(todayFormatted);

                    // Check if from exceeds today's date
                    if (fromDateValue > todayDate) {
                        $('#from_dateInput').val(todayFormatted);
                    }

                    // Check if fromDate is greater than
                    if (fromDateValue > toDateValue) {
                        $('#to_dateInput').val('');
                    }

                }
            }

            function validateDateInput2() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                // Convert dates only if both fields have values
                if (fromDate && toDate) {
                    const fromDateValue = new Date(fromDate);
                    const toDateValue = new Date(toDate);
                    const todayDate = new Date(todayFormatted);

                    // Check if toDate exceeds today's date
                    if (toDateValue > todayDate) {
                        $('#to_dateInput').val(todayFormatted);
                    }

                    // Check if fromDate is after toDate
                    if (fromDateValue > toDateValue) {
                        $('#from_dateInput').val('');
                    }

                }
            }


            $('#from_dateInput').on('input change', validateDateInput1);
            $('#to_dateInput').on('input change', validateDateInput2);


            // BACK BUTTON
            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            // RFID TEXT DELETE BEHAVIOR:
            $('#modal_1_rfid').on('keydown', function(e) {

                // Check if the key pressed is either Backspace (8) or Delete (46)
                if (e.keyCode === 8 || e.keyCode === 46) {
                    // Clear the input field
                    $(this).val('');
                }
            });


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
                        search: search
                    },
                    success: function(data) {
                        $('#resultTableBody').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching profiles:", error);
                    }
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
                    from_date: $('#from_dateInput').val(),
                    to_date: $('#to_dateInput').val(),
                    type_of_profile: $('#profileType').val()
                };
                fetchProfiles();
                $('#filterModal').modal('hide');
            });

            // Apply Sort
            $('#applySort').on('click', function() {
                sort = {
                    date: $('input[name="sortDate"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val()
                };
                fetchProfiles();
                $('#sortModal').modal('hide');
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                filters = {};
                $('#filterForm')[0].reset();
                fetchProfiles();
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};

                // Reset the sorting radio buttons to default
                $('input[name="sortDate"]').prop('checked', false);
                $('input[name="sortName"]').prop('checked', false);

                fetchProfiles();
            });

            // Initial Fetch
            fetchProfiles();

            // Check new records every 5 seconds
            setInterval(fetchProfiles, 5000);

            // PROFILE DETAILS MODAL 1

            let originalData = {}; // Object to store original modal values

            // View Details Function
            window.viewDetails = function(profileId) {
                $.ajax({
                    url: 'get_profile_details.php',
                    type: 'GET',
                    data: {
                        profile_id: profileId
                    },
                    success: function(response) {
                        const profile = JSON.parse(response);

                        // Store original values for reverting later
                        originalData = {
                            profileId: profile.profile_id,
                            firstName: profile.first_name,
                            lastName: profile.last_name,
                            profileType: profile.type_of_profile,
                            imgSrc: '/TAPNLOG/Image/Pending/' + profile.profile_img,
                        };

                        // Populate modal fields with fetched data
                        $('#modal_1_profileId').val(originalData.profileId);
                        $('#modal_1_firstName').val(originalData.firstName);
                        $('#modal_1_lastName').val(originalData.lastName);
                        $('#modal_1_profileType').val(originalData.profileType);
                        $('#modal_1_profileImg').attr('src', originalData.imgSrc);

                        // Remove the invalid first
                        $('#firstName-feedback').text('').removeClass('invalid-feedback');
                        $('#lastName-feedback').text('').removeClass('invalid-feedback');
                        $('#modal_1_rfid').text('').removeClass('invalid-feedback');

                        $('#modal_1_firstName').removeClass('is-invalid');
                        $('#modal_1_lastName').removeClass('is-invalid');
                        $('#modal_1_rfid').val('').removeClass('is-invalid');

                        // dine

                        // Hide edit buttons and show action buttons
                        $('#cancelEditBtn_cont, #saveEditBtn_cont').hide();
                        $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').show();

                        // Disable editing
                        $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', true);
                        $('#modal_1_rfid').prop('disabled', false);


                        // Open modal
                        $('#profileDetailsModal').modal('show');
                    },
                });
            };

            // Edit Button Click Handler
            $('#editBtn').click(function() {
                // Hide buttons
                $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').hide();
                $('#cancelEditBtn_cont, #saveEditBtn_cont').show();

                // Enable fields for editing
                $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', false);
                $('#modal_1_rfid').prop('disabled', true);
            });

            // Cancel Edit Button Click Handler
            $('#cancelEditBtn').click(function() {
                // Capture current values from modal
                const currentFirstName = $('#modal_1_firstName').val().trim();
                const currentLastName = $('#modal_1_lastName').val().trim();
                const currentProfileType = $('#modal_1_profileType').val();

                // Check if there are any changes
                const hasChanges =
                    currentFirstName !== originalData.firstName ||
                    currentLastName !== originalData.lastName ||
                    currentProfileType !== originalData.profileType;

                if (hasChanges) {
                    // Show confirmation dialog if there are changes
                    Swal.fire({
                        title: 'Unsaved Changes Detected',
                        text: 'You have unsaved changes. Are you sure you want to DISCARD the changes?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'YES',
                        cancelButtonText: 'NO',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                            cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                        },
                        buttonsStyling: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Revert fields to original values
                            $('#modal_1_firstName').val(originalData.firstName);
                            $('#modal_1_lastName').val(originalData.lastName);
                            $('#modal_1_profileType').val(originalData.profileType);

                            // Reset validation messages and classes
                            $('#firstName-feedback, #lastName-feedback').text('').removeClass('invalid-feedback');
                            $('#modal_1_firstName, #modal_1_lastName').removeClass('is-invalid');

                            // Reset image in case it was modified
                            $('#modal_1_profileImg').attr('src', originalData.imgSrc);

                            // Hide edit buttons and show action buttons
                            $('#cancelEditBtn_cont, #saveEditBtn_cont').hide();
                            $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').show();

                            // Disable fields
                            $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', true);
                            $('#modal_1_rfid').prop('disabled', false);
                        }
                    });
                } else {
                    // No changes, directly revert without confirmation
                    $('#modal_1_firstName').val(originalData.firstName);
                    $('#modal_1_lastName').val(originalData.lastName);
                    $('#modal_1_profileType').val(originalData.profileType);

                    // Reset validation messages and classes
                    $('#firstName-feedback, #lastName-feedback').text('').removeClass('invalid-feedback');
                    $('#modal_1_firstName, #modal_1_lastName').removeClass('is-invalid');

                    // Reset image
                    $('#modal_1_profileImg').attr('src', originalData.imgSrc);

                    // Hide edit buttons and show action buttons
                    $('#cancelEditBtn_cont, #saveEditBtn_cont').hide();
                    $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').show();

                    // Disable fields
                    $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', true);
                    $('#modal_1_rfid').prop('disabled', false);
                }
            });

            $('#saveEditBtn').click(function() {

                // Validate fields before submission
                validateFirstName();
                validateLastName();
                validateRFID();

                // If validation fails, display an alert
                if (checksaveEditBtn()) {

                    // Check for changes
                    const currentData = {
                        firstName: $('#modal_1_firstName').val().trim(),
                        lastName: $('#modal_1_lastName').val().trim(),
                        profileType: $('#modal_1_profileType').val(),
                        rfid: $('#modal_1_rfid').val().trim(),
                    };

                    const isChanged =
                        currentData.firstName !== originalData.firstName ||
                        currentData.lastName !== originalData.lastName ||
                        currentData.profileType !== originalData.profileType ||
                        currentData.rfid !== (originalData.rfid || '');

                    if (!isChanged) {
                        Swal.fire({
                            title: 'No Changes Detected',
                            text: 'You have not made any changes to the profile.',
                            icon: 'info',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to save the changes?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'YES',
                        cancelButtonText: 'NO',
                        reverseButtons: true,
                        customClass: {
                            confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                            cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                        },
                        buttonsStyling: false,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Gather the data for submission
                            const profileData = {
                                profile_id: $('#modal_1_profileId').val(),
                                first_name: $('#modal_1_firstName').val().trim(),
                                last_name: $('#modal_1_lastName').val().trim(),
                                type_of_profile: $('#modal_1_profileType').val()
                            };

                            // Send the AJAX request to update the profile
                            $.ajax({
                                url: 'save_profile_changes.php', // Endpoint for saving changes
                                type: 'POST',
                                data: profileData,
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        // Display success message
                                        Swal.fire({
                                            position: "top",
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success',
                                            timer: 3000,
                                            timerProgressBar: true,
                                            showConfirmButton: false
                                        });

                                        fetchProfiles();

                                        // Update the `originalData` object with the new values
                                        originalData.firstName = profileData.first_name;
                                        originalData.lastName = profileData.last_name;
                                        originalData.profileType = profileData.type_of_profile;

                                        // Update modal fields with the new values
                                        $('#modal_1_firstName').val(originalData.firstName);
                                        $('#modal_1_lastName').val(originalData.lastName);
                                        $('#modal_1_profileType').val(originalData.profileType);

                                        // Hide edit buttons and show action buttons
                                        $('#cancelEditBtn_cont, #saveEditBtn_cont').hide();
                                        $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').show();

                                        // Disable editing
                                        $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', true);
                                        $('#modal_1_rfid').prop('disabled', false);


                                    } else {
                                        // Display error message from server
                                        Swal.fire({
                                            position: "top",
                                            title: 'Error!',
                                            text: response.message,
                                            icon: 'error',
                                            timer: 3000,
                                            timerProgressBar: true,
                                            showConfirmButton: false
                                        });
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error details:', status, error); // Log the error for debugging
                                    Swal.fire({
                                        position: "top",
                                        title: 'Error!',
                                        text: 'An error occurred while saving changes. Please try again.',
                                        icon: 'error',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });
                                }
                            });
                        }
                    });

                }



            });

            // View details: discard button
            $('#discardBtn').on('click', function() {
                const profileId = $('#modal_1_profileId').val(); // Get profile ID from the hidden input in the modal

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to DELETE this profile? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                        cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'delete_profile.php', // URL to the PHP script
                            type: 'POST',
                            data: {
                                profile_id: profileId
                            },
                            success: function(response) {
                                const result = JSON.parse(response);

                                if (result.success) {
                                    // Show success message
                                    Swal.fire({
                                        position: "top",
                                        title: 'Success!',
                                        text: result.message,
                                        icon: 'success',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });

                                    $('#profileDetailsModal').modal('hide'); // Close the modal
                                    fetchProfiles(); // Refresh the table or data
                                } else {
                                    // Show error message
                                    Swal.fire({
                                        position: "top",
                                        title: 'Error!',
                                        text: result.message,
                                        icon: 'error',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    position: "top",
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the profile. Please try again.',
                                    icon: 'error',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                                console.error('Error details:', status, error); // Log details for debugging
                            }
                        });
                    }
                });
            });



            // FUNCTIONS FOR FEEDBACK MESSAGES OF MODAL 1

            // Add input event listeners
            $('#modal_1_firstName').on('input', validateFirstName);
            $('#modal_1_lastName').on('input', validateLastName);
            $('#modal_1_rfid').on('input keyup', validateRFID);

            // Validation for First Name
            function validateFirstName() {
                const firstName = $('#modal_1_firstName').val().trim();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;

                $('#firstName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_firstName').removeClass('is-invalid');

                if (firstName === '') {
                    $('#firstName-feedback').text('First name cannot be empty.').addClass('invalid-feedback');
                    $('#modal_1_firstName').addClass('is-invalid');
                } else if (!nameRegex.test(firstName)) {
                    $('#firstName-feedback').text('First name contains invalid characters.').addClass('invalid-feedback');
                    $('#modal_1_firstName').addClass('is-invalid');
                }
            }

            // Validation for Last Name
            function validateLastName() {
                const lastName = $('#modal_1_lastName').val().trim();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;

                $('#lastName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_lastName').removeClass('is-invalid');

                if (lastName === '') {
                    $('#lastName-feedback').text('Last name cannot be empty.').addClass('invalid-feedback');
                    $('#modal_1_lastName').addClass('is-invalid');
                } else if (!nameRegex.test(lastName)) {
                    $('#lastName-feedback').text('Last name contains invalid characters.').addClass('invalid-feedback');
                    $('#modal_1_lastName').addClass('is-invalid');
                }
            }

            function validateRFID() {
                const rfid = $('#modal_1_rfid').val().trim();
                const profileType = $('#modal_1_profileType').val();
                const rfidRegex = /^[A-Za-z0-9]*$/; // Alphanumeric, allows empty

                // Clear previous feedback
                $('#rfid-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_rfid').removeClass('is-invalid');

                // If RFID is not empty, validate it
                if (rfid && !rfidRegex.test(rfid)) {
                    $('#rfid-feedback').text('RFID must be alphanumeric.').addClass('invalid-feedback');
                    $('#modal_1_rfid').addClass('is-invalid');
                    return;
                }

                // If RFID is empty, no need for uniqueness check
                if (!rfid) {
                    return;
                }

                // AJAX call to check if the RFID exists
                $.ajax({
                    url: 'check_rfid.php', // Backend script for RFID validation
                    type: 'POST',
                    data: {
                        rfid: rfid,
                        type_of_profile: profileType
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.exists) {
                            $('#rfid-feedback').text('RFID already exists for this type of profile. Please use a different one.').addClass('invalid-feedback');
                            $('#modal_1_rfid').addClass('is-invalid');
                        } else {
                            $('#rfid-feedback').text(''); // Clear feedback
                            $('#modal_1_rfid').removeClass('is-invalid');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            position: "top",
                            title: 'Error!',
                            text: 'An error occurred while validating RFID.' || "An error occurred.",
                            icon: "error",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    }
                });
            }


            function checksaveEditBtn() {
                const isFirstNameValid = !$('#modal_1_firstName').hasClass('is-invalid') && $('#modal_1_firstName').val().trim() !== '';
                const isLastNameValid = !$('#modal_1_lastName').hasClass('is-invalid') && $('#modal_1_lastName').val().trim() !== '';

                if (!isFirstNameValid || !isLastNameValid) {
                    return false;
                } else {
                    return true;
                }

            }

            function checkapproveBtn() {
                const isFirstNameValid = !$('#modal_1_firstName').hasClass('is-invalid') && $('#modal_1_firstName').val().trim() !== '';
                const isLastNameValid = !$('#modal_1_lastName').hasClass('is-invalid') && $('#modal_1_lastName').val().trim() !== '';
                const isRFIDValid = !$('#modal_1_rfid').hasClass('is-invalid');

                if (!isFirstNameValid || !isLastNameValid || !isRFIDValid) {
                    return false;
                } else {
                    return true;
                }
            }


            // APPROVE FUNCTIONS

            $('#approveBtn').on('click', function() {

                if (!checkapproveBtn()) {
                    // do nothing
                    return;
                }


                const profileId = $('#modal_1_profileId').val();
                const firstName = $('#modal_1_firstName').val().trim();
                const lastName = $('#modal_1_lastName').val().trim();
                const profileType = $('#modal_1_profileType').val();
                const profileImg = $('#modal_1_profileImg').attr('src').split('/').pop();
                const rfid = $('#modal_1_rfid').val().trim();

                $.ajax({
                    url: 'check_duplicates.php', // Endpoint to check duplicates
                    type: 'POST',
                    data: {
                        first_name: firstName,
                        last_name: lastName,
                        type_of_profile: profileType,
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        if (response.duplicates.length > 0) {
                            // Populate the modal with duplicate profiles
                            const modalBody = $('#same_result');
                            modalBody.empty();

                            response.duplicates.forEach((profile) => {

                                // Use image_folder from the response for folder path
                                const folderName = profile.image_folder;

                                // Construct the image path or fallback to default avatar
                                const imgPath = profile.img && profile.img.trim() !== "" ?
                                    `/TAPNLOG/Image/${folderName}/${profile.img}` :
                                    "/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png";

                                // Handle null or undefined RFID
                                const rfidValue = profile.rfid ? profile.rfid : "NONE";

                                // Create a card for each duplicate profile
                                const card = `
                                    <div class="col-lg-6">
                                        <div class="card mb-3">
                                            <div class="profile-image-container">
                                                <img src="${imgPath}" class="card-img-top" alt="Profile Image">
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><strong>Date Approved:</strong> ${profile.formatted_date || "N/A"}</p>
                                                <p class="card-text"><strong>Name:</strong> ${profile.first_name} ${profile.last_name}</p>
                                                <p class="card-text"><strong>Profile Type:</strong> ${profile.type}</p>
                                                <p class="card-text"><strong>Status:</strong> ${profile.status}</p>
                                                <p class="card-text"><strong>RFID Number:</strong> ${rfidValue}</p>
                                            </div>
                                        </div>
                                    </div>`;
                                modalBody.append(card);
                            });

                            // Show the duplicate modal
                            $('#duplicateProfileModal').modal('show');
                        } else {

                            Swal.fire({
                                title: 'Are you sure?',
                                text: "Do you want to APPROVE this profile?",
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonText: 'YES',
                                cancelButtonText: 'NO',
                                reverseButtons: true,
                                customClass: {
                                    confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                                    cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                                },
                                buttonsStyling: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // No duplicates, approve the profile
                                    approveProfile(profileId, firstName, lastName, profileType, profileImg, rfid);
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            position: "top",
                            title: 'Error!',
                            text: 'Error occurred while checking duplicates.' || "An error occurred.",
                            icon: "error",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                        console.error(status, error);
                    },
                });


            });

            // Getting type of profile
            function getFullProfileType(shortType) {
                const types = {
                    'OJT': 'ON THE JOB TRAINEE',
                    'CFW': 'CASH FOR WORK',
                    'EMPLOYEE': 'EMPLOYEE'
                };
                return types[shortType] || shortType;
            }

            $('#approveSimilarBtn').on('click', function() {
                const profileId = $('#modal_1_profileId').val();
                const firstName = $('#modal_1_firstName').val().trim();
                const lastName = $('#modal_1_lastName').val().trim();
                const profileType = $('#modal_1_profileType').val();
                const profileImg = $('#modal_1_profileImg').attr('src').split('/').pop();

                const rfid = $('#modal_1_rfid').val().trim();

                // Prepare the RFID display value
                const rfidDisplay = rfid ? rfid : "NONE";
                const profileImgDisplay = `/tapnlog/Image/Pending/${profileImg}`;

                // SweetAlert2 confirmation
                Swal.fire({
                    title: 'Are you sure you want to APPROVE this profile?',
                    html: `
                            <div style="text-align: center;">
                                <!-- Responsive image container -->
                                <div style="width: 80%; max-width: 300px; aspect-ratio: 1; margin: 0 auto; border: 1px solid #ddd; border-radius: 50%; overflow: hidden; margin-bottom: 15px;">
                                    <img src="${profileImgDisplay}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                
                                <p><strong>Profile Type:</strong> ${getFullProfileType(profileType)}</p>
                                <p><strong>Name:</strong> ${firstName} ${lastName}</p>
                                <p><strong>RFID Number:</strong> ${rfidDisplay}</p>
                            </div>
                        `,
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                        cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Run the approveProfile function if confirmed
                        approveProfile(profileId, firstName, lastName, profileType, profileImg, rfid);
                    }
                });
            });


            $('#discardSimilarBtn').on('click', function() {
                const profileId = $('#modal_1_profileId').val(); // Get profile ID from the hidden input in the modal
                const firstName = $('#modal_1_firstName').val().trim();
                const lastName = $('#modal_1_lastName').val().trim();
                const profileType = $('#modal_1_profileType').val();
                const profileImg = $('#modal_1_profileImg').attr('src').split('/').pop();
                const rfid = $('#modal_1_rfid').val().trim();

                // Prepare the RFID display value
                const rfidDisplay = rfid ? rfid : "NONE";
                const profileImgDisplay = `/tapnlog/Image/Pending/${profileImg}`;

                // SweetAlert2 confirmation dialog with details
                Swal.fire({
                    title: 'Are you sure you want to DELETE this profile?',
                    html: `
                        <div style="text-align: center;">
                            <!-- Responsive image container -->
                            <div style="width: 80%; max-width: 300px; aspect-ratio: 1; margin: 0 auto; border: 1px solid #ddd; border-radius: 50%; overflow: hidden; margin-bottom: 15px;">
                                <img src="${profileImgDisplay}" alt="Profile Image" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            
                            <p><strong>Profile Type:</strong> ${getFullProfileType(profileType)}</p>
                            <p><strong>Name:</strong> ${firstName} ${lastName}</p>
                            <p><strong>RFID Number:</strong> ${rfidDisplay}</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                        cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                    },
                    buttonsStyling: false,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX request to delete the profile
                        $.ajax({
                            url: 'delete_profile.php', // URL to the PHP script
                            type: 'POST',
                            data: {
                                profile_id: profileId
                            },
                            success: function(response) {
                                const result = JSON.parse(response);

                                if (result.success) {
                                    // Show success message
                                    Swal.fire({
                                        position: "top",
                                        title: 'Success!',
                                        text: result.message,
                                        icon: 'success',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });

                                    // Close the modals and refresh the table or data
                                    $('#profileDetailsModal, #duplicateProfileModal').modal('hide');
                                    fetchProfiles();
                                } else {
                                    // Show error message
                                    Swal.fire({
                                        position: "top",
                                        title: 'Error!',
                                        text: result.message,
                                        icon: 'error',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle AJAX error
                                Swal.fire({
                                    position: "top",
                                    title: 'Error!',
                                    text: 'An error occurred while deleting the profile. Please try again.',
                                    icon: 'error',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                                console.error('Error details:', status, error); // Log details for debugging
                            }
                        });
                    }
                });
            });



            function approveProfile(profileId, firstName, lastName, profileType, profileImg, rfid) {
                $.ajax({
                    url: 'approve_profile.php', // Endpoint to approve profile
                    type: 'POST',
                    data: {
                        profile_id: profileId,
                        first_name: firstName,
                        last_name: lastName,
                        type_of_profile: profileType,
                        profile_img: profileImg,
                        rfid: rfid, // Include RFID in the request
                    },

                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.success) {

                            Swal.fire({
                                position: "top",
                                title: 'Success!',
                                text: result.message,
                                icon: "success",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });

                            fetchProfiles(); // Refresh the table
                            $('#profileDetailsModal, #duplicateProfileModal').modal('hide');
                        } else {
                            Swal.fire({
                                position: "top",
                                title: 'Error!',
                                text: result.message || "An error occurred.",
                                icon: "error",
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            position: "top",
                            title: 'Error!',
                            text: "Error occurred while approving the profile.",
                            icon: "error",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });

                        console.error("AJAX Error: ", error);
                        console.log("Raw Response: ", xhr.responseText);

                    },
                });
            }

        });
    </script>
</body>

</html>