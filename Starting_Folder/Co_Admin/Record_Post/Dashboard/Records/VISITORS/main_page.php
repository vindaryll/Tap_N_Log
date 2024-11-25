<?php
session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// Kapag hindi pa sila nakakalogin, dederetso sa login page
if (!isset($_SESSION['record_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

// kapag hindi belong sa Record Post, redirect sa landing page
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

    <title>Records - Visitors | Record Post</title>

    <style>
        .input-group {
            position: relative;
        }

        .toggle-password {
            cursor: pointer;
        }

        /* FOR TABLE */
        table.table {
            min-width: 1000px;
        }


        .table-pre {
            white-space: pre;
        }

        .table-responsive {
            height: 400px;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
            z-index: 1;
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
    </style>

</head>

<body>
    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\Record_Post\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container-fluid row col-sm-12 p-0">

            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-0 mt-4 px-2">

                <div class="container-fluid text-center">
                    <h2 class="text-center w-100">VISITORS RECORD</h2>

                    <!-- Textbox for search -->
                    <input type="text" id="searchTextbox" class="form-control mb-3" placeholder="Search by name or Logbook ID">

                    <!-- Filter and Sort Buttons -->
                    <div class="row d-flex justify-content-start p-0 ms-2 mb-3">
                        <div class="col-md-6 m-0 p-0">
                            <div class="row d-flex justify-content-start">
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#filterModal">FILTER</button>
                                </div>
                                <div class="col-3 m-1 p-0">
                                    <button class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#sortModal">SORT</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>LOGBOOK ID</th>
                                    <th>DATE</th>
                                    <th>NAME</th>
                                    <th>TIME-IN</th>
                                    <th>TIME-OUT</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <!-- Results will be inserted here -->

                            </tbody>
                        </table>
                    </div>

                    <div class="row d-flex justify-content-start mt-2">
                        <div class="col-md-3 col-sm-4 col-12 mb-2">
                            <!-- Add Guard Button -->
                            <button type="button" id="goToArchive" class="btn btn-primary w-100 h-100 p-2">ARCHIVED RECORDS</button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- END OF CONTAINER -->

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">FILTER RECORDS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="from_dateInput" class="form-label">FROM</label>
                            <input type="date" id="from_dateInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="to_dateInput" class="form-label">TO</label>
                            <input type="date" id="to_dateInput" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="resetFilters" class="btn btn-danger">RESET</button>
                    <button type="button" id="applyFilters" class="btn btn-primary">APPLY</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sort Modal -->
    <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sortModalLabel">SORT RECORDS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">SORT BY DATE</label>
                        <div>
                            <input type="radio" name="sortDate" value="asc"> Ascending<br>
                            <input type="radio" name="sortDate" value="desc"> Descending
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">SORT BY TIME-IN</label>
                        <div>
                            <input type="radio" name="sortTimeIn" value="asc"> Earliest<br>
                            <input type="radio" name="sortTimeIn" value="desc"> Latest
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">SORT BY TIME-OUT</label>
                        <div>
                            <input type="radio" name="sortTimeOut" value="asc"> Earliest<br>
                            <input type="radio" name="sortTimeOut" value="desc"> Latest
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">SORT BY name</label>
                        <div>
                            <input type="radio" name="sortName" value="asc"> A-Z<br>
                            <input type="radio" name="sortName" value="desc"> Z-A
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="resetSort" class="btn btn-danger">RESET</button>
                    <button type="button" id="applySort" class="btn btn-primary">APPLY</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Visitor Modal -->
    <div class="modal fade" id="viewRecordModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRecordLabel">VISITORS DETAILS</h5>
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
                            <label for="modal_2_phoneNumber" class="form-label"><strong>PHONE NUMBER</strong></label>
                            <input type="text" class="form-control" id="modal_2_phoneNumber" disabled>
                            <div id="modal_2_phoneNumber-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>
                        <div class="mb-3">
                            <label for="modal_2_visitorPass" class="form-label"><strong>VISITOR PASS (Optional)</strong></label>
                            <input type="text" class="form-control" id="modal_2_visitorPass" disabled>
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




    <script>
        $(document).ready(function() {

            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            $('#modal_2_firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Add event listener for the last name input
            $('#modal_2_lastName').on('input', function() {
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

            let filters = {};
            let sort = {};
            let search = '';

            function fetchRecords() {
                $.ajax({
                    url: 'fetch_records.php',
                    type: 'POST',
                    data: {
                        search: search,
                        filters: filters,
                        sort: sort,
                    },
                    success: function(data) {
                        $('#tableBody').html(data);
                    },
                });
            }

            // Live Search
            $('#searchTextbox').on('keyup', function() {
                search = $(this).val().trim();
                fetchRecords();
            });

            // Apply Filters
            $('#applyFilters').on('click', function() {
                filters = {
                    from_date: $('#from_dateInput').val(),
                    to_date: $('#to_dateInput').val()
                };
                fetchRecords();
                $('#filterModal').modal('hide');
            });

            // Apply Sort
            $('#applySort').on('click', function() {
                sort = {
                    date: $('input[name="sortDate"]:checked').val(),
                    time_in: $('input[name="sortTimeIn"]:checked').val(),
                    time_out: $('input[name="sortTimeOut"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val()
                };
                fetchRecords();
                $('#sortModal').modal('hide');
            });

            // Reset Filters
            $('#resetFilters').on('click', function() {
                filters = {};
                $('#filterForm')[0].reset();
                fetchRecords();
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};

                // Reset the sorting radio buttons to default
                $('input[name="sortDate"]').prop('checked', false);
                $('input[name="sortName"]').prop('checked', false);
                $('input[name="sortTimeIn"]').prop('checked', false);
                $('input[name="sortTimeOut"]').prop('checked', false);
                fetchRecords();
            });

            // Initial Fetch
            fetchRecords();





            // FEEDBACK MESSAGE FUNCTIONS FOR EDIT VISITOR MODAL

            $('#modal_2_firstName').on('input', validateFirstName2);
            $('#modal_2_lastName').on('input', validateLastName2);
            $('#modal_2_phoneNumber').on('input', validatePhoneNumber2);
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

            function validatePhoneNumber2() {
                const phoneNumber = $('#modal_2_phoneNumber').val().trim();
                const phoneRegex = /^09\d{9}$/;

                $('#modal_2_phoneNumber-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (phoneNumber === "") {
                    feedbackMessage = 'Phone number cannot be empty.';
                } else if (!phoneNumber.match(/^\d+$/)) {
                    feedbackMessage = 'Phone number must contain numbers only.';
                } else if (!phoneRegex.test(phoneNumber)) {
                    feedbackMessage = 'Phone number must start with "09" and be exactly 11 digits long.';
                }

                if (feedbackMessage) {
                    $('#modal_2_phoneNumber-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modal_2_phoneNumber').addClass('is-invalid');
                } else {
                    $('#modal_2_phoneNumber').removeClass('is-invalid');
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
                const isPhoneNumberValid = !$('#modal_2_phoneNumber').hasClass('is-invalid') && $('#modal_2_phoneNumber').val().trim() !== "";
                const isPurposeValid = !$('#modal_2_purpose').hasClass('is-invalid') && $('#modal_2_purpose').val().trim() !== "";

                if (!isFirstNameValid || !isLastNameValid || !isPhoneNumberValid || !isPurposeValid) {
                    return false;
                } else {
                    return true;
                }
            }



            // VIEW AND EDIT DETAILS
            $(document).on('click', '.view-details-btn', function() {
                const record_id = $(this).data('id');
                const first_name = $(this).data('first-name');
                const last_name = $(this).data('last-name');
                const phone_number = $(this).data('phone-num');
                const purpose = $(this).data('purpose');
                const pass = $(this).data('visitor-pass');

                // Store current values for checking of changes
                current = {
                    record_id: record_id,
                    first_name: first_name,
                    last_name: last_name,
                    phone_number: phone_number,
                    purpose: purpose,
                    pass: pass
                }


                // OPEN THE MODAL WITH POPULATED VALUES AND disabled inputs
                $('#modal_2_firstName').val(current.first_name).removeClass('is-invalid');
                $('#modal_2_firstName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_2_lastName').val(current.last_name).removeClass('is-invalid');
                $('#modal_2_lastName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_2_phoneNumber').val(current.phone_number).removeClass('is-invalid');
                $('#modal_2_phoneNumber-feedback').text('').removeClass('invalid-feedback');
                $('#modal_2_visitorPass').val(current.pass);
                $('#modal_2_purpose').val(current.purpose).removeClass('is-invalid');
                $('#modal_2_purpose-feedback').text('').removeClass('invalid-feedback');

                $('#editBtn, #modal_2_closeBtn').show();
                $('#cancelBtn, #saveEditBtn').hide();
                $('#modal_2_firstName, #modal_2_lastName, #modal_2_phoneNumber, #modal_2_visitorPass, #modal_2_purpose').prop('disabled', true);

                // Show the modal
                $('#viewRecordModal').modal('show');
            });

            $('#editBtn').on('click', function() {

                $('#editBtn, #modal_2_closeBtn').hide();
                $('#cancelBtn, #saveEditBtn').show();
                $('#modal_2_firstName, #modal_2_lastName, #modal_2_phoneNumber, #modal_2_visitorPass, #modal_2_purpose').prop('disabled', false);
            });

            $('#cancelBtn').on('click', function() {

                const first_name = $('#modal_2_firstName').val().trim();
                const last_name = $('#modal_2_lastName').val().trim();
                const phone_number = $('#modal_2_phoneNumber').val().trim();
                const pass = $('#modal_2_visitorPass').val().trim();
                const purpose = $('#modal_2_purpose').val().trim();


                if (current.first_name === first_name && current.last_name === last_name &&
                    current.phone_number === phone_number && current.pass === pass && current.purpose === purpose
                ) {
                    $('#editBtn, #modal_2_closeBtn').show();
                    $('#cancelBtn, #saveEditBtn').hide();
                    $('#modal_2_firstName, #modal_2_lastName, #modal_2_phoneNumber, #modal_2_visitorPass, #modal_2_purpose').prop('disabled', true);
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
                        $('#modal_2_phoneNumber').val(current.phone_number).removeClass('is-invalid');
                        $('#modal_2_phoneNumber-feedback').text('').removeClass('invalid-feedback');
                        $('#modal_2_visitorPass').val(current.pass);
                        $('#modal_2_purpose').val(current.purpose).removeClass('is-invalid');
                        $('#modal_2_purpose-feedback').text('').removeClass('invalid-feedback');

                        $('#editBtn, #modal_2_closeBtn').show();
                        $('#cancelBtn, #saveEditBtn').hide();
                        $('#modal_2_firstName, #modal_2_lastName, #modal_2_phoneNumber, #modal_2_visitorPass, #modal_2_purpose').prop('disabled', true);
                    }
                });
            });

            $('#modal_2_closeBtn').on('click', function() {

                $('#viewRecordModal').modal('hide');
            });

            $('#saveEditBtn').on('click', function() {

                validateFirstName2();
                validateLastName2();
                validatePhoneNumber2();
                validatePurpose2();

                if (!checkbtn2()) {
                    return;
                }

                data_update = {
                    record_id: current.record_id,
                    first_name: $('#modal_2_firstName').val().trim(),
                    last_name: $('#modal_2_lastName').val().trim(),
                    phone_number: $('#modal_2_phoneNumber').val().trim(),
                    pass: $('#modal_2_visitorPass').val().trim(),
                    purpose: $('#modal_2_purpose').val().trim()
                }



                if (current.first_name === data_update.first_name && current.last_name === data_update.last_name &&
                    current.phone_number === data_update.phone_number && current.pass === data_update.pass && current.purpose === data_update.purpose
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
                            text: 'Please wait while we update the details.',
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

                                    current.first_name = data_update.first_name;
                                    current.last_name = data_update.last_name;
                                    current.phone_number = data_update.phone_number;
                                    current.pass = data_update.pass;
                                    current.purpose = data_update.purpose;

                                    // UPDATE VALUES AND DISABLE INPUTS
                                    $('#modal_2_firstName').val(data_update.first_name);
                                    $('#modal_2_lastName').val(data_update.last_name);
                                    $('#modal_2_phoneNumber').val(data_update.phone_number);
                                    $('#modal_2_visitorPass').val(data_update.pass);
                                    $('#modal_2_purpose').val(data_update.purpose);

                                    $('#editBtn, #modal_2_closeBtn').show();
                                    $('#cancelBtn, #saveEditBtn').hide();
                                    $('#modal_2_firstName, #modal_2_lastName, #modal_2_phoneNumber, #modal_2_visitorPass, #modal_2_purpose').prop('disabled', true);

                                    // Refresh the profile list or update the UI
                                    fetchRecords();
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


            // ARCHIVE RECORD
            $(document).on('click', '.archive-btn', function() {
                const visitorId = $(this).data('id'); // Get the visitor ID from the button
                const full_name = $(this).data('name');

                // Confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to ARCHIVE the LOG of ${full_name}? This action cannot be undone.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'YES, ARCHIVE',
                    cancelButtonText: 'NO, CANCEL',
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
                                record_id: visitorId
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

                                    // Refresh the visitor list or update the UI
                                    fetchRecords();
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>