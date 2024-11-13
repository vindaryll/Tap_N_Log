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

    <!-- Sweet alert 2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Co-Admin Account | Main Admin</title>
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
    </style>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container row col-sm-12">

            <div class="container col-sm-12 mb-3">
                <button type="button" class="btn btn-primary" id="backbtn">Back</button>
                <!-- Button to Open Modal -->
                <button data-bs-toggle="modal" data-bs-target="#profileDetailsModal" class="btn btn-primary">Try for View Profile</button>
                <!-- Button to Open Modal -->
                <button data-bs-toggle="modal" data-bs-target="#duplicateProfileModal" class="btn btn-primary">Try for duplicate Profile</button>
            </div>

            <div class="container col-sm-12">
                <div class="container mt-5">
                    <h2>Profile Management</h2>
                    <div class="mb-3">
                        <input type="text" id="searchTextbox" class="form-control" placeholder="Search by name or ID">
                    </div>

                    <div class="row">
                        <div class="container col-lg-6">
                            <div class="row">
                                <div class="container col-md-6">
                                    <div class="mb-3">
                                        <label for="from_dateInput" class="form-label">From Date</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="from_dateInput">
                                            <span class="input-group-text">
                                                <i class="bi bi-calendar-date"></i> <!-- Bootstrap Icon for Calendar -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="container col-md-6">
                                    <div class="mb-3">
                                        <label for="to_dateInput" class="form-label">To Date</label>
                                        <div class="input-group">
                                            <input type="date" class="form-control" id="to_dateInput">
                                            <span class="input-group-text">
                                                <i class="bi bi-calendar-date"></i> <!-- Bootstrap Icon for Calendar -->
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6"></div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mt-4">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date (YYYY/MM/DD)</th>
                                    <th>Name</th>
                                    <th class="text-center d-flex justify-content-center">
                                        <select id="profileType" class="form-select form-select-sm">
                                            <option value="">Type of Profiles</option>
                                            <option value="OJT">On the job Trainees</option>
                                            <option value="CFW">Cash for Work Staff</option>
                                            <option value="EMPLOYEE">Employees</option>
                                        </select>
                                    </th>
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

    <!-- Profile Details Modal 1 -->
    <div class="modal fade" id="profileDetailsModal" tabindex="-1" aria-labelledby="profileDetailsModalLabel" aria-hidden="true">
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
                                    <label for="modal_1_profileType" class="form-label">Type of Profile</label>
                                    <select class="form-select" id="modal_1_profileType" name="type_of_profile" required disabled>
                                        <option value="OJT">On-the-job training</option>
                                        <option value="CFW">Cash for Work</option>
                                        <option value="EMPLOYEE">Employee</option>
                                    </select>
                                </div>

                                <!-- First name -->
                                <div class="mb-3">
                                    <label for="modal_1_firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modal_1_firstName" name="firstName" disabled>
                                    <div id="firstName-feedback" class="invalid-feedback"></div>
                                </div>

                                <!-- Last name -->
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modal_1_lastName" name="lastName" disabled>
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


                            <div id="discardBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-danger w-100" id="discardBtn">DISCARD</button>
                            </div>
                            <div id="editBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-warning w-100" id="editBtn">EDIT</button>
                            </div>
                            <div id="approveBtn_cont" class="col-md-4 col-sm-12 p-1">
                                <button type="button" class="btn btn-success w-100" id="approveBtn">APPROVE</button>
                            </div>

                            <!-- For Edit Buttons -->
                            <div id="cancelEditBtn_cont" class="col-md-6 col-sm-12 p-1" style="display:none;">
                                <button type="button" class="btn btn-danger w-100" id="cancelEditBtn">CANCEL</button>
                            </div>
                            <div id="saveEditBtn_cont" class="col-md-6 col-sm-12 p-1" style="display:none;">
                                <button type="button" class="btn btn-success w-100" id="saveEditBtn">SAVE CHANGES</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Duplicate Profile Modal -->
    <div class="modal fade" id="duplicateProfileModal" tabindex="-1" aria-labelledby="duplicateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="duplicateProfileModalLabel">SIMILAR PROFILES</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                    <!-- RESULTS -->
                    <div class="row d-flex justify-content-center" id="same_result">


                    </div>
                </div>
                <div class="modal-footer">

                    <div class="row p-0 w-100">
                        <div id="discardSimilarBtn_cont" class="col-md-6 col-sm-12 p-1">
                            <button type="button" class="btn btn-danger w-100" id="discardSimilarBtn">DISCARD</button>
                        </div>
                        <div id="approveSimilarBtn_cont" class="col-md-6 col-sm-12 p-1">
                            <button type="button" class="btn btn-success w-100" id="approveSimilarBtn">APPROVE ANYWAY</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {

            // Get today's date in local time (correcting for time zone)
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
            const day = String(today.getDate()).padStart(2, '0'); // Ensures the day is two digits
            const todayFormatted = `${year}-${month}-${day}`; // Format as YYYY-MM-DD

            // Set initial value and max attribute for both inputs
            $('#from_dateInput, #to_dateInput').val(todayFormatted).attr('max', todayFormatted);


            // VALIDATION OF DATE INPUTS
            // Main validation function to check dates
            function validateDateInputs() {
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
                        $('#from_dateInput').val(toDate);
                    }
                }
            }

            // Event handlers for input and change events
            $('#from_dateInput').on('input change', function() {
                const fromDate = $(this).val();
                const toDate = $('#to_dateInput').val();

                if (!fromDate) {
                    // Default to either toDate or today if fromDate is empty
                    $(this).val(toDate || todayFormatted);
                } else if (toDate && new Date(fromDate) > new Date(toDate)) {
                    // Set fromDate to toDate if it's greater than toDate
                    $(this).val(toDate);
                }
                validateDateInputs();
            });

            $('#to_dateInput').on('input change', function() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $(this).val();

                if (!toDate) {
                    // Default to today's date if toDate is empty
                    $(this).val(todayFormatted);
                } else if (new Date(toDate) > new Date(todayFormatted)) {
                    // Set toDate to today if it exceeds today's date
                    $(this).val(todayFormatted);
                } else if (fromDate && new Date(fromDate) > new Date(toDate)) {
                    // Reset fromDate if it is greater than the updated toDate
                    $('#from_dateInput').val(toDate);
                }
                validateDateInputs();
            });


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
                validateRFID();
            });


            // START
            fetchProfiles();

            // LIVE SEARCH
            $('#searchTextbox, #profileType, #from_dateInput, #to_dateInput').on('change keyup', function() {
                fetchProfiles();
            });

            function fetchProfiles() {
                var search = $('#searchTextbox').val();
                var type = $('#profileType').val();
                var fromDate = $('#from_dateInput').val();
                var toDate = $('#to_dateInput').val();

                $.ajax({
                    url: 'fetch_profiles.php',
                    type: 'GET',
                    data: {
                        search: search,
                        type: type,
                        from_date: fromDate,
                        to_date: toDate
                    },
                    success: function(data) {
                        $('#resultTableBody').html(data);
                    }
                });
            }

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
                // Revert fields to original values
                $('#modal_1_firstName').val(originalData.firstName);
                $('#modal_1_lastName').val(originalData.lastName);
                $('#modal_1_profileType').val(originalData.profileType);

                $('#firstName-feedback').text('').removeClass('invalid-feedback');
                $('#lastName-feedback').text('').removeClass('invalid-feedback');
                $('#modal_1_firstName').removeClass('is-invalid');
                $('#modal_1_lastName').removeClass('is-invalid');

                // Reset image in case it was modified
                $('#modal_1_profileImg').attr('src', originalData.imgSrc);

                // Hide edit buttons and show action buttons
                $('#cancelEditBtn_cont, #saveEditBtn_cont').hide();
                $('#discardBtn_cont, #editBtn_cont, #approveBtn_cont').show();

                // Disable editing
                $('#modal_1_firstName, #modal_1_lastName, #modal_1_profileType').prop('disabled', true);
                $('#modal_1_rfid').prop('disabled', false);
            });

            $('#saveEditBtn').click(function() {
                // Validate fields before submission
                validateFirstName();
                validateLastName();

                // If validation fails, display an alert
                if (checksaveEditBtn()) {
                    if (confirm("Are you sure u want to save changes?")) {
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
                                    alert(response.message); // Display success message

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
                                    alert('Error: ' + response.message); // Display error message from server
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error details:', status, error); // Log the error for debugging
                                alert('An error occurred while saving changes. Please try again.');
                            }
                        });
                    }
                }


            });

            // View details: discard button
            $('#discardBtn').on('click', function() {
                const profileId = $('#modal_1_profileId').val(); // Get profile ID from the hidden input in the modal

                if (confirm('Are you sure you want to delete this profile? This action cannot be undone.')) {
                    $.ajax({
                        url: 'delete_profile.php', // URL to the PHP script
                        type: 'POST',
                        data: {
                            profile_id: profileId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);

                            if (result.success) {
                                alert(result.message); // Show success message
                                $('#profileDetailsModal').modal('hide'); // Close the modal
                                fetchProfiles(); // Refresh the table or data
                            } else {
                                alert('Error: ' + result.message); // Show error message
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred while deleting the profile. Please try again.');
                            console.error('Error details:', status, error); // Log details for debugging
                        }
                    });
                }
            });



            // FUNCTIONS FOR FEEDBACK MESSAGES OF MODAL 1

            // Add input event listeners
            $('#modal_1_firstName').on('input', validateFirstName);
            $('#modal_1_lastName').on('input', validateLastName);
            $('#modal_1_rfid').on('input', validateRFID);

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
                        alert('An error occurred while validating RFID.');
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
                        if (response.duplicates.length > 0) {
                            // Populate the modal with duplicate profiles
                            const modalBody = $('#same_result');
                            modalBody.empty();

                            response.duplicates.forEach((profile) => {
                                // Ensure the image path is valid or use a default image
                                const imgPath = profile.img && profile.img.trim() !== "" ?
                                    `/TAPNLOG/Image/${profile.type.toUpperCase()}/${profile.img}` :
                                    "/TAPNLOG/Image/LOGO_AND_ICONS/default_avatar.png";

                                // Handle null or undefined RFID
                                const rfidValue = profile.rfid ? profile.rfid : "N/A";

                                // Create a card for each duplicate profile
                                const card = `
                                    <div class="col-lg-6">
                                        <div class="card mb-3">
                                            <div class="profile-image-container">
                                                <img src="${imgPath}" class="card-img-top" alt="Profile Image">
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text"><strong>Name:</strong> ${profile.first_name} ${profile.last_name}</p>
                                                <p class="card-text"><strong>Type:</strong> ${profile.type}</p>
                                                <p class="card-text"><strong>Status:</strong> ${profile.status}</p>
                                                <p class="card-text"><strong>RFID:</strong> ${rfidValue}</p>
                                            </div>
                                        </div>
                                    </div>`;
                                modalBody.append(card);
                            });

                            // Show the duplicate modal
                            $('#duplicateProfileModal').modal('show');
                        } else {

                            if (confirm("Are you sure you want to approve this?")) {
                                // No duplicates, approve the profile
                                approveProfile(profileId, firstName, lastName, profileType, profileImg, rfid);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error occurred while checking duplicates.');
                        console.error(status, error);
                    },
                });


            });

            $('#approveSimilarBtn').on('click', function() {
                if (confirm("Are you sure you want to save this profile?")) {
                    const profileId = $('#modal_1_profileId').val();
                    const firstName = $('#modal_1_firstName').val().trim();
                    const lastName = $('#modal_1_lastName').val().trim();
                    const profileType = $('#modal_1_profileType').val();
                    const profileImg = $('#modal_1_profileImg').attr('src').split('/').pop();
                    const rfid = $('#modal_1_rfid').val().trim();

                    approveProfile(profileId, firstName, lastName, profileType, profileImg, rfid);
                }
            });

            // View details: discard button
            $('#discardSimilarBtn').on('click', function() {
                const profileId = $('#modal_1_profileId').val(); // Get profile ID from the hidden input in the modal

                if (confirm('Are you sure you want to delete this profile? This action cannot be undone.')) {
                    $.ajax({
                        url: 'delete_profile.php', // URL to the PHP script
                        type: 'POST',
                        data: {
                            profile_id: profileId
                        },
                        success: function(response) {
                            const result = JSON.parse(response);

                            if (result.success) {
                                alert(result.message); // Show success message
                                $('#profileDetailsModal, #duplicateProfileModal').modal('hide');
                                fetchProfiles(); // Refresh the table or data
                            } else {
                                alert('Error: ' + result.message); // Show error message
                            }
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred while deleting the profile. Please try again.');
                            console.error('Error details:', status, error); // Log details for debugging
                        }
                    });
                }
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
                            alert(result.message);
                            fetchProfiles(); // Refresh the table
                            $('#profileDetailsModal, #duplicateProfileModal').modal('hide');
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Error occurred while approving the profile.');
                        console.error(status, error);
                    },
                });
            }

        });
    </script>
</body>

</html>