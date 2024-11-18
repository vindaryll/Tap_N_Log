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

    <title>OJT Profiles | Main Admin</title>
    <style>
        #profile-container {
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
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name or RFID">
                    </div>

                    <div class="row">
                        <!-- Add Filter and Sort Buttons -->
                        <div class="d-flex justify-content-start mb-3">
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
                            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#sortModal">Sort</button>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center">

                        <!-- Profiles Section -->
                        <div id="profile-container" class="row d-flex justify-content-center">
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
                            <label for="from_dateInput" class="form-label">From</label>
                            <input type="date" id="from_dateInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="to_dateInput" class="form-label">To</label>
                            <input type="date" id="to_dateInput" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="">ALL</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="INACTIVE">INACTIVE</option>
                            </select>
                        </div>
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
                    <div>
                        <label class="form-label">Sort by Date Approved:</label>
                        <div>
                            <input type="radio" id="sortDateAsc" name="sortDate" value="asc">
                            <label for="sortDateAsc">Ascending</label><br>

                            <input type="radio" id="sortDateDesc" name="sortDate" value="desc">
                            <label for="sortDateDesc">Descending</label>
                        </div>
                    </div>
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

    <!-- Edit Profile Details Modal-->
    <div class="modal fade" id="EditProfileDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="EditProfileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
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

            $('#modal_1_firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Add event listener for the last name input
            $('#modal_1_lastName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // VALIDATE DATE INPUTS

            // Get today's date in local time (correcting for time zone)
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(today.getDate()).padStart(2, '0'); // Ensures the day is two digits
            const todayFormatted = `${year}-${month}-${day}`; // Format as YYYY-MM-DD

            // Set max attribute for both inputs
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
                const profileId = $('#modal_1_profileId').val(); // Get the current profile ID
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
                        ojt_id: profileId // Include the current profile ID
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
                            timer: 1500,
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
                    confirmButtonText: 'UPLOAD',
                    cancelButtonText: 'CANCEL',
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
                    from_date: $('#from_dateInput').val(),
                    to_date: $('#to_dateInput').val(),
                    status: $('#status').val(),
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
                    date: $('input[name="sortDate"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchProfiles();
                $('#sortModal').modal('hide');
            });

            // Reset Sort
            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortDate"]').prop('checked', false);
                $('input[name="sortName"]').prop('checked', false);
                fetchProfiles();
            });

            // Initial Fetch
            fetchProfiles();



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
                            text: "You have not made any changes to the profile.",
                            icon: 'info',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        return;
                    }


                    // Show a confirmation message before proceeding
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Do you want to save the changes to this profile?",
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
                                            timer: 1500,
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
                                            timer: 1500,
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
                                        timer: 1500,
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
                        text: 'You have unsaved changes. Are you sure you want to DISCARD the changes?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'YES, DISCARD CHANGES',
                        cancelButtonText: 'NO, KEEP EDITING',
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
                const profileId = $(this).data('id');
                const newStatus = $(this).data('status'); // ACTIVE or INACTIVE
                const actionText = newStatus === 'INACTIVE' ? 'DEACTIVATE' : 'REACTIVATE';

                // Step 1: Show initial confirmation modal
                Swal.fire({
                    title: `Are you sure?`,
                    text: `Do you want to ${actionText} this profile?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'YES',
                    cancelButtonText: 'NO',
                    reverseButtons: true
                }).then((result) => {

                    if (result.isConfirmed) {
                        if (newStatus === 'INACTIVE') {
                            // Check if the profile has an RFID
                            $.ajax({
                                url: 'check_rfid_status.php',
                                type: 'POST',
                                data: {
                                    ojt_id: profileId
                                },
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        if (response.hasRFID) {
                                            // profile has an RFID; show the modal
                                            showDeactivateModal(profileId, response.rfid);
                                            $('#returnedRFID').focus();
                                        } else {
                                            // No RFID; directly deactivate without modal
                                            deactivateProfile(profileId, 'no_rfid', null);
                                        }
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message || 'Unable to fetch RFID details.',
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
                                        text: 'An error occurred while checking RFID.',
                                        icon: 'error',
                                        timer: 1500,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                },
                            });
                        } else {
                            // Reactivate profile
                            showReactivateModal(profileId);
                            $('#rfidInput').focus();
                        }
                    }


                });
            });

            // Show Deactivate Modal
            function showDeactivateModal(profileId) {
                Swal.fire({
                    title: 'Deactivate Profile',
                    html: `
                            <p>Does the trainee return the RFID?</p>
                            <input type="text" id="returnedRFID" class="form-control mb-2" placeholder="Enter returned RFID">
                            <div id="rfid-feedback_deactivate" class="invalid-feedback d-none">RFID validation feedback.</div>
                        `,
                    showCancelButton: false,
                    confirmButtonText: 'YES',
                    showDenyButton: true,
                    denyButtonText: 'LOST RFID',
                    reverseButtons: true,
                    didOpen: () => {
                        const inputElement = $('#returnedRFID');

                        validateDeactivateRFID(profileId, inputElement); // Initial validation

                        // Add event listener for input validation
                        inputElement.on('input keyup', function(e) {
                            validateDeactivateRFID(profileId, inputElement);
                        });

                        // Add event listener for input validation
                        inputElement.on('keydown', function(e) {
                            // Clear the input field on Backspace or Delete
                            if (e.keyCode === 8 || e.keyCode === 46) {
                                $(this).val('');
                            }
                        });
                    },
                    preConfirm: () => {
                        const rfid = $('#returnedRFID').val().trim();
                        return {
                            rfid
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Confirmed with "Yes"
                        const rfid = result.value.rfid;

                        // Proceed with the deactivation process for returned RFID
                        deactivateProfile(profileId, 'returned', rfid);
                    } else if (result.isDenied) {

                        // Proceed with the deactivation process for lost RFID
                        deactivateProfile(profileId, 'lost', null);
                    }
                });
            }

            // Reusable function for deactivating the profile
            function deactivateProfile(profileId, type, rfid) {
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we deactivate the profile.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });

                $.ajax({
                    url: 'deactivate.php',
                    type: 'POST',
                    data: {
                        ojt_id: profileId,
                        rfid,
                        type,
                    },
                    success: function(response) {
                        Swal.close(); // Close the loading modal
                        const result = JSON.parse(response);

                        Swal.fire({
                            title: result.success ? 'Success!' : 'Error!',
                            text: result.message,
                            icon: result.success ? 'success' : 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        if (result.success) {
                            fetchProfiles(); // Refresh profile list
                        }
                    },
                    error: function() {
                        Swal.close(); // Close the loading modal
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred while deactivating the profile.',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    },
                });
            }

            function validateDeactivateRFID(profileId, inputElement) {
                const rfid = inputElement.val().trim();
                const feedback = $('#rfid-feedback_deactivate');

                // Reset feedback and button state
                feedback.addClass('d-none').text('');
                inputElement.removeClass('is-invalid');
                $('.swal2-confirm').prop('disabled', true); // Disable Deactivate button initially

                // Check for empty value
                if (!rfid) {
                    feedback.removeClass('d-none').text('RFID cannot be empty.');
                    inputElement.addClass('is-invalid');
                    checkDeactivateButtonState(inputElement);
                    return;
                }

                // Check for non-alphanumeric characters
                if (!/^[A-Za-z0-9]+$/.test(rfid)) {
                    feedback.removeClass('d-none').text('RFID must be alphanumeric.');
                    inputElement.addClass('is-invalid');
                    checkDeactivateButtonState(inputElement);
                    return;
                }

                // Check if the RFID matches the profile's assigned RFID
                $.ajax({
                    url: 'check_rfid_status.php', // Endpoint to validate RFID
                    type: 'POST',
                    data: {
                        ojt_id: profileId,
                        rfid: rfid
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Input RFID:', rfid);
                        console.log('Response RFID:', response.rfid);

                        if (response.success && response.hasRFID) {
                            if (response.rfid === rfid) {
                                feedback.addClass('d-none').text('');
                                inputElement.removeClass('is-invalid');
                                checkDeactivateButtonState(inputElement);
                            } else {
                                feedback.removeClass('d-none').text('RFID does not match the trainee\'s record.');
                                inputElement.addClass('is-invalid');
                                checkDeactivateButtonState(inputElement);
                            }
                        } else if (response.success && !response.hasRFID) {
                            feedback.removeClass('d-none').text('No RFID found for this trainee.');
                            inputElement.addClass('is-invalid');
                            checkDeactivateButtonState(inputElement);
                        }
                    },
                    error: function() {
                        // Handle AJAX error
                        feedback.removeClass('d-none').text('Error occurred while validating RFID.');
                        inputElement.addClass('is-invalid');
                        checkDeactivateButtonState(inputElement);
                    }
                });
            }

            // Helper function to check Reactivate button state
            function checkDeactivateButtonState(inputElement) {
                const isInvalid = inputElement.hasClass('is-invalid');
                // Disable Reactivate button if input is invalid
                $('.swal2-confirm').prop('disabled', isInvalid);
            }


            // Show Reactivate Modal
            function showReactivateModal(profileId) {
                Swal.fire({
                    title: 'Reactivate Profile',
                    html: `
                            <label class="mb-2" for="rfidInput">Enter RFID (optional):</label>
                            <input type="text" id="rfidInput" class="form-control mb-2" placeholder="Enter RFID Number">
                            <div id="rfid-feedback_reactivate" class="invalid-feedback d-none">RFID validation feedback.</div>
                        `,
                    confirmButtonText: 'Reactivate',
                    showCancelButton: false,
                    didOpen: () => {
                        const inputElement = $('#rfidInput');

                        validateReactivateRFID(profileId, inputElement); // Initial validation

                        inputElement.on('input keyup', function(e) {
                            validateReactivateRFID(profileId, inputElement);
                        });

                        // Add event listener for input validation
                        inputElement.on('keydown', function(e) {
                            // Clear the input field on Backspace or Delete
                            if (e.keyCode === 8 || e.keyCode === 46) {
                                $(this).val('');
                            }
                        });


                    },
                    preConfirm: () => {
                        const rfid = $('#rfidInput').val().trim();
                        return {
                            rfid
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const rfid = result.value.rfid;

                        // Proceed with the reactivation process
                        $.ajax({
                            url: 'reactivate.php',
                            type: 'POST',
                            data: {
                                ojt_id: profileId,
                                rfid
                            },
                            success: function(response) {
                                const result = JSON.parse(response);
                                Swal.fire({
                                    title: result.success ? 'Success!' : 'Error!',
                                    text: result.message,
                                    icon: result.success ? 'success' : 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });

                                if (result.success) {
                                    fetchProfiles(); // Refresh profile list
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'An error occurred while reactivating the profile.',
                                    icon: 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            }


            function validateReactivateRFID(profileId, inputElement) {
                const rfid = inputElement.val().trim(); // Get the trimmed input value
                const feedback = $('#rfid-feedback_reactivate'); // Feedback element

                // Reset feedback and button state
                feedback.addClass('d-none').text('');
                inputElement.removeClass('is-invalid');
                $('.swal2-confirm').prop('disabled', false); // Enable Reactivate button initially

                // Can be empty
                if (!rfid) {
                    return;
                }

                // Validation for non-alphanumeric characters
                if (!/^[A-Za-z0-9]+$/.test(rfid)) {
                    feedback.removeClass('d-none').text('RFID must be alphanumeric.');
                    inputElement.addClass('is-invalid');
                    checkReactivateButtonState(inputElement); // Recheck button state
                    return;
                }

                // AJAX call to check if RFID already exists
                $.ajax({
                    url: 'check_rfid.php', // Backend endpoint to validate RFID
                    type: 'POST',
                    data: {
                        rfid: rfid,
                        ojt_id: profileId // Current profile ID for context
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.exists) {
                            // RFID is valid and unique
                            $('.swal2-confirm').prop('disabled', false); // Enable Reactivate button
                        } else {
                            // RFID already exists
                            feedback.removeClass('d-none').text('RFID already exists for another trainee.');
                            inputElement.addClass('is-invalid');
                            checkReactivateButtonState(inputElement); // Recheck button state
                        }
                    },
                    error: function() {
                        // Handle AJAX errors
                        feedback.removeClass('d-none').text('Error occurred while validating RFID.');
                        inputElement.addClass('is-invalid');
                        checkReactivateButtonState(inputElement); // Recheck button state
                    }
                });
            }

            // Helper function to check Reactivate button state
            function checkReactivateButtonState(inputElement) {
                const isInvalid = inputElement.hasClass('is-invalid');
                // Disable Reactivate button if input is invalid
                $('.swal2-confirm').prop('disabled', isInvalid);
            }


        });
    </script>
</body>

</html>