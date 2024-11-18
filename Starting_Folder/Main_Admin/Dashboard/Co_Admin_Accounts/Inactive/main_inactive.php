<?php

// FROND-END for MAIN PAGE of Co-admin Active Account
session_start();

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

    <title>Inactive Co-admin Accounts | Main Admin</title>

    <style>
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        .table thead th {
            position: sticky;
            top: 0;
            background-color: #343a40;
            color: white;
        }
    </style>

</head>

<body>

    <!-- Nav Bar -->
    <?php require_once $_SESSION['directory'] . '\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container row col-sm-12">

            <div class="container col-sm-12 text-center">
                <h2>Inactive Co-Admin Accounts</h2>
                <!-- Search, Filter, and Sort Buttons -->
                <input type="text" id="search" class="form-control mb-3" placeholder="Search by guard name or ID">
                <div class="d-flex justify-content-start mb-3">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
                    <button class="btn btn-secondary ms-2" data-bs-toggle="modal" data-bs-target="#sortModal">Sort</button>
                </div>

                <!-- Table -->
                <div class="table-responsive" style="max-height: 300px;">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>DATE</th>
                                <th>GUARD NAME</th>
                                <th>STATION</th>
                                <th>ACTION</th>
                            </tr>
                        </thead>
                        <tbody id="guardTableBody">
                            <!-- Results will be inserted here -->
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <button type="button" class="btn btn-primary" id="backbtn">Go back to Active Accounts</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Guards</h5>
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
                            <label for="filterStationSelect" class="form-label">Station</label>
                            <select id="filterStationSelect" class="form-select">
                                <option value="">All Stations</option>
                                <?php
                                $stationsSql = "SELECT station_id, station_name FROM stations";
                                $stationsResult = $conn->query($stationsSql);
                                while ($station = $stationsResult->fetch_assoc()) {
                                    echo "<option value='" . $station['station_id'] . "'>" . htmlspecialchars($station['station_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="resetFilters" class="btn btn-danger">Reset</button>
                    <button type="button" id="applyFilters" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sort Modal -->
    <div class="modal fade" id="sortModal" tabindex="-1" aria-labelledby="sortModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sortModalLabel">Sort Guards</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <label class="form-label">Sort by Date:</label>
                        <div>
                            <input type="radio" name="sortDate" value="asc"> Ascending<br>
                            <input type="radio" name="sortDate" value="desc"> Descending
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Sort by Name:</label>
                        <div>
                            <input type="radio" name="sortName" value="asc"> A-Z<br>
                            <input type="radio" name="sortName" value="desc"> Z-A
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="resetSort" class="btn btn-danger">Reset</button>
                    <button type="button" id="applySort" class="btn btn-primary">Apply</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal for Guard Details -->
    <div class="modal fade" id="guardDetailsModal" tabindex="-1" aria-labelledby="guardDetailsLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guardDetailsLabel">Guard Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Guard Name:</strong> <span id="modalGuardName"></span></p>
                    <p><strong>Station Name:</strong> <span id="modalStationName"></span></p>
                    <p><strong>Username:</strong> <span id="modalUsername"></span></p>
                    <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                </div>
            </div>
        </div>
    </div>


    <script>

        let filters = {};
        let sort = {};
        let search = '';

        function fetchInactiveGuards() {
            $.ajax({
                url: 'fetch_inactive_guards.php',
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


            $('#applyFilters').on('click', function() {
                filters = {
                    fromDate: $('#from_dateInput').val(),
                    toDate: $('#to_dateInput').val(),
                    station: $('#filterStationSelect').val(),
                };
                fetchInactiveGuards();
                $('#filterModal').modal('hide');
            });

            $('#resetFilters').on('click', function() {
                filters = {};
                $('#filterForm')[0].reset();
                fetchInactiveGuards();
            });

            $('#applySort').on('click', function() {
                sort = {
                    date: $('input[name="sortDate"]:checked').val(),
                    name: $('input[name="sortName"]:checked').val(),
                };
                fetchInactiveGuards();
                $('#sortModal').modal('hide');
            });

            $('#resetSort').on('click', function() {
                sort = {};
                $('input[name="sortDate"]').prop('checked', false);
                $('input[name="sortName"]').prop('checked', false);
                fetchInactiveGuards();
            });

            $('#search').on('input', function() {
                search = $(this).val().trim();
                fetchInactiveGuards();
            });

            // Initial fetch
            fetchInactiveGuards();

            // Back button
            $('#backbtn').on('click', function() {
                window.location.href = '../Active/main_active.php';
            });

        });

        // Function to open modal and populate data
        function openDetailsModal(guard) {
            $('#modalGuardName').text(guard.guard_name);
            $('#modalStationName').text(guard.station_name);
            $('#modalUsername').text(guard.username);
            $('#modalEmail').text(guard.email);
            $('#modalStatus').text(guard.status);
            $('#guardDetailsModal').modal('show');
        }

        // Function to reactivate guard
        function reactivateGuard(guardId) {
            showConfirmation(
                "Do you want to reactivate guard no: " + guardId + "?",
                function() { // Callback to execute on confirmation
                    $.ajax({
                        url: 'reactivate_guard.php',
                        type: 'POST',
                        data: {
                            guard_id: guardId
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {

                                showAlert(response.message, "success");

                                fetchInactiveGuards(); // Reload the table to show updated data
                            } else {
                                showAlert(response.message, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            showAlert("An unexpected error occurred: " + error, "error");
                        }
                    });
                }
            );
        }

        function showAlert(message, type = "error") {
            Swal.fire({
                position: "top",
                title: type === "success" ? 'Success!' : 'Error!',
                text: message,
                icon: type,
                timer: 1500,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }

        function showConfirmation(message, callback, _icon = 'question', confirmText = 'YES', cancelText = 'NO') {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: _icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    callback(); // Execute the callback function if confirmed
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>