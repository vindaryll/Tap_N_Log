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

// Pending pa

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

    <title>Co-Admin Account | Main Admin</title>
    <style>
        #employee-container {
            height: 450px;
            /* Adjust the height as needed */
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
    </style>

    <style>
        /* Fixed header and scrollable body styling */
        .table-responsive {
            height: 400px;
            /* Adjust the height as needed */
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            /* Background color for header */
            color: white;
            z-index: 1;
        }

        /* Pre-style to maintain line breaks without wrapping text */
        .table-pre {
            white-space: pre;
            /* Maintains line breaks without word wrapping */
        }
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container-fluid row col-sm-12 p-0">

            <div class="container col-sm-12">
                <button type="button" class="btn btn-primary" id="backbtn">Back</button>
            </div>

            <div class="container-fluid col-sm-12">
                <div class="container mt-3 p-0">

                    <div class="mb-3">
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name or ID">
                    </div>

                    <div class="row">
                        <div class="container col-lg-6">
                            <div class="row">

                                <div class="container col-md-4">
                                    <div class="mb-3 text-start">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                Status
                                            </span>
                                            <select id="status-select" class="form-select">
                                                <option value="">Active or Inactive</option>
                                                <option value="ACTIVE">Active</option>
                                                <option value="INACTIVE">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="container col-md-4">
                                    <div class="mb-3 text-start">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                From
                                            </span>
                                            <input type="date" class="form-control" id="from_dateInput">
                                        </div>
                                    </div>
                                </div>

                                <div class="container col-md-4 text-start">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                To
                                            </span>
                                            <input type="date" class="form-control" id="to_dateInput">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>

                    <div class="row d-flex justify-content-center">

                        <!-- Employee Profiles Section -->
                        <div id="employee-container" class="row d-flex justify-content-center">
                            <!-- Employee Profile Cards will be inserted here dynamically -->
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Edit Profile Details Modal-->
    <div class="modal fade" id="EditProfileDetailsModal" tabindex="-1" aria-labelledby="EditProfileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

                                <!-- hidden Id -->
                                <input type="hidden" id="modal_1_profileId">

                                <!-- First name -->
                                <div class="mb-3">
                                    <label for="modal_1_firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modal_1_firstName" name="firstName">
                                    <div id="firstName-feedback" class="invalid-feedback"></div>
                                </div>

                                <!-- Last name -->
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modal_1_lastName" name="lastName">
                                    <div id="lastName-feedback" class="invalid-feedback"></div>
                                </div>

                                <!-- RFID number -->
                                <div class="mb-3">
                                    <label for="modal_1_rfid" class="form-label">RFID Number</label>
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

                            <!-- For Revert Button -->
                            <div id="revertOriginalBtn_cont" class="col-md-4 col-sm-12 p-1" style="display: none;">
                                <button type="button" class="btn btn-warning w-100" id="revertOriginalBtn">REVERT ORIGINAL PICTURE</button>
                            </div>

                            <!-- For Edit Buttons -->
                            <div id="cancelEditBtn_cont" class="col-md-6 col-sm-12 p-1">
                                <button type="button" class="btn btn-danger w-100" id="cancelEditBtn">CANCEL</button>
                            </div>
                            <div id="saveEditBtn_cont" class="col-md-6 col-sm-12 p-1">
                                <button type="button" class="btn btn-success w-100" id="saveEditBtn">SAVE CHANGES</button>
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

            // VALIDATE DATE INPUTS

            // Get today's date in local time (correcting for time zone)
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(today.getDate()).padStart(2, '0'); // Ensures the day is two digits
            const todayFormatted = `${year}-${month}-${day}`; // Format as YYYY-MM-DD

            // Set initial value and max attribute for both inputs
            $('#from_dateInput, #to_dateInput').attr('max', todayFormatted);
            $('#from_dateInput, #to_dateInput').on('input change', validateDateInputs);

            function validateDateInputs() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                if (toDate) {
                    // If to_date exceeds today's date, set it to today's date
                    if (new Date(toDate) > new Date(todayFormatted)) {
                        $('#to_dateInput').val(todayFormatted);
                    }
                }

                if (fromDate && toDate) {
                    const fromDateValue = new Date(fromDate);
                    const toDateValue = new Date(toDate);

                    // If from_date is greater than to_date, set from_date to to_date
                    if (fromDateValue > toDateValue) {
                        $('#from_dateInput').val(toDate);
                    }

                    // If to_date is less than from_date, set to_date to from_date
                    if (toDateValue < fromDateValue) {
                        $('#to_dateInput').val(fromDate);
                    }
                }

                fetchProfiles();
            }

            // VALIDATE EDIT MODAL INPUTS

            // Attach input event listeners to fields
            $('#modal_1_firstName').on('input', validateFirstName);
            $('#modal_1_lastName').on('input', validateLastName);
            $('#modal_1_rfid').on('input keyup', validateRFID);

            // Validation for First Name
            function validateFirstName() {
                const firstName = $('#modal_1_firstName').val().trim();
                const nameRegex = /^[A-Za-z.\-'\s]+$/; // Letters, dots, hyphens, apostrophes, and spaces

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
                const nameRegex = /^[A-Za-z.\-'\s]+$/; // Letters, dots, hyphens, apostrophes, and spaces

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
                const employeeId = $('#modal_1_profileId').val(); // Get the current employee ID
                const rfidRegex = /^[A-Za-z0-9]*$/; // Alphanumeric, allows empty

                // Clear previous feedback
                $('#rfid-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_rfid').removeClass('is-invalid');

                // If RFID is not empty, validate it before proceeding
                if (rfid && !rfidRegex.test(rfid)) {
                    $('#rfid-feedback').text('RFID must be alphanumeric.').addClass('invalid-feedback');
                    $('#modal_1_rfid').addClass('is-invalid');

                    const currentRFID = $('#modal_1_rfid').attr('data-current') || null;

                    $('#modal_1_rfid').val(currentRFID);

                    return; // Stop further validation
                }

                // If RFID is empty, no further validation is needed
                if (!rfid) {
                    return;
                }

                // AJAX call to check if the RFID exists
                $.ajax({
                    url: 'check_rfid.php', // Backend script for RFID validation
                    type: 'POST',
                    data: {
                        rfid: rfid,
                        employee_id: employeeId // Include the current employee ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.exists) {
                            $('#rfid-feedback')
                                .text('RFID already exists. Please use a different one.')
                                .addClass('invalid-feedback');
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
                            text: 'An error occurred while validating RFID.',
                            icon: "error",
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
                });
            }


            function checksaveEditBtn() {
                const isFirstNameValid = !$('#modal_1_firstName').hasClass('is-invalid') && $('#modal_1_firstName').val().trim() !== '';
                const isLastNameValid = !$('#modal_1_lastName').hasClass('is-invalid') && $('#modal_1_lastName').val().trim() !== '';
                const isRFIDValid = !$('#modal_1_rfid').hasClass('is-invalid');

                // If any of the fields are invalid, return false
                if (!isFirstNameValid || !isLastNameValid || !isRFIDValid) {
                    return false;
                } else {
                    return true; // All fields are valid
                }
            }


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

            // EDIT PICTURE
            let cropper;
            let originalImage = null;
            let croppedImage = null;

            $('#modal_1_profileImg').on('click', function() {

                // Open SweetAlert2 with Cropper
                Swal.fire({
                    title: 'Edit Profile Picture',
                    html: `
                        <input type="file" id="swalFileInput" accept="image/*" class="form-control mb-3">
                        <div style="max-width: 100%; overflow: hidden;">
                            <img id="swalImageToCrop" style="display: none; width: 100%; max-height: 70vh;" />
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Save Crop',
                    reverseButtons: true,
                    didOpen: () => {
                        // Initialize file input behavior
                        $('#swalFileInput').change(function() {
                            const file = this.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    $('#swalImageToCrop').attr('src', e.target.result).show();
                                    if (cropper) cropper.destroy();
                                    cropper = new Cropper(document.getElementById('swalImageToCrop'), {
                                        aspectRatio: 1, // 1:1 aspect ratio
                                        viewMode: 1,
                                        movable: true,
                                        zoomable: true,
                                        scalable: true,
                                        rotatable: true,
                                        cropBoxMovable: true,
                                        cropBoxResizable: true,
                                    });
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    },
                    preConfirm: () => {
                        // Save the cropped image
                        if (cropper) {
                            const canvas = cropper.getCroppedCanvas({
                                width: 600,
                                height: 600,
                            });
                            croppedImage = canvas.toDataURL('image/png');
                            return croppedImage;
                        }
                    },
                    willClose: () => {
                        if (cropper) cropper.destroy();
                    },
                }).then((result) => {
                    if (result.isConfirmed && croppedImage) {
                        // Update the profile picture with the cropped image
                        $('#revertOriginalBtn_cont').show(); // Show the revert button
                        $('#modal_1_profileImg').attr('src', croppedImage);
                        adjustButtonColumns();
                    }
                });

            });

            function adjustButtonColumns() {
                if ($('#revertOriginalBtn_cont').is(':visible')) {
                    // Revert button is visible
                    $('#cancelEditBtn_cont').removeClass('col-md-6').addClass('col-md-4');
                    $('#saveEditBtn_cont').removeClass('col-md-6').addClass('col-md-4');
                } else {
                    // Revert button is hidden
                    $('#cancelEditBtn_cont').removeClass('col-md-4').addClass('col-md-6');
                    $('#saveEditBtn_cont').removeClass('col-md-4').addClass('col-md-6');
                }
            }


            // Revert to Original Picture
            $('#revertOriginalBtn').on('click', function() {
                if (originalImage) {
                    $('#modal_1_profileImg').attr('src', originalImage); // Reset image to original
                    croppedImage = null; // Clear the cropped image
                    $('#revertOriginalBtn_cont').hide(); // Hide the revert button
                    adjustButtonColumns(); // Adjust button columns
                }
            });



            // START

            fetchProfiles();
            $('#searchTextbox, #status-select, #from_dateInput, #to_dateInput').on('change keyup', fetchProfiles);

            // Function to fetch and display profiles
            function fetchProfiles() {
                const search = $('#searchTextbox').val();
                const status = $('#status-select').val();
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                $.ajax({
                    url: 'fetch_profiles.php',
                    type: 'GET',
                    data: {
                        search,
                        status,
                        from_date: fromDate,
                        to_date: toDate
                    },
                    success: function(data) {
                        $('#employee-container').html(data);
                    },
                });
            }

            // Handle Edit button click
            $(document).on('click', '.edit-btn', function() {
                const id = $(this).data('id');
                const firstName = $(this).data('first-name');
                const lastName = $(this).data('last-name');
                const rfid = $(this).data('rfid');
                const img = $(this).data('img');

                originalImage = img; // Save the original image for reversion
                croppedImage = null; // Reset cropped image

                // Populate modal fields
                $('#modal_1_profileId').val(id);
                $('#modal_1_firstName').val(firstName).attr('data-current', firstName);
                $('#modal_1_lastName').val(lastName).attr('data-current', lastName);
                $('#modal_1_rfid').val(rfid).attr('data-current', rfid || '');
                $('#modal_1_profileImg').attr('src', img);

                // Triggering validations
                validateFirstName();
                validateLastName();
                validateRFID();

                $('#revertOriginalBtn_cont').hide(); // Initially hide the revert button
                adjustButtonColumns();

                // Show the modal
                $('#EditProfileDetailsModal').modal('show');
            });

            $('#saveEditBtn').on('click', function() {
                if (checksaveEditBtn()) {

                    // Collect data from the form
                    const profileId = $('#modal_1_profileId').val();
                    const firstName = $('#modal_1_firstName').val();
                    const lastName = $('#modal_1_lastName').val();
                    const rfid = $('#modal_1_rfid').val() || null;
                    const croppedImageData = croppedImage || null;

                    // Retrieve current values from the `data-current` attributes
                    const currentFirstName = $('#modal_1_firstName').attr('data-current');
                    const currentLastName = $('#modal_1_lastName').attr('data-current');
                    const currentRFID = $('#modal_1_rfid').attr('data-current') || null;

                    // console.log('First Name:', firstName, 'Current:', currentFirstName);
                    // console.log('Last Name:', lastName, 'Current:', currentLastName);
                    // console.log('RFID:', rfid, 'Current:', currentRFID);
                    // console.log('Cropped Image:', croppedImageData);

                    // Check if there are any changes
                    if (
                        firstName === currentFirstName &&
                        lastName === currentLastName &&
                        rfid === currentRFID &&
                        !croppedImageData
                    ) {
                        // No changes detected
                        Swal.fire({
                            title: 'No Changes Detected!',
                            text: "There's nothing to update.",
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
                        text: "Do you want to save the changes to this profile?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel',
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
                                url: 'update_changes.php',
                                type: 'POST',
                                data: {
                                    profileId: profileId,
                                    firstName: firstName,
                                    lastName: lastName,
                                    rfid: rfid,
                                    croppedImage: croppedImageData,
                                },
                                dataType: 'json',
                                success: function(response) {
                                    Swal.close(); // Close the loading alert

                                    if (response.success) {
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success',
                                            timer: 3000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });

                                        // Refresh the profile list or update the UI
                                        fetchProfiles();
                                        $('#EditProfileDetailsModal').modal('hide');
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message,
                                            icon: 'error',
                                            timer: 3000,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.close(); // Close the loading alert
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'An unexpected error occurred while updating the profile.',
                                        icon: 'error',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                },
                            });
                        }
                    });
                }
            });

            $('#cancelEditBtn').on('click', function() {
                const firstName = $('#modal_1_firstName').val().trim();
                const lastName = $('#modal_1_lastName').val().trim();
                const rfid = $('#modal_1_rfid').val().trim() || null;
                const croppedImageData = croppedImage || null;

                // Retrieve current values from the `data-current` attributes
                const currentFirstName = $('#modal_1_firstName').attr('data-current');
                const currentLastName = $('#modal_1_lastName').attr('data-current');
                const currentRFID = $('#modal_1_rfid').attr('data-current') || null;

                // Check for changes
                const hasChanges =
                    firstName !== currentFirstName ||
                    lastName !== currentLastName ||
                    rfid !== currentRFID ||
                    croppedImageData;

                if (hasChanges) {
                    // Show confirmation dialog if there are changes
                    Swal.fire({
                        title: 'Unsaved Changes Detected',
                        text: 'You have unsaved changes. Are you sure you want to close the editor?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, close it',
                        cancelButtonText: 'No, stay here',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#EditProfileDetailsModal').modal('hide'); // Close modal
                        }
                    });
                } else {
                    // No changes, simply close the modal
                    $('#EditProfileDetailsModal').modal('hide');
                }
            });

            $(document).on('click', '.status-btn', function() {
                const employeeId = $(this).data('id');
                const newStatus = $(this).data('status');
                const endpoint = newStatus === 'INACTIVE' ? 'deactivate_employee.php' : 'reactivate_employee.php';
                const actionText = newStatus === 'INACTIVE' ? 'DEACTIVATE' : 'REACTIVATE';

                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to ${actionText} this employee?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, proceed',
                    cancelButtonText: 'No, cancel',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {

                        if (newStatus === 'ACTIVE') {
                            Swal.fire({
                                title: 'Reactivate Employee',
                                html: `
                                        <label class="mb-2" for="rfidInput">Enter RFID (optional):</label>
                                        <input type="text" id="rfidInput" class="form-control" placeholder="RFID Number">
                                    `,
                                showCancelButton: true,
                                confirmButtonText: 'Reactivate',
                                cancelButtonText: 'Skip RFID',
                                preConfirm: () => {
                                    const rfid = document.getElementById('rfidInput').value.trim();
                                    return rfid;
                                }
                            }).then((result) => {
                                if (result.isConfirmed || result.dismiss === Swal.DismissReason.cancel) {
                                    const rfid = result.value || null; // RFID is null if skipped
                                    $.ajax({
                                        url: endpoint,
                                        type: 'POST',
                                        data: {
                                            employee_id: employeeId,
                                            rfid: rfid
                                        },
                                        dataType: 'json',
                                        success: function(response) {
                                            Swal.fire({
                                                title: response.success ? 'Success!' : 'Error!',
                                                text: response.message,
                                                icon: response.success ? 'success' : 'error',
                                                timer: 3000,
                                                showConfirmButton: false,
                                            });
                                            if (response.success) fetchProfiles();
                                        },
                                        error: function() {
                                            Swal.fire({
                                                title: 'Error!',
                                                text: 'An unexpected error occurred.',
                                                icon: 'error',
                                            });
                                        },
                                    });
                                }
                            });
                        }

                        // INACTIVE
                        else {
                            // Check if the employee has an RFID
                            $.ajax({
                                url: 'check_rfid_status.php',
                                type: 'POST',
                                data: {
                                    employee_id: employeeId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.hasRFID) {
                                        // Open SweetAlert modal if the employee has an RFID
                                        Swal.fire({
                                            title: 'Deactivate Employee',
                                            html: `
                                                    <p>Does the employee return the RFID?</p>
                                                    <input type="text" id="returnedRFID" class="form-control mb-2" placeholder="Enter returned RFID">
                                                    <div id="rfid-feedback" class="invalid-feedback d-none">RFID does not match the database.</div>
                                                `,
                                            showCancelButton: true,
                                            confirmButtonText: 'Proceed',
                                            cancelButtonText: 'Lost RFID',
                                            didOpen: () => {
                                                const input = document.getElementById('returnedRFID');
                                                input.addEventListener('input', function() {
                                                    const rfid = input.value.trim();
                                                    $.ajax({
                                                        url: 'validate_rfid.php',
                                                        type: 'POST',
                                                        data: {
                                                            employee_id: employeeId,
                                                            rfid: rfid
                                                        },
                                                        dataType: 'json',
                                                        success: function(validation) {
                                                            if (validation.valid) {
                                                                input.classList.remove('is-invalid');
                                                                $('#rfid-feedback').addClass('d-none');
                                                                $('.swal2-confirm').prop('disabled', false);
                                                            } else {
                                                                input.classList.add('is-invalid');
                                                                $('#rfid-feedback').removeClass('d-none').text('RFID does not match.');
                                                                $('.swal2-confirm').prop('disabled', true);
                                                            }
                                                        },
                                                    });
                                                });
                                            },
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                const returnedRFID = $('#returnedRFID').val().trim();
                                                deactivateEmployee(employeeId, 'returned', returnedRFID);
                                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                deactivateEmployee(employeeId, 'lost');
                                            }
                                        });
                                    } else {
                                        // Proceed with deactivation if no RFID
                                        deactivateEmployee(employeeId, 'no_rfid');
                                    }
                                },
                            });
                        }

                    }
                });
            });

            function deactivateEmployee(employeeId, type, rfid = null) {
                $.ajax({
                    url: 'deactivate_employee.php',
                    type: 'POST',
                    data: {
                        employee_id: employeeId,
                        type: type,
                        rfid: rfid,
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            title: response.success ? 'Success!' : 'Error!',
                            text: response.message,
                            icon: response.success ? 'success' : 'error',
                            timer: 3000,
                            showConfirmButton: false,
                        });
                        if (response.success) fetchProfiles(); // Refresh profiles
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An unexpected error occurred.',
                            icon: 'error',
                        });
                    },
                });
            }

        });
    </script>
</body>

</html>