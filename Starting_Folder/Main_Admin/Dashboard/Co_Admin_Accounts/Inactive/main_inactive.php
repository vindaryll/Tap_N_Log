<?php

// FROND-END for MAIN PAGE of Co-admin Active Account
session_start();

require_once 'C:\xampp\htdocs\TAPNLOG\Database\dbcon.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Inactive | Main Admin</title>
</head>

<body>

    <!-- Nav Bar -->
    <?php require_once 'C:\xampp\htdocs\TAPNLOG\Starting_Folder\Main_Admin\Dashboard\navbar.php'; ?>

    <!-- START OF CONTAINER -->
    <div class="d-flex justify-content-center">

        <div class="container row col-sm-12">

            <div class="container col-sm-12 mb-3">
                <button type="button" class="btn btn-primary" id="backbtn">Go back to Active Accounts</button>
            </div>

            <div class="container col-sm-12">
                <h2>Inactive Co-Admin Accounts</h2>
                <input type="text" id="search" class="form-control" placeholder="Search by guard name...">
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guard Name</th>
                            <th class="text-center d-flex justify-content-center">
                                <!-- Dropdown for station selection inside the table header -->
                                <select id="stationSelect" class="form-select form-select-sm">
                                    <option value="">ALL STATIONS</option>
                                    <?php
                                    // Fetching stations to populate the dropdown
                                    $stationsSql = "SELECT station_id, station_name FROM stations";
                                    $stationsResult = $conn->query($stationsSql);
                                    if ($stationsResult->num_rows > 0) {
                                        while ($station = $stationsResult->fetch_assoc()) {
                                            echo "<option value='" . $station['station_id'] . "'>" . $station['station_name'] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="guardTableBody">
                        <!-- Results will be inserted here -->

                    </tbody>
                </table>
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
        function fetchInactiveGuards() {
            var stationId = $('#stationSelect').val();
            var searchQuery = $('#search').val();

            $.ajax({
                url: 'fetch_inactive_guards.php',
                type: 'GET',
                data: {
                    station_id: stationId,
                    search: searchQuery
                }, // Sending station ID and search query
                success: function(data) {
                    $('#guardTableBody').html(data); // Populate the table with the fetched data
                }
            });
        }

        $(document).ready(function() {

            // Initial fetch to populate the table
            fetchInactiveGuards();

            // Event listener for station filter
            $('#stationSelect').change(function() {
                fetchInactiveGuards(); // Fetch based on selected station and current search input
            });

            // Using jQuery with onInput event to achieve live search
            $('#search').on('input', function() {
                fetchInactiveGuards(); // Fetch based on selected station and search input
            });

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
            if (confirm("Do you want to reactivate guard no: " + guardId + "?")) {
                $.ajax({
                    url: 'reactivate_guard.php',
                    type: 'POST',
                    data: {
                        guard_id: guardId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);

                            fetchInactiveGuards(); // Reload the table to show updated data
                        } else {
                            alert(response.message); // Show error message
                        }
                    },
                    error: function(xhr, status, error) {
                        alert("An unexpected error occurred: " + error);
                    }
                });
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>