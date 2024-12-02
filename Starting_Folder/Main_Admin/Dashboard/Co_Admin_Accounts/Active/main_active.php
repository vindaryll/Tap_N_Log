<?php

// FROND-END for MAIN PAGE of Co-admin Active Account

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

// Fetch all stations
$stationsSql = "SELECT station_id, station_name FROM stations";
$stationsResult = $conn->query($stationsSql);

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

    <title>Active Co-Admin Accounts | Main Admin</title>

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

        table.table td {
            text-align: center;
            vertical-align: middle;
        }

        table.table th {
            text-align: center;
            vertical-align: middle;
        }

        .table-responsive {
            background-color: white;
            height: calc(100vh - 320px);
            overflow-y: auto;
            margin-bottom: 10px;
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
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container-fluid row col-sm-12 p-0">

            <div class="container col-sm-12">
                <a href="#" class="back-icon" id="backbtn" style="position: absolute;"><i class="bi bi-arrow-left"></i></a>
            </div>

            <div class="container-fluid col-sm-12 mt-sm-0 mt-4 px-2">

                <div class="container-fluid text-center">
                    <h2 class="text-center w-100">ACTIVE CO-ADMIN ACCOUNTS</h2>

                    <!-- Textbox for search -->
                    <input type="text" id="search" class="form-control mb-3" placeholder="Search by guard name or ID">

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

                    <div class="table-responsive mb-2">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>DATE</th>
                                    <th>NAME</th>
                                    <th>STATION</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="guardTableBody">
                                <!-- Results will be inserted here -->

                            </tbody>
                        </table>
                    </div>


                    <div class="row d-flex justify-content-between mb-2">
                        <div class="col-md-4 col-12 mb-2">
                            <button type="button" id="goToInactive" class="btn btn-primary btn-custom w-100 h-100 p-2">INACTIVE CO-ADMIN ACCOUNTS</button>
                        </div>
                        <div class="col-md-4 col-12 mb-2">
                            <button type="button" id="addGuardModalButton" class="btn btn-success btn-custom w-100 h-100 p-2" data-bs-toggle="modal" data-bs-target="#addGuardModal">ADD CO-ADMIN</button>
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
                    <h5 class="modal-title" id="filterModalLabel"><strong>FILTER ACCOUNTS</strong></h5>
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
                            <label for="filterStationSelect" class="form-label"><strong>STATIONS</strong></label>
                            <select id="filterStationSelect" class="form-select">
                                <option value="">ALL STATIONS</option>
                                <?php
                                $stationsResult->data_seek(0);
                                while ($station = $stationsResult->fetch_assoc()) {
                                    echo "<option value='" . $station['station_id'] . "'>" . htmlspecialchars($station['station_name']) . "</option>";
                                }
                                ?>
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
                    <h5 class="modal-title" id="sortModalLabel"><strong>SORT ACCOUNTS</strong></h5>
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


    <!-- Modal for Adding Co-admin -->
    <div class="modal fade" id="addGuardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addGuardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="addGuardModalLabel"><strong>ADD NEW CO-ADMIN</strong></h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <!-- Form starts here -->
                    <form id="addGuardForm">

                        <div class="mb-3">
                            <label for="guard_name" class="form-label "><strong>CO-ADMIN NAME</strong></label>
                            <input type="text" id="guard_name" name="guard_name" class="form-control" required>
                            <div id="addName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label "><strong>USERNAME</strong></label>
                            <input type="text" id="username" name="username" class="form-control" required>
                            <div id="addUsername-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label "><strong>EMAIL</strong></label>
                            <input type="email" id="email" name="email" class="form-control" required>
                            <div id="addEmail-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label "><strong>PASSWORD</strong></label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="addPassword-feedback1" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label "><strong>CONFIRM PASSWORD</strong></label>
                            <div class="input-group">
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="addPassword-feedback2" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="station" class="form-label "><strong>STATION</strong></label>
                            <select id="station" name="station" class="form-select" required>
                                <?php
                                $stationsResult->data_seek(0);
                                while ($row = $stationsResult->fetch_assoc()): ?>
                                    <option value="<?php echo $row['station_id']; ?>"><?php echo htmlspecialchars($row['station_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <div class="col-12 d-flex justify-content-end">
                                <button type="button" id="submitAddGuardButton" class="btn btn-primary btn-custom col-6 mt-3">ADD CO-ADMIN</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Guard Details -->
    <div class="modal fade" id="guardDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="guardDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guardDetailsLabel"><strong>CO-ADMIN DETAILS</strong></h5>
                    <button type="button" id="edit_closeButton" class="btn-close up btn-cont-1" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Editable inputs -->
                    <form id="editGuardForm">
                        <input type="hidden" name="guard_id" id="guard_id">

                        <div class="mb-3">
                            <label for="modalGuardName" class="form-label "><strong>CO-ADMIN NAME</strong></label>
                            <input type="text" class="form-control" id="modalGuardName" name="guard_name" disabled>
                            <div id="editName-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="modalStationName" class="form-label "><strong>STATION NAME</strong></label>
                            <select id="modalStationName" name="station" class="form-select" required disabled>
                                <?php
                                // Reset the station result to fetch again
                                $stationsResult->data_seek(0); // Reset to the first result
                                while ($row = $stationsResult->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($row['station_id']); ?>"><?php echo htmlspecialchars($row['station_name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="modalUsername" class="form-label "><strong>USERNAME</strong></label>
                            <input type="text" class="form-control" id="modalUsername" name="username" disabled>
                            <div id="editUsername-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <label for="modalEmail" class="form-label "><strong>EMAIL</strong></label>
                            <input type="email" class="form-control" id="modalEmail" name="email" disabled>
                            <div id="editEmail-feedback" class="invalid-feedback"> <!-- Message will display here --> </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">

                    <div class="w-100 mt-3 btn-cont-1">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="changePasswordButton" class="btn btn-primary btn-custom text-uppercase w-100" data-bs-toggle="modal" data-bs-target="#passwordChangeModal">CHANGE PASSWORD</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button id="editButton" class="btn btn-primary btn-custom text-uppercase w-100">EDIT</button>
                            </div>
                        </div>
                    </div>

                    <div class="w-100 mt-3 btn-cont-2">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="discardButton" class="btn btn-danger btn-custom text-uppercase w-100">CANCEL</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button type="button" id="saveButton" class="btn btn-success btn-custom text-uppercase w-100">SAVE</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Password Change -->
    <div class="modal fade" id="passwordChangeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="passwordChangeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordChangeLabel">CHANGE PASSWORD</h5>
                    <!-- No close button here -->
                </div>
                <div class="modal-body">
                    <form id="passwordChangeForm">
                        <input type="hidden" name="password_guard_id" id="password_guard_id">
                        <input type="hidden" name="password_guard_name" id="password_guard_name">

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="New Password" tabindex="1" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="changePassword-feedback1" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" placeholder="Confirm Password" tabindex="2" required>
                                <span class="input-group-text toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="changePassword-feedback2" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <div class="w-100 mt-3 btn-cont-1">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="goBackButton" class="btn btn-danger btn-custom text-uppercase w-100" tabindex="4">CANCEL</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button id="submitPasswordButton" class="btn btn-success btn-custom text-uppercase w-100" tabindex="3">SAVE</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>
        // Function to open details modal and populate data
        function openDetailsModal(guard) {

            populateDetails(guard);

            // Disable inputs initially
            $('#modalGuardName, #modalStationName, #modalUsername, #modalEmail').prop('disabled', true);

            // Removing feedbacks initially
            $('#editName-feedback').text('').removeClass('invalid-feedback');
            $('#editUsername-feedback').text('').removeClass('invalid-feedback');
            $('#editEmail-feedback').text('').removeClass('invalid-feedback');
            $('#modalGuardName').removeClass('is-invalid');
            $('#modalUsername').removeClass('is-invalid');
            $('#modalEmail').removeClass('is-invalid');

            $('.btn-cont-1').show(); // edit and change pass
            $('.btn-cont-2').hide(); // save and discard

            // Show the modal
            $('#guardDetailsModal').modal('show');

            // Store the recent/current/original data when the modal opens
            current = {
                guard_id: guard.guard_id,
                guard_name: guard.guard_name,
                station_id: guard.station_id,
                username: guard.username,
                email: guard.email
            };
        }

        function populateDetails(guard) {
            // populating the inputs from json file
            $('#guard_id').val(guard.guard_id);
            $('#modalGuardName').val(guard.guard_name);
            $('#modalStationName').val(guard.station_id);
            $('#modalUsername').val(guard.username);
            $('#modalEmail').val(guard.email);
        }

        // Function to deactivate guard
        function deactivateGuard(guardId) {
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to DEACTIVATE account number: ${guardId}?`,
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
                        url: 'deactivate_guard.php',
                        type: 'POST',
                        data: {
                            guard_id: guardId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Success!',
                                    text: response.message,
                                    icon: 'success',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                                fetchActiveGuards();
                            } else {
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: `An unexpected error occurred: ${error}`,
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    });
                }
            });
        }

        let filters = {};
        let sort = {};
        let search = '';

        function fetchActiveGuards() {
            $.ajax({
                url: 'fetch_active_guards.php',
                type: 'POST',
                data: {
                    search: search,
                    filters: filters,
                    sort: sort,
                },
                success: function(data) {
                    $('#guardTableBody').html(data);
                },
            });
        }



        $(document).ready(function() {

            $('#guard_name').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Add event listener for the last name input
            $('#modalGuardName').on('input', function() {
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

            // START
            $('#applyFilters').on('click', function() {
                filters = {
                    fromDate: $('#from_dateInput').val(),
                    toDate: $('#to_dateInput').val(),
                    station: $('#filterStationSelect').val(),
                };
                fetchActiveGuards();
                $('#filterModal').modal('hide');
            });

            $('#resetFilters').on('click', function() {
                filters = {};
                $('#filterForm')[0].reset();
                fetchActiveGuards();
            });

            $('#search').on('input', function() {
                search = $(this).val().trim();
                fetchActiveGuards();
            });

            $('#applySort').on('click', function() {
                sort = {
                    date: $('input[name="sortDate"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchActiveGuards();
                $('#sortModal').modal('hide');
            });

            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortDate"]').prop('checked', false);
                $('input[name="sortName"]').prop('checked', false);
                fetchActiveGuards();
            });

            // Initial fetch to populate the table
            fetchActiveGuards();
            setInterval(fetchActiveGuards, 5000);

            // Using jQuery with onInput event to achieve live search
            $('#search').on('input', function() {
                fetchActiveGuards(); // Fetch based on selected station and search input
            });


            $('#goToInactive').on('click', function() {
                window.location.href = '../Inactive/main_inactive.php';
            });

            $('#backbtn').on('click', function() {
                window.location.href = '../../dashboard_home.php';
            });

            // Toggle password visibility
            $(document).on('click', '.toggle-password', function() {
                let input = $(this).siblings('input');
                let icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
                }
            });


            // FOR ADD CO ADMIN FUNCTIONS   

            $('#addGuardModalButton').click(function() {

                // Removing feedbacks initially
                $('#addName-feedback').text('').removeClass('invalid-feedback');
                $('#addUsername-feedback').text('').removeClass('invalid-feedback');
                $('#addEmail-feedback').text('').removeClass('invalid-feedback');
                $('#addPassword-feedback1').text('').removeClass('invalid-feedback');
                $('#addPassword-feedback2').text('').removeClass('invalid-feedback');
                $('#guard_name').removeClass('is-invalid');
                $('#username').removeClass('is-invalid');
                $('#email').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                $('#confirm_password').removeClass('is-invalid');

                // Clearing initial value of textboxes
                $('#guard_name').val('');
                $('#username').val('');
                $('#email').val('');
                $('#password').val('');
                $('#confirm_password').val('');

                // Set input types to password and reset the eye icon
                $('#password').attr('type', 'password');
                $('#confirm_password').attr('type', 'password');
                $('.toggle-password i').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');

            });

            // Handle the Save button click (submit the form)
            $('#submitAddGuardButton').click(function(e) {
                e.preventDefault();

                // Check feedback messages
                validateName();
                validateUsername();
                validateEmail();
                validatePassword();
                validateConfirmPassword();

                if (!checkAddGuardButton()) {
                    return;
                }
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you want to ADD the account?',
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
                        $('#addGuardForm').submit();
                    }
                });


            });

            // Handle form submission
            $('#addGuardForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                $.ajax({
                    type: 'POST',
                    url: 'add_guard.php',
                    data: $(this).serialize(),
                    dataType: 'json', // Expect a JSON response from the server
                    success: function(response) {
                        // Handle alert messages
                        if (response.success) {
                            Swal.fire({
                                position: 'top',
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });

                            fetchActiveGuards();

                        } else {
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function(xhr, status, error) {

                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: `An error occurred while adding the guard: ${error}`,
                            icon: 'error',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                    },
                    complete: function() {
                        $('#addGuardModal').modal('hide'); // Optionally hide the modal
                        $('#addGuardForm')[0].reset(); // Reset the form
                    }
                });

            });


            // FOR EDIT GUARD FUNCTIONS

            // Event listener for the Edit button
            $('#editButton').click(function() {
                // Enable inputs
                $('#modalGuardName, #modalStationName, #modalUsername, #modalEmail').prop('disabled', false);

                // Show Save and Discard buttons, hide Edit and Change Password button
                $('.btn-cont-1').hide(); // edit and change pass
                $('.btn-cont-2').show(); // save and discard
            });

            // Event listener for the discard button
            // Event listener for the discard button
            $('#discardButton').click(function() {
                // Get current values from the modal inputs
                const currentGuardName = $('#modalGuardName').val().trim();
                const currentStationId = $('#modalStationName').val();
                const currentUsername = $('#modalUsername').val().trim();
                const currentEmail = $('#modalEmail').val().trim();

                // Check if there are any changes
                const hasChanges =
                    currentGuardName !== current.guard_name ||
                    currentStationId !== current.station_id ||
                    currentUsername !== current.username ||
                    currentEmail !== current.email;

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
                            // Revert inputs to original values
                            resetGuardModalToOriginal();
                        }
                    });
                } else {
                    // No changes, directly reset without confirmation
                    resetGuardModalToOriginal();
                }
            });

            // Function to reset modal values to the original data
            function resetGuardModalToOriginal() {
                // Reset inputs to the original data
                $('#modalGuardName').val(current.guard_name);
                $('#modalStationName').val(current.station_id);
                $('#modalUsername').val(current.username);
                $('#modalEmail').val(current.email);

                // Clear validation feedback
                $('#editName-feedback, #editUsername-feedback, #editEmail-feedback').text('').removeClass('invalid-feedback');
                $('#modalGuardName, #modalUsername, #modalEmail').removeClass('is-invalid');

                // Disable inputs
                $('#modalGuardName, #modalStationName, #modalUsername, #modalEmail').prop('disabled', true);

                // Hide Save and Discard buttons, show Edit button
                $('.btn-cont-1').show(); // edit and change pass
                $('.btn-cont-2').hide(); // save and discard
            }


            // Event listener for the closing view guards modal
            $('#edit_closeButton').click(function() {

                // Disable inputs
                $('#modalGuardName, #modalStationName, #modalUsername, #modalEmail').prop('disabled', true);

                // Reset inputs to the original data
                $('#modalGuardName').val(current.guard_name);
                $('#modalStationName').val(current.station_id);
                $('#modalUsername').val(current.username);
                $('#modalEmail').val(current.email);

                // Hide Save and Discard buttons, show Edit button
                $('.btn-cont-1').show(); // edit and change pass
                $('.btn-cont-2').hide(); // save and discard
            });

            // Handle the Save button click (submit the form)
            $('#saveButton').click(function() {

                // Check feedback messages
                validateEditName();
                validateEditUsername();
                validateEditEmail();

                if (checkEditGuardButton()) {

                    // Check for changes
                    const currentData = {
                        guard_name: $('#modalGuardName').val().trim(),
                        username: $('#modalUsername').val().trim(),
                        email: $('#modalEmail').val().trim(),
                        station_id: $('#modalStationName').val(),
                    };

                    const isChanged =
                        currentData.guard_name !== current.guard_name ||
                        currentData.username !== current.username ||
                        currentData.email !== current.email ||
                        currentData.station_id !== current.station_id;

                    if (!isChanged) {
                        Swal.fire({
                            title: 'No Changes Detected',
                            text: "You have not made any changes to the details.",
                            icon: 'info',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                        return;
                    }

                    // Confirmation dialog before saving
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to SAVE the changes?',
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
                            $('#editGuardForm').submit(); // Trigger the form submission

                            // Disable inputs
                            $('#modalGuardName, #modalStationName, #modalUsername, #modalEmail').prop('disabled', true);

                            // Hide buttons
                            $('.btn-cont-1').show(); // edit and change pass
                            $('.btn-cont-2').hide(); // save and discard
                        }
                    });
                }
            });


            $('#editGuardForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                const guardData = {
                    guard_id: $('#guard_id').val(),
                    guard_name: $('#modalGuardName').val(),
                    username: $('#modalUsername').val(),
                    email: $('#modalEmail').val(),
                    station: $('#modalStationName').val()
                };

                $.ajax({
                    url: 'update_changes.php',
                    type: 'POST',
                    data: guardData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {

                            Swal.fire({
                                position: 'top',
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });

                            fetchActiveGuards();

                            // Update the current object with new values
                            current.guard_name = guardData.guard_name;
                            current.username = guardData.username;
                            current.email = guardData.email;
                            current.station_id = guardData.station;

                            // Reset the modal data
                            populateDetails(current);

                        } else {
                            // Show error message if unsuccessful
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error); // Log the error for debugging
                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }
                });
            });


            // FOR CHANGE PASSWORD
            $('#changePasswordButton').on('click', function() {
                $('#guardDetailsModal').modal('hide');
                $('#passwordChangeModal').modal('show');


                // Clear the initial value of textboxes and populate the needed info for activity logs
                $('#new_password').val('');
                $('#confirm_new_password').val('');
                $('#password_guard_id').val(current.guard_id);
                $('#password_guard_name').val(current.guard_name);

                // Set input types to password and reset the eye icon
                $('#new_password').attr('type', 'password');
                $('#confirm_new_password').attr('type', 'password');
                $('.toggle-password i').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');

                // Removing initial feedbacks
                $('#changePassword-feedback1').text('').removeClass('invalid-feedback');
                $('#changePassword-feedback2').text('').removeClass('invalid-feedback');
                $('#new_password').removeClass('is-invalid');
                $('#confirm_new_password').removeClass('is-invalid');

            });

            // clicking go back
            $('#goBackButton').on('click', function() {
                $('#passwordChangeModal').modal('hide');

                // get back to the details of current guard
                openDetailsModal(current);
            });

            // Handle the Save button click (submit the form)
            $('#submitPasswordButton').click(function() {

                // Check feedback message
                validateNewPassword();
                validateConfirmNewPassword();

                if (checkChangePasswordButton()) {
                    $('#passwordChangeForm').submit(); // Trigger the form submission
                }

            });

            $('#passwordChangeForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                const passwordGuardId = $('#password_guard_id').val();
                const newPassword = $('#new_password').val();
                const confirmNewPassword = $('#confirm_new_password').val();
                const passwordGuardName = $('#password_guard_name').val();
                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to change the password of Account no: ${passwordGuardId}?`,
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
                            url: 'update_password.php',
                            type: 'POST',
                            data: {
                                guard_id: passwordGuardId,
                                new_password: newPassword,
                                confirm_new_password: confirmNewPassword,
                                password_guard_name: passwordGuardName
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {
                                    // Handle success message
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false
                                    });

                                    fetchActiveGuards();

                                    // Hide the password modal and open the current guard details modal
                                    $('#passwordChangeModal').modal('hide');
                                    openDetailsModal(current);

                                } else {
                                    // Show error message if unsuccessful
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                        timer: 3000,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error); // Log the error for debugging
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'An error occurred. Please try again.',
                                    icon: 'error',
                                    timer: 3000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        });
                    }
                });
            });




            // FEEDBACK MESSAGE FUNCTIONS FOR ADD CO-ADMIN

            // Add on input event handlers for immediate feedback
            $('#guard_name').on('input keyup', validateName);
            $('#username').on('input keyup', validateUsername);
            $('#email').on('input keyup', validateEmail);
            $('#password').on('input keyup', validatePassword);
            $('#confirm_password').on('input keyup', validateConfirmPassword);

            function validateName() {
                const name = $('#guard_name').val().trim();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;

                // Clear previous feedback
                $('#addName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';

                if (name === "") {
                    feedbackMessage = 'Name cannot be empty';
                } else if (!nameRegex.test(name)) { // Check for invalid chars
                    feedbackMessage = 'Name can only contain letters, dots, hyphens, apostrophes, and spaces.';
                }

                if (feedbackMessage) {
                    $('#addName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#guard_name').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#guard_name').removeClass('is-invalid');
                }

            }

            function validateUsername() {
                const username = $('#username').val().trim();

                // Clear previous feedback
                $('#addUsername-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';

                // Validate username input
                if (username === "") {
                    feedbackMessage = 'Username cannot be empty.';
                } else if (username.length < 8) {
                    feedbackMessage = 'Username must be at least 8 characters long.';
                } else if (username.includes(' ')) {
                    feedbackMessage = 'Username cannot contain spaces.';
                }

                // Display feedback message if there are errors
                if (feedbackMessage) {
                    $('#addUsername-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#username').addClass('is-invalid'); // Mark input as invalid
                    return;
                } else {
                    $('#username').removeClass('is-invalid'); // Remove invalid class
                }


                // AJAX request to check for existing username
                $.post('check_uniqueness.php', {
                    value: username,
                    type: 'username'
                }, function(data) {
                    const response = JSON.parse(data);
                    if (response.exists) {
                        feedbackMessage = 'This username is already taken.';

                        // Display feedback message if username is taken
                        $('#addUsername-feedback').text(feedbackMessage).addClass('invalid-feedback');
                        $('#username').addClass('is-invalid'); // Mark input as invalid
                    }
                });
            }

            function validateEmail() {
                const email = $('#email').val().trim();
                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

                // Clear previous feedback
                $('#addEmail-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';

                // Validate email input
                if (email === "") {
                    feedbackMessage = 'Email cannot be empty.';
                } else if (!emailPattern.test(email)) {
                    feedbackMessage = 'Invalid email format.';
                }

                // Display feedback message if there are errors
                if (feedbackMessage) {
                    $('#addEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#email').addClass('is-invalid'); // Mark input as invalid
                    return;
                } else {
                    $('#email').removeClass('is-invalid'); // Remove invalid class
                }

                // AJAX request to check for existing email
                $.post('check_uniqueness.php', {
                    value: email,
                    type: 'email'
                }, function(data) {
                    const response = JSON.parse(data);
                    if (response.exists) {
                        feedbackMessage = 'This email is already taken.';

                        // Display feedback message if email is taken
                        $('#addEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                        $('#email').addClass('is-invalid'); // Mark input as invalid
                    }
                });
            }

            function validatePassword() {
                const password = $('#password').val();
                let feedbackMessage = '';

                // Clear previous feedback message
                $('#addPassword-feedback1').text('').removeClass('invalid-feedback');

                if (password === "") {
                    feedbackMessage = 'New password cannot be empty.';
                } else if (password.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                }

                // Display feedback message if there are errors
                if (feedbackMessage) {
                    $('#addPassword-feedback1').text(feedbackMessage).addClass('invalid-feedback');
                    $('#password').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#password').removeClass('is-invalid');
                }

            }

            function validateConfirmPassword() {
                const password = $('#password').val();
                const confirmPassword = $('#confirm_password').val();
                let feedbackMessage = '';

                // Clear previous feedback message
                $('#addPassword-feedback2').text('').removeClass('invalid-feedback');

                if (confirmPassword === "") {
                    feedbackMessage = 'Please confirm your new password.';
                } else if (confirmPassword.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                } else if (password !== confirmPassword) {
                    feedbackMessage = 'Passwords do not match.';
                }

                // Display feedback message if there are errors
                if (feedbackMessage) {
                    $('#addPassword-feedback2').text(feedbackMessage).addClass('invalid-feedback');
                    $('#confirm_password').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#confirm_password').removeClass('is-invalid');
                }

            }

            function checkAddGuardButton() {
                let isNameValid = $('#guard_name').val().trim() !== "" && !$('#guard_name').hasClass('is-invalid');
                let isUsernameValid = $('#username').val().trim() !== "" && !$('#username').hasClass('is-invalid');
                let isEmailValid = $('#email').val().trim() !== "" && !$('#email').hasClass('is-invalid');
                let isPasswordValid = $('#password').val().trim() !== "" && !$('#password').hasClass('is-invalid');
                let isConfirmPasswordValid = $('#confirm_password').val().trim() !== "" && !$('#confirm_password').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isNameValid || !isUsernameValid || !isEmailValid || !isPasswordValid || !isConfirmPasswordValid) {
                    return false;
                } else {
                    return true;
                }
            }



            // FEEDBACK MESSAGE FUNCTIONS FOR EDIT GUARD DETAILS

            $('#modalGuardName').on('input keyup', validateEditName);
            $('#modalUsername').on('input keyup', validateEditUsername);
            $('#modalEmail').on('input keyup', validateEditEmail);

            function validateEditName() {
                const name = $('#modalGuardName').val().trim();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;

                $('#editName-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (name === "") {
                    feedbackMessage = 'Name cannot be empty';
                } else if (!nameRegex.test(name)) { // Check for invalid chars
                    feedbackMessage = 'Name can only contain letters, dots, hyphens, apostrophes, and spaces.';
                }


                if (feedbackMessage) {
                    $('#editName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modalGuardName').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#modalGuardName').removeClass('is-invalid');
                }
            }

            function validateEditUsername() {
                const username = $('#modalUsername').val().trim();
                const id = $('#guard_id').val();
                $('#editUsername-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (username === "") {
                    feedbackMessage = 'Username cannot be empty.';
                } else if (username.length < 8) {
                    feedbackMessage = 'Username must be at least 8 characters long.';
                } else if (username.includes(' ')) {
                    feedbackMessage = 'Username cannot contain spaces.';
                }

                if (feedbackMessage) {
                    $('#editUsername-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modalUsername').addClass('is-invalid'); // Mark input as invalid
                    return;
                } else {
                    $('#modalUsername').removeClass('is-invalid');
                }

                // AJAX request to check for existing username
                $.post('check_edit_uniqueness.php', {
                    value: username,
                    type: 'username',
                    guard_id: id
                }, function(data) {
                    const response = JSON.parse(data);
                    if (response.exists) {
                        feedbackMessage = 'This username is already taken.';
                        $('#editUsername-feedback').text(feedbackMessage).addClass('invalid-feedback');
                        $('#modalUsername').addClass('is-invalid'); // Mark input as invalid
                    }
                });
            }

            function validateEditEmail() {
                const email = $('#modalEmail').val().trim();
                const id = $('#guard_id').val();
                const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
                $('#editEmail-feedback').text('').removeClass('invalid-feedback');

                let feedbackMessage = '';
                if (email === "") {
                    feedbackMessage = 'Email cannot be empty.';
                } else if (!emailPattern.test(email)) {
                    feedbackMessage = 'Invalid email format.';
                }

                if (feedbackMessage) {
                    $('#editEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#modalEmail').addClass('is-invalid'); // Mark input as invalid
                    return;
                } else {
                    $('#modalEmail').removeClass('is-invalid');
                }

                // AJAX request to check for existing email
                $.post('check_edit_uniqueness.php', {
                    value: email,
                    type: 'email',
                    guard_id: id
                }, function(data) {
                    const response = JSON.parse(data);
                    if (response.exists) {
                        feedbackMessage = 'This email is already taken.';
                        $('#editEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                        $('#modalEmail').addClass('is-invalid'); // Mark input as invalid
                    }
                });
            }

            function checkEditGuardButton() {
                let isNameValid = $('#modalGuardName').val().trim() !== "" && !$('#modalGuardName').hasClass('is-invalid');
                let isUsernameValid = $('#modalUsername').val().trim() !== "" && !$('#modalUsername').hasClass('is-invalid');
                let isEmailValid = $('#modalEmail').val().trim() !== "" && !$('#modalEmail').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isNameValid || !isUsernameValid || !isEmailValid) {
                    return false;
                } else {
                    return true;
                }
            }



            // FEEDBACK MESSAGE FUNCTIONS FOR CHANGE PASSWORD

            $('#new_password').on('input keyup', validateNewPassword);
            $('#confirm_new_password').on('input keyup', validateConfirmNewPassword);

            function validateNewPassword() {
                const password = $('#new_password').val();
                let feedbackMessage = '';

                $('#changePassword-feedback1').text('').removeClass('invalid-feedback');

                if (password === "") {
                    feedbackMessage = 'New password cannot be empty.';
                } else if (password.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                }

                if (feedbackMessage) {
                    $('#changePassword-feedback1').text(feedbackMessage).addClass('invalid-feedback');
                    $('#new_password').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#new_password').removeClass('is-invalid');
                }
            }

            function validateConfirmNewPassword() {
                const password = $('#new_password').val();
                const confirmPassword = $('#confirm_new_password').val();
                let feedbackMessage = '';

                $('#changePassword-feedback2').text('').removeClass('invalid-feedback');

                if (confirmPassword === "") {
                    feedbackMessage = 'Please confirm your new password.';
                } else if (confirmPassword.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                } else if (password !== confirmPassword) {
                    feedbackMessage = 'Passwords do not match.';
                }

                if (feedbackMessage) {
                    $('#changePassword-feedback2').text(feedbackMessage).addClass('invalid-feedback');
                    $('#confirm_new_password').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#confirm_new_password').removeClass('is-invalid');
                }
            }

            function checkChangePasswordButton() {
                let isNewPasswordValid = $('#new_password').val().trim() !== "" && !$('#new_password').hasClass('is-invalid');
                let isConfirmNewPasswordValid = $('#confirm_new_password').val().trim() !== "" && !$('#confirm_new_password').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isNewPasswordValid || !isConfirmNewPasswordValid) {
                    return false;
                } else {
                    return true;
                }
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>