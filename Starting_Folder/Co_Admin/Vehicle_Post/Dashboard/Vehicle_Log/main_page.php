<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// If already logged in, redirect to dashboard
if (!isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (isset($_SESSION['admin_logged']) || isset($_SESSION['record_guard_logged'])) {
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

    <!-- Real time session checker -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\status_script.php'; ?>

    <title>Vehicle logs | Co-Admin for Vehicle Post</title>
    <style>
        /* CARD CONTAINER FOR RESULT */
        #results-container {
            height: 400px;
            overflow-y: auto;

        }

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


        /* MODALS*/
        .modal-dialog {
            max-height: 90vh;
        }

        .modal-content {
            overflow-y: auto;
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

        /* ELEMENTS */
        textarea {
            resize: none;
        }
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
                    <h2 class="text-center w-100">VEHICLE LOGS</h2>
                    <div class="mb-3">
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name">
                    </div>

                    <!-- Sort Button -->
                    <div class="w-100 d-flex justify-content-start p-0 mb-3">
                        <div class="col-md-6 col-12 m-0 p-0">
                            <div class="w-100 d-flex justify-content-start p-0 m-0">
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#sortModal">SORT</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">

                        <!-- Result Section -->
                        <div id="results-container" class="row d-flex justify-content-center p-0">
                            <!-- Result Cards will be inserted here dynamically -->

                        </div>

                    </div>

                    <div class="row d-flex justify-content-start mt-2">
                        <div class="col-md-3 col-sm-4 col-12 mb-2">
                            <!-- Add Guard Button -->
                            <button type="button" id="addRecord" class="btn btn-primary w-100 h-100 p-2">ADD NEW VEHICLE</button>
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

    <!-- Add Vehicle Modal -->
    <div class="modal fade" id="addRecordModal" tabindex="-1" aria-labelledby="addRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRecordModalLabel">VEHICLE DETAILS</h5>
                    <button type="button" class="btn-close" id="modal_1_closeBtn"></button>
                </div>
                <div class="modal-body">
                    <form id="addRecordForm">
                        <div class="mb-3">
                            <label for="modal_1_firstName" class="form-label"><strong>FIRST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_1_firstName">
                            <div id="modal_1_firstName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_lastName" class="form-label"><strong>LAST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_1_lastName">
                            <div id="modal_1_lastName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_plateNumber" class="form-label"><strong>PLATE NUMBER</strong></label>
                            <input type="text" class="form-control" id="modal_1_plateNumber">
                            <div id="modal_1_plateNumber-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_vehiclePass" class="form-label"><strong>VEHICLE PASS (Optional)</strong></label>
                            <input type="text" class="form-control" id="modal_1_vehiclePass">
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_purpose" class="form-label"><strong>PURPOSE</strong></label>
                            <textarea class="form-control" id="modal_1_purpose" rows="3"></textarea>
                            <div id="modal_1_purpose-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveNewRecord">TIME IN</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Vehicle Modal -->
    <div class="modal fade" id="viewRecordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordLabel">VEHICLE DETAILS</h5>
                    <button type="button" class="btn-close" id="modal_2_closeBtn"></button>
                </div>
                <div class="modal-body">
                    <form id="addRecordForm">
                        <div class="mb-3">
                            <label for="modal_2_firstName" class="form-label"><strong>FIRST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_2_firstName" disabled>
                            <div id="modal_2_firstName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_2_lastName" class="form-label"><strong>LAST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_2_lastName" disabled>
                            <div id="modal_2_lastName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_2_plateNumber" class="form-label"><strong>PLATE NUMBER</strong></label>
                            <input type="text" class="form-control" id="modal_2_plateNumber" disabled>
                            <div id="modal_2_plateNumber-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_2_vehiclePass" class="form-label"><strong>VEHICLE PASS (Optional)</strong></label>
                            <input type="text" class="form-control" id="modal_2_vehiclePass" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modal_2_purpose" class="form-label"><strong>PURPOSE</strong></label>
                            <textarea class="form-control" id="modal_2_purpose" rows="3" disabled></textarea>
                            <div id="modal_2_purpose-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="editBtn">EDIT</button>
                    <button type="button" class="btn btn-danger" id="cancelBtn" style="display:none;">CANCEL</button>
                    <button type="button" class="btn btn-success" id="saveEditBtn" style="display:none;">SAVE CHANGES</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#modal_1_firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            $('#modal_1_lastName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            $('#modal_2_firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            $('#modal_2_lastName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });


            // BACK BUTTON
            $('#backbtn').on('click', function() {
                window.location.href = '../dashboard_home.php';
            });

            let datePassing = null;
            let timePassing = null;
            let formattedDate = null;
            let formattedTime = null;

            // START
            let sort = {};
            let search = '';

            function fetchResults() {
                $.ajax({
                    url: 'fetch_results.php',
                    method: 'POST',
                    data: {
                        sort: sort,
                        search: search,
                    },
                    success: function(data) {
                        $('#results-container').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching profiles:", error);
                    },
                });
            }

            // Live Search
            $('#searchTextbox').on('keyup', function() {
                search = $(this).val().trim();
                fetchResults();
            });

            // Apply Sort
            $('#applySort').on('click', function() {
                sort = {
                    time_in: $('input[name="sortTimeIn"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchResults();
                $('#sortModal').modal('hide');
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortName"], input[name="sortTimeIn"]').prop('checked', false);
                fetchResults();
            });

            // Initial Fetch
            fetchResults();


            // FEEDBACK MESSAGE FUNCTIONS FOR ADD VEHICLE MODAL

            $('#modal_1_firstName').on('input', validateFirstName1);
            $('#modal_1_lastName').on('input', validateLastName1);
            $('#modal_1_plateNumber').on('input', validatePlateNumber1);
            $('#modal_1_purpose').on('input', validatePurpose1);

            function validateFirstName1() {
                const firstName = $('#modal_1_firstName').val().trim();
                const firstNameRegex = /^[A-Za-z\s]+$/;

                $('#modal_1_firstName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (firstName === "") {
                    feedbackMessage = 'First name cannot be empty.';
                } else if (!firstNameRegex.test(firstName)) {
                    feedbackMessage = 'First name must only contain letters and spaces.';
                }

                if (feedbackMessage) {
                    $('#modal_1_firstName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_1_firstName').addClass('is-invalid');
                } else {
                    $('#modal_1_firstName').removeClass('is-invalid');
                }
            }

            function validateLastName1() {
                const lastName = $('#modal_1_lastName').val().trim();
                const lastNameRegex = /^[A-Za-z\s]+$/;

                $('#modal_1_lastName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (lastName === "") {
                    feedbackMessage = 'Last name cannot be empty.';
                } else if (!lastNameRegex.test(lastName)) {
                    feedbackMessage = 'Last name must only contain letters and spaces.';
                }

                if (feedbackMessage) {
                    $('#modal_1_lastName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_1_lastName').addClass('is-invalid');
                } else {
                    $('#modal_1_lastName').removeClass('is-invalid');
                }
            }

            function validatePlateNumber1() {
                const plateNumber = $('#modal_1_plateNumber').val().trim();
                const plateRegex = /^09\d{9}$/;

                $('#modal_1_plateNumber-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (plateNumber === "") {
                    feedbackMessage = 'Plate number cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#modal_1_plateNumber-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_1_plateNumber').addClass('is-invalid');
                } else {
                    $('#modal_1_plateNumber').removeClass('is-invalid');
                }
            }

            function validatePurpose1() {
                const purpose = $('#modal_1_purpose').val().trim();

                $('#modal_1_purpose-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (purpose === "") {
                    feedbackMessage = 'Purpose cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#modal_1_purpose-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_1_purpose').addClass('is-invalid');
                } else {
                    $('#modal_1_purpose').removeClass('is-invalid');
                }
            }

            function checksaveNewRecord() {
                const isFirstNameValid = !$('#modal_1_firstName').hasClass('is-invalid') && $('#modal_1_firstName').val().trim() !== "";
                const isLastNameValid = !$('#modal_1_lastName').hasClass('is-invalid') && $('#modal_1_lastName').val().trim() !== "";
                const isPlateNumberValid = !$('#modal_1_plateNumber').hasClass('is-invalid') && $('#modal_1_plateNumber').val().trim() !== "";
                const isPurposeValid = !$('#modal_1_purpose').hasClass('is-invalid') && $('#modal_1_purpose').val().trim() !== "";

                if (!isFirstNameValid || !isLastNameValid || !isPlateNumberValid || !isPurposeValid) {
                    return false;
                } else {
                    return true;
                }
            }


            // FEEDBACK MESSAGE FUNCTIONS FOR EDIT VEHICLE MODAL

            $('#modal_2_firstName').on('input', validateFirstName2);
            $('#modal_2_lastName').on('input', validateLastName2);
            $('#modal_2_plateNumber').on('input', validatePlateNumber2);
            $('#modal_2_purpose').on('input', validatePurpose2);

            function validateFirstName2() {
                const firstName = $('#modal_2_firstName').val().trim();
                const firstNameRegex = /^[A-Za-z\s]+$/;

                $('#modal_2_firstName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (firstName === "") {
                    feedbackMessage = 'First name cannot be empty.';
                } else if (!firstNameRegex.test(firstName)) {
                    feedbackMessage = 'First name must only contain letters and spaces.';
                }

                if (feedbackMessage) {
                    $('#modal_2_firstName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_2_firstName').addClass('is-invalid');
                } else {
                    $('#modal_2_firstName').removeClass('is-invalid');
                }
            }

            function validateLastName2() {
                const lastName = $('#modal_2_lastName').val().trim();
                const lastNameRegex = /^[A-Za-z\s]+$/;

                $('#modal_2_lastName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (lastName === "") {
                    feedbackMessage = 'Last name cannot be empty.';
                } else if (!lastNameRegex.test(lastName)) {
                    feedbackMessage = 'Last name must only contain letters and spaces.';
                }

                if (feedbackMessage) {
                    $('#modal_2_lastName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_2_lastName').addClass('is-invalid');
                } else {
                    $('#modal_2_lastName').removeClass('is-invalid');
                }
            }

            function validatePlateNumber2() {
                const plateNumber = $('#modal_2_plateNumber').val().trim();
                const plateRegex = /^09\d{9}$/;

                $('#modal_2_plateNumber-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (plateNumber === "") {
                    feedbackMessage = 'Plate number cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#modal_2_plateNumber-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_2_plateNumber').addClass('is-invalid');
                } else {
                    $('#modal_2_plateNumber').removeClass('is-invalid');
                }
            }

            function validatePurpose2() {
                const purpose = $('#modal_2_purpose').val().trim();

                $('#modal_2_purpose-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (purpose === "") {
                    feedbackMessage = 'Purpose cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#modal_2_purpose-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_2_purpose').addClass('is-invalid');
                } else {
                    $('#modal_2_purpose').removeClass('is-invalid');
                }
            }

            function checkbtn2() {
                const isFirstNameValid = !$('#modal_2_firstName').hasClass('is-invalid') && $('#modal_2_firstName').val().trim() !== "";
                const isLastNameValid = !$('#modal_2_lastName').hasClass('is-invalid') && $('#modal_2_lastName').val().trim() !== "";
                const isPlateNumberValid = !$('#modal_2_plateNumber').hasClass('is-invalid') && $('#modal_2_plateNumber').val().trim() !== "";
                const isPurposeValid = !$('#modal_2_purpose').hasClass('is-invalid') && $('#modal_2_purpose').val().trim() !== "";

                if (!isFirstNameValid || !isLastNameValid || !isPlateNumberValid || !isPurposeValid) {
                    return false;
                } else {
                    return true;
                }
            }






            // Time In Button Click
            $('#addRecord').on('click', function() {

                // Get current date and time from the laptop
                const currentDate = new Date();
                datePassing = currentDate.toLocaleDateString("en-CA");
                timePassing = currentDate.toLocaleTimeString("en-CA", {
                    hour12: false
                });

                // FOR ACTIVITY LOGS
                formattedDate = new Intl.DateTimeFormat('en-US', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                }).format(currentDate);

                // OPEN THE MODAL WITH EMPTY VALUES
                $('#modal_1_firstName').val('').removeClass('is-invalid');
                $('#modal_1_firstName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_lastName').val('').removeClass('is-invalid');
                $('#modal_1_lastName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_plateNumber').val('').removeClass('is-invalid');
                $('#modal_1_plateNumber-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_vehiclePass').val('');
                $('#modal_1_purpose').val('').removeClass('is-invalid');
                $('#modal_1_purpose-feedback').text('').removeClass('invalid-feedback');

                $('#addRecordModal').modal('show');

            });


            $('#saveNewRecord').on('click', function() {

                validateFirstName1();
                validateLastName1();
                validatePlateNumber1();
                validatePurpose1();

                if (!checksaveNewRecord()) {
                    return;
                }

                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to CONFIRM the TIME-IN?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        current = {
                            date: datePassing,
                            time_in: timePassing,
                            date_format: formattedDate,
                            first_name: $('#modal_1_firstName').val().trim(),
                            last_name: $('#modal_1_lastName').val().trim(),
                            plate_number: $('#modal_1_plateNumber').val().trim(),
                            vehicle_pass: $('#modal_1_vehiclePass').val().trim() ? $('#modal_1_vehiclePass').val().trim() : null,
                            purpose: $('#modal_1_purpose').val().trim(),
                        };

                        $.ajax({
                            type: "POST",
                            url: "time_in.php",
                            data: current,
                            dataType: "json",
                            success: function(response) {
                                console.log(response);
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

                                    fetchResults();
                                    $('#addRecordModal').modal('hide');

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
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: "An unknown error occurred, please try again",
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                                console.log(error);
                            }
                        });

                    }
                });
            });

            $('#modal_1_closeBtn').on('click', function() {
                const first_name = $('#modal_1_firstName').val().trim();
                const last_name = $('#modal_1_lastName').val().trim();
                const plate_number = $('#modal_1_plateNumber').val().trim();
                const pass = $('#modal_1_vehiclePass').val().trim();
                const purpose = $('#modal_1_purpose').val().trim();

                if (first_name !== '' || last_name !== '' || plate_number !== '' || pass !== '' || purpose !== '') {

                    Swal.fire({
                        title: `Unsaved Input Detected`,
                        text: `You have unsaved attendance log. Are you sure you want to DISCARD the input?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'YES, DISCARD',
                        cancelButtonText: 'NO, KEEP EDITING',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#addRecordModal').modal('hide');
                        }
                    });

                } else {
                    $('#addRecordModal').modal('hide');
                    return;
                }



            });


            // VIEW AND EDIT DETAILS
            $(document).on('click', '.view-details-btn', function() {
                const vehicleId = $(this).data('id'); // Get the vehicle ID from the button

                $.ajax({
                    url: 'get_record_details.php', // Backend to fetch vehicle details
                    method: 'POST',
                    data: {
                        id: vehicleId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {

                            // Store current values for checking of changes
                            current = {
                                record_id: response.data.vehicle_id,
                                first_name: response.data.first_name,
                                last_name: response.data.last_name,
                                plate_number: response.data.plate_num,
                                pass: response.data.vehicle_pass,
                                purpose: response.data.purpose
                            }

                            console.log(current);

                            // OPEN THE MODAL WITH POPULATED VALUES AND disabled inputs
                            $('#modal_2_firstName').val(current.first_name).removeClass('is-invalid');
                            $('#modal_2_firstName-feedback').text('').removeClass('invalid-feedback');
                            $('#modal_2_lastName').val(current.last_name).removeClass('is-invalid');
                            $('#modal_2_lastName-feedback').text('').removeClass('invalid-feedback');
                            $('#modal_2_plateNumber').val(current.plate_number).removeClass('is-invalid');
                            $('#modal_2_plateNumber-feedback').text('').removeClass('invalid-feedback');
                            $('#modal_2_vehiclePass').val(current.pass);
                            $('#modal_2_purpose').val(current.purpose).removeClass('is-invalid');
                            $('#modal_2_purpose-feedback').text('').removeClass('invalid-feedback');

                            $('#editBtn, #modal_2_closeBtn').show();
                            $('#cancelBtn, #saveEditBtn').hide();
                            $('#modal_2_firstName, #modal_2_lastName, #modal_2_plateNumber, #modal_2_vehiclePass, #modal_2_purpose').prop('disabled', true);

                            // Show the modal
                            $('#viewRecordModal').modal('show');
                        } else {
                            Swal.fire({
                                position: 'top',
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching vehicle details:", error);
                        Swal.fire({
                            position: 'top',
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to fetch vehicle details. Please try again.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }
                });
            });

            $('#modal_2_closeBtn').on('click', function() {
                $('#viewRecordModal').modal('hide');
            });

            $('#editBtn').on('click', function() {

                $('#editBtn, #modal_2_closeBtn').hide();
                $('#cancelBtn, #saveEditBtn').show();
                $('#modal_2_firstName, #modal_2_lastName, #modal_2_plateNumber, #modal_2_vehiclePass, #modal_2_purpose').prop('disabled', false);
            });

            $('#cancelBtn').on('click', function() {

                const first_name = $('#modal_2_firstName').val().trim();
                const last_name = $('#modal_2_lastName').val().trim();
                const plate_number = $('#modal_2_plateNumber').val().trim();
                const pass = $('#modal_2_vehiclePass').val().trim() === '' ? null : $('#modal_2_vehiclePass').val().trim();
                const purpose = $('#modal_2_purpose').val().trim();

                if (current.first_name === first_name && current.last_name === last_name &&
                    current.plate_number === plate_number && current.pass === pass && current.purpose === purpose
                ) {
                    $('#editBtn, #modal_2_closeBtn').show();
                    $('#cancelBtn, #saveEditBtn').hide();
                    $('#modal_2_firstName, #modal_2_lastName, #modal_2_plateNumber, #modal_2_vehiclePass, #modal_2_purpose').prop('disabled', true);
                    return;
                }

                // swal fire confirmation message ta's etong nasa baba
                Swal.fire({
                    title: 'Unsaved Changes Detected',
                    text: 'You have unsaved changes. Are you sure you want to DISCARD the changes?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YES, DISCARD CHANGES',
                    cancelButtonText: 'NO, KEEP EDITING',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        // REVERT VALUES AND DISABLE INPUTS
                        $('#modal_2_firstName').val(current.first_name).removeClass('is-invalid');
                        $('#modal_2_firstName-feedback').text('').removeClass('invalid-feedback');
                        $('#modal_2_lastName').val(current.last_name).removeClass('is-invalid');
                        $('#modal_2_lastName-feedback').text('').removeClass('invalid-feedback');
                        $('#modal_2_plateNumber').val(current.plate_number).removeClass('is-invalid');
                        $('#modal_2_plateNumber-feedback').text('').removeClass('invalid-feedback');
                        $('#modal_2_vehiclePass').val(current.pass);
                        $('#modal_2_purpose').val(current.purpose).removeClass('is-invalid');
                        $('#modal_2_purpose-feedback').text('').removeClass('invalid-feedback');

                        $('#editBtn, #modal_2_closeBtn').show();
                        $('#cancelBtn, #saveEditBtn').hide();
                        $('#modal_2_firstName, #modal_2_lastName, #modal_2_plateNumber, #modal_2_vehiclePass, #modal_2_purpose').prop('disabled', true);
                    }
                });
            });

            $('#saveEditBtn').on('click', function() {

                validateFirstName2();
                validateLastName2();
                validatePlateNumber2();
                validatePurpose2();

                if (!checkbtn2()) {
                    return;
                }

                data_update = {
                    record_id: current.record_id,
                    first_name: $('#modal_2_firstName').val().trim(),
                    last_name: $('#modal_2_lastName').val().trim(),
                    plate_number: $('#modal_2_plateNumber').val().trim(),
                    pass: $('#modal_2_vehiclePass').val().trim() === '' ? null : $('#modal_2_vehiclePass').val().trim(),
                    purpose: $('#modal_2_purpose').val().trim()
                }

                if (current.first_name === data_update.first_name && current.last_name === data_update.last_name &&
                    current.plate_number === data_update.plate_number && current.pass === data_update.pass && current.purpose === data_update.purpose
                ) {
                    Swal.fire({
                        title: 'No Changes Detected',
                        text: 'You have not made any changes to the details.',
                        icon: 'info',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                    return;
                }

                // Show a confirmation message before proceeding
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to SAVE the changes?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Show a loading alert while saving
                        Swal.fire({
                            title: 'Saving Changes...',
                            text: 'Please wait while we update the profile.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        // Send the data using AJAX
                        $.ajax({
                            url: 'save_changes.php',
                            type: 'POST',
                            data: data_update,
                            dataType: 'json',
                            success: function(response) {
                                Swal.close(); // Close the loading alert

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

                                    // Refresh the profile list or update the UI
                                    fetchResults();

                                    current.first_name = data_update.first_name;
                                    current.last_name = data_update.last_name;
                                    current.plate_number = data_update.plate_number;
                                    current.pass = data_update.pass;
                                    current.purpose = data_update.purpose;

                                    // UPDATE VALUES AND DISABLE INPUTS
                                    $('#modal_2_firstName').val(data_update.first_name);
                                    $('#modal_2_lastName').val(data_update.last_name);
                                    $('#modal_2_plateNumber').val(data_update.plate_number);
                                    $('#modal_2_vehiclePass').val(data_update.pass);
                                    $('#modal_2_purpose').val(data_update.purpose);

                                    $('#editBtn, #modal_2_closeBtn').show();
                                    $('#cancelBtn, #saveEditBtn').hide();
                                    $('#modal_2_firstName, #modal_2_lastName, #modal_2_plateNumber, #modal_2_vehiclePass, #modal_2_purpose').prop('disabled', true);

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
                                Swal.close(); // Close the loading alert
                                console.error("Error on time out:", error);
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'An unexpected error occurred. Please try again later.',
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


            // TIME OUT
            $(document).on('click', '.time-out-btn', function() {
                const vehicleId = $(this).data('id'); // Get the vehicle ID from the button
                const full_name = $(this).data('name');

                // Get the current time
                const currentDate = new Date();
                datePassing = currentDate.toLocaleDateString("en-CA");
                timePassing = currentDate.toLocaleTimeString("en-CA", {
                    hour12: false
                });

                // Confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to CONFIRM the TIME-OUT of ${full_name}? This action cannot be undone.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show a loading alert while processing
                        Swal.fire({
                            title: 'Processing Time-Out...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        // Send the AJAX request
                        $.ajax({
                            url: 'time_out.php', // Backend script for processing time out
                            type: 'POST',
                            data: {
                                record_id: vehicleId,
                                date: datePassing,
                                time_out: timePassing
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.close(); // Close the loading alert

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

                                    // Refresh the vehicle list or remove the card from UI
                                    fetchResults();
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
                            error: function() {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'Failed to process Time-Out. Please try again.',
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            },
                        });
                    }
                });
            });



            // ARCHIVE RECORD
            $(document).on('click', '.archive-btn', function() {
                const vehicleId = $(this).data('id'); // Get the vehicle ID from the button
                const full_name = $(this).data('name');
                
                // Confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to ARCHIVE the LOG of ${full_name}? This action cannot be undone.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show a loading alert while processing
                        Swal.fire({
                            title: 'Archiving Record...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                        });

                        // Send the AJAX request to archive the record
                        $.ajax({
                            url: 'archive_record.php', // The backend script to process the archive
                            type: 'POST',
                            data: {
                                record_id: vehicleId
                            },
                            dataType: 'json',
                            success: function(response) {
                                Swal.close(); // Close the loading alert

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

                                    // Refresh the vehicle list or update the UI
                                    fetchResults();
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
                            error: function() {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'Failed to process archive. Please try again.',
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
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