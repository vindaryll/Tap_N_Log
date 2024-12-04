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

    <title>Main Admin - Activity logs | Main Admin</title>

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
                    <h2 class="page-title text-center w-100">MAIN ADMINISTRATOR ACTIVITY LOG</h2>

                    <!-- Textbox for search -->
                    <input type="text" id="searchTextbox" class="form-control mb-3" placeholder="Search by details or activity ID">

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
                                    <th>ACTIVITY ID</th>
                                    <th>TIMESTAMP</th>
                                    <th>DETAILS</th>
                                    <th>CATEGORY</th>
                                    <th>ADMIN ID - USERNAME</th>
                                </tr>
                            </thead>
                            <tbody id="activityTableBody">
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
                    <h5 class="modal-title" id="filterModalLabel"><strong>FILTER ACTIVITY</strong></h5>
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
                            <label for="section" class="form-label"><strong>SECTION</strong></label>
                            <select id="section" class="form-select">
                                <option value="">ALL</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label"><strong>CATEGORY</strong></label>
                            <select id="category" class="form-select">
                                <option value="">ALL</option>
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
                    <h5 class="modal-title" id="sortModalLabel"><strong>SORT ACTIVITY</strong></h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label"><strong>SORT BY TIMESTAMP</strong></label>
                        <div>
                            <input type="radio" name="sortTimestamp" value="asc"> Ascending<br>
                            <input type="radio" name="sortTimestamp" value="desc"> Descending
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

    <script>
        $(document).ready(function() {
            $('#backbtn').on('click', function() {
                window.location.href = '../main_page.php';
            });

            // Initialize filters, sort and search
            let filters = {};
            let sort = {};
            let search = '';

            // Get today's date in local time
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const todayFormatted = `${year}-${month}-${day}`;

            // Set initial value and max attribute for date inputs
            $('#from_dateInput, #to_dateInput').attr('max', todayFormatted);

            // Update categories based on selected section
            function updateCategories() {
                const selectedSection = $('#section').val();
                const categorySelect = $('#category');
                categorySelect.empty();
                categorySelect.append('<option value="">ALL</option>');

                if (selectedSection && window.categoriesData && window.categoriesData[selectedSection]) {
                    window.categoriesData[selectedSection].forEach(category => {
                        categorySelect.append(`<option value="${category}">${category}</option>`);
                    });
                }
            }

            // Populate sections and categories
            function populateSectionsCategories() {
                $.ajax({
                    url: 'fetch_sections_categories.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Populate sections
                        const sectionSelect = $('#section');
                        sectionSelect.empty();
                        sectionSelect.append('<option value="">ALL</option>');
                        data.sections.forEach(section => {
                            sectionSelect.append(`<option value="${section.value}">${section.label}</option>`);
                        });

                        // Store categories data
                        window.categoriesData = data.categories;
                        updateCategories();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching sections and categories:', error);
                    }
                });
            }

            // Event handler for section change
            $('#section').on('change', updateCategories);

            // Initial population
            populateSectionsCategories();

            // VALIDATION OF DATE INPUTS
            function validateDateInput1() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                if (fromDate && toDate) {
                    const fromDateValue = new Date(fromDate);
                    const toDateValue = new Date(toDate);
                    const todayDate = new Date(todayFormatted);

                    if (fromDateValue > todayDate) {
                        $('#from_dateInput').val(todayFormatted);
                    }

                    if (fromDateValue > toDateValue) {
                        $('#to_dateInput').val('');
                    }
                }
            }

            function validateDateInput2() {
                const fromDate = $('#from_dateInput').val();
                const toDate = $('#to_dateInput').val();

                if (fromDate && toDate) {
                    const fromDateValue = new Date(fromDate);
                    const toDateValue = new Date(toDate);
                    const todayDate = new Date(todayFormatted);

                    if (toDateValue > todayDate) {
                        $('#to_dateInput').val(todayFormatted);
                    }

                    if (toDateValue < fromDateValue) {
                        $('#to_dateInput').val('');
                    }
                }
            }

            $('#from_dateInput').on('input change', validateDateInput1);
            $('#to_dateInput').on('input change', validateDateInput2);

            // Event handlers
            $('#searchTextbox').on('input', function() {
                search = $(this).val();
                fetchActivity();
            });

            $('#applyFilters').on('click', function() {
                filters = {
                    from_date: $('#from_dateInput').val(),
                    to_date: $('#to_dateInput').val(),
                    section: $('#section').val(),
                    category: $('#category').val()
                };
                fetchActivity();
                $('#filterModal').modal('hide');
            });

            $('#resetFilters').on('click', function() {
                // Reset filter inputs
                $('#from_dateInput').val('');
                $('#to_dateInput').val('');
                $('#section').val('');
                $('#category').val('');

                // Clear filters object
                filters = {};
                fetchActivity();
            });

            $('#applySort').on('click', function() {
                sort = {
                    timestamp: $('input[name="sortTimestamp"]:checked').val()
                };
                fetchActivity();
                $('#sortModal').modal('hide');
            });

            $('#resetSort').on('click', function() {
                // Reset sort inputs
                $('input[name="sortTimestamp"]').prop('checked', false);

                // Clear sort object
                sort = {};
                fetchActivity();
            });

            // Function to fetch activity data
            function fetchActivity() {
                $.ajax({
                    url: 'fetch_activity.php',
                    type: 'POST',
                    data: {
                        search: search,
                        filters: filters,
                        sort: sort
                    },
                    success: function(response) {
                        $('#activityTableBody').html(response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching activity:', error);
                    }
                });
            }

            // Initial fetch
            fetchActivity();
            setInterval(fetchActivity, 5000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>