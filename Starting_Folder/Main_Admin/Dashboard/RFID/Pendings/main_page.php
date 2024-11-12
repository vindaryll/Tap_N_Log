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

                                <div class="mb-3">
                                    <label for="modal_1_firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modal_1_firstName" name="firstName" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="modal_1_lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modal_1_lastName" name="lastName" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="rfid" class="form-label">RFID Number</label>
                                    <input type="text" class="form-control" id="rfid" name="rfid">
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
                <div class="modal-body" id="same_result" style="max-height: 70vh; overflow-y: auto;">

                    <!-- result examples -->
                    <div class="row d-flex justify-content-center">
                        <!-- Example Card Structure -->
                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="profile-image-container">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Profile Image">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Date Approved:</strong> 2024-10-20</p>
                                    <p class="card-text"><strong>Name:</strong> John Doe</p>
                                    <p class="card-text"><strong>Type of Profile:</strong> OJT</p>
                                    <p class="card-text"><strong>Status:</strong> Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="profile-image-container">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Profile Image">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Date Approved:</strong> 2024-10-20</p>
                                    <p class="card-text"><strong>Name:</strong> John Doe</p>
                                    <p class="card-text"><strong>Type of Profile:</strong> OJT</p>
                                    <p class="card-text"><strong>Status:</strong> Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="profile-image-container">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Profile Image">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Date Approved:</strong> 2024-10-20</p>
                                    <p class="card-text"><strong>Name:</strong> John Doe</p>
                                    <p class="card-text"><strong>Type of Profile:</strong> OJT</p>
                                    <p class="card-text"><strong>Status:</strong> Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="profile-image-container">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Profile Image">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Date Approved:</strong> 2024-10-20</p>
                                    <p class="card-text"><strong>Name:</strong> John Doe</p>
                                    <p class="card-text"><strong>Type of Profile:</strong> OJT</p>
                                    <p class="card-text"><strong>Status:</strong> Active</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-3">
                                <div class="profile-image-container">
                                    <img src="https://via.placeholder.com/300" class="card-img-top" alt="Profile Image">
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><strong>Date Approved:</strong> 2024-10-20</p>
                                    <p class="card-text"><strong>Name:</strong> John Doe</p>
                                    <p class="card-text"><strong>Type of Profile:</strong> OJT</p>
                                    <p class="card-text"><strong>Status:</strong> Active</p>
                                </div>
                            </div>
                        </div>
                        <!-- Repeat cards as needed for each similar profile -->
                    </div>
                </div>
                <div class="modal-footer">

                    <div class="row p-0 w-100">
                        <div id="cancelEditBtn_cont" class="col-md-6 col-sm-12 p-1">
                            <button type="button" class="btn btn-danger w-100" id="discardSimilarBtn">DISCARD</button>
                        </div>
                        <div id="saveEditBtn_cont" class="col-md-6 col-sm-12 p-1">
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
            window.viewDetails = function(profileId) {
                $.ajax({
                    url: 'get_profile_details.php',
                    type: 'GET',
                    data: {
                        profile_id: profileId
                    },
                    success: function(response) {
                        var profile = JSON.parse(response);
                        $('#modal_1_profileId').val(profile.profile_id);
                        $('#modal_1_firstName').val(profile.first_name);
                        $('#modal_1_lastName').val(profile.last_name);
                        $('#modal_1_profileType').val(profile.type_of_profile);
                        $('#modal_1_profileImg').attr('src', '/TAPNLOG/Image/Pending/' + profile.profile_img);
                        $('#profileDetailsModal').modal('show');
                    }
                });
            }

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



        });
    </script>
</body>

</html>