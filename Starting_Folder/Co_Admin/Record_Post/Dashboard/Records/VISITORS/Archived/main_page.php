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

    <!-- Real time session checker -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Co_Admin\status_script.php'; ?>

    <title>Archived Records - Visitors | Record Post</title>

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
        
        table.table tbody tr:hover {
            background-color: #5abed6;
        }

        .table-pre {
            white-space: pre;
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
                    <h2 class="text-center w-100">VISITORS ARCHIVED RECORDS</h2>

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
                    <h5 class="modal-title" id="viewRecordLabel">VISITOR DETAILS</h5>
                    <button type="button" class="btn-close" id="modal_1_closeBtn"></button>
                </div>
                <div class="modal-body mb-3">
                    <form id="addRecordForm">
                        <div class="mb-3">
                            <label for="modal_1_firstName" class="form-label"><strong>FIRST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_1_firstName" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_lastName" class="form-label"><strong>LAST NAME</strong></label>
                            <input type="text" class="form-control" id="modal_1_lastName" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_phoneNumber" class="form-label"><strong>PHONE NUMBER</strong></label>
                            <input type="text" class="form-control" id="modal_1_phoneNumber" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_visitorPass" class="form-label"><strong>VISITOR PASS (Optional)</strong></label>
                            <input type="text" class="form-control" id="modal_1_visitorPass" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="modal_1_purpose" class="form-label"><strong>PURPOSE</strong></label>
                            <textarea class="form-control" id="modal_1_purpose" rows="3" disabled></textarea>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>




    <script>
        $(document).ready(function() {

            $('#modal_1_closeBtn').on('click', function() {
                $('#viewRecordModal').modal('hide');
            });

            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

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
            setInterval(fetchRecords, 5000);

            // VIEW AND EDIT DETAILS
            $(document).on('click', '.view-details-btn', function() {

                current = {
                    first_name: $(this).data('first-name'),
                    last_name: $(this).data('last-name'),
                    phone_number: $(this).data('phone-num'),
                    purpose: $(this).data('purpose'),
                    pass: $(this).data('visitor-pass')
                }

                console.log(current);

                // OPEN THE MODAL WITH POPULATED VALUES AND disabled inputs
                $('#modal_1_firstName').val(current.first_name).removeClass('is-invalid');
                $('#modal_1_lastName').val(current.last_name).removeClass('is-invalid');
                $('#modal_1_phoneNumber').val(current.phone_number).removeClass('is-invalid');
                $('#modal_1_visitorPass').val(current.pass);
                $('#modal_1_purpose').val(current.purpose).removeClass('is-invalid');

                // Show the modal
                $('#viewRecordModal').modal('show');
            });



        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>