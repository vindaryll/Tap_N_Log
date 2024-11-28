<?php

session_start();

// Include database connection
require_once $_SESSION['directory'] . '\Database\dbcon.php';

// If they haven't logged in yet
if (!isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

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

    <title>Records - Employees | Main Admin</title>

    <style>
        .input-group {
            position: relative;
        }

        .toggle-password {
            cursor: pointer;
        }

        /* FOR MODAL VIEW MODAL */
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

        table.table tbody tr:hover {
            background-color: #5abed6;
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
                    <h2 class="text-center w-100">EMPLOYEES RECORDS</h2>

                    <!-- Textbox for search -->
                    <input type="text" id="searchTextbox" class="form-control mb-3" placeholder="Search by name or Logbook ID">

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
                        <div class="mb-3">
                            <label for="rfidFilter" class="form-label">RFID Status</label>
                            <select id="rfidFilter" class="form-select">
                                <option value="">BOTH</option>
                                <option value="with_rfid">WITH RFID</option>
                                <option value="without_rfid">WITHOUT RFID</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" class="form-select">
                                <option value="">ALL</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="INACTIVE">INACTIVE</option>
                            </select>
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
                        <label class="form-label">SORT BY NAME</label>
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

    <!-- Details Modal -->
    <div class="modal fade" id="ProfileDetailsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ProfileDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><strong>EMPLOYEE DETAILS</strong></h5>
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
                            <form id="profileForm" class=" mt-2 mt-lg-4">

                                <!-- DATE APPROVED -->
                                <div class="mb-3">
                                    <p><strong>Date Approved: </strong><span id="details_date"> <!-- for populate --> </span></p>
                                </div>

                                <!-- FULL NAME -->
                                <div class="mb-3">
                                    <p><strong>Name: </strong><span id="details_name"> <!-- for populate --> </span></p>
                                </div>

                                <!-- TYPE OF PROFILE: EMPLOYEE -->
                                <div class="mb-3">
                                    <p><strong>Profile Type: </strong><span id="details_type"> <!-- for populate --> </span></p>
                                </div>

                                <!-- STATUS -->
                                <div class="mb-3">
                                    <p><strong>Status: </strong><span id="details_status"> <!-- for populate --> </span></p>
                                </div>

                                <!-- RFID NUMBER -->
                                <div class="mb-3">
                                    <p><strong>RFID Number: </strong><span id="details_rfid"> <!-- for populate --> </span></p>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {

            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            $('#goToArchive').on('click', function() {
                window.location.href = 'Archived/main_page.php';
            });

            $('#modal_2_closeBtn').on('click', function() {
                $('#viewRecordModal').modal('hide');
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
                    to_date: $('#to_dateInput').val(),
                    rfid_filter: $('#rfidFilter').val(), // Correct key for backend
                    status: $('#status').val(),
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

            // VIEW AND EDIT DETAILS
            $(document).on('click', '.view-details-btn', function() {
                const img = $(this).data('bs-img');
                const date = $(this).data('bs-date-approved');
                const name = $(this).data('bs-name');
                const profile_type = "EMPLOYEE";
                const status = $(this).data('bs-status');
                const rfid = $(this).data('bs-rfid');

                $('#modal_1_profileImg').attr('src', img);
                $('#details_date').text(date);
                $('#details_name').text(name);
                $('#details_type').text(profile_type);
                $('#details_status').text(status);
                $('#details_rfid').text(rfid);

                // Show the modal
                $('#ProfileDetailsModal').modal('show');
            });


        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>