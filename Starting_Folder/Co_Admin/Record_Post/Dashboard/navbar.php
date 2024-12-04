<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid px-lg-5">
        <!-- Mobile Logo (left-aligned) -->
        <div class="d-lg-none">
            <a href="#" id="dashboard-link2" class="navbar-brand up">
                <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" alt="Logo" width="40" height="40">
                <span class="ms-2 fw-semibold">TAP-N-LOG</span>
            </a>
        </div>

        <!-- Hamburger Menu (right-aligned on mobile) -->
        <button class="navbar-toggler border-0 d-lg-none ms-auto" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas"
            aria-controls="navbarOffcanvas">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Desktop Logo -->
        <div class="col-2 d-none d-lg-block">
            <a href="#" id="dashboard-link" class="navbar-brand up">
                <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" alt="Logo" width="40" height="40">
                <span class="ms-2 fw-semibold">TAP-N-LOG</span>
            </a>
        </div>

        <!-- Center Menu -->
        <div class="col-8 d-none d-lg-block">
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Attendance_Log/main_page.php" class="nav-link text-center up">
                        ATTENDANCE LOG
                    </a>
                </li>
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Records/main_page.php" class="nav-link text-center up">
                        RECORDS
                    </a>
                </li>
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Activity_Logs/main_page.php" class="nav-link text-center up">
                        ACTIVITY LOGS
                    </a>
                </li>
            </ul>
        </div>

        <!-- Right Section -->
        <div class="col-2 d-none d-lg-block text-end">
            <a href="#" id="logout-link" class="btn btn-link text-decoration-none nav-logout up" style="color: #1877f2; font-weight: 500;">
                <i class="bi bi-box-arrow-right fs-4 text-primary"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Right Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="navbarOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close up" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Attendance_Log/main_page.php" class="nav-link up">
                    <i class="bi bi-calendar-check me-2"></i>ATTENDANCE LOG
                </a>
            </li>
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Records/main_page.php" class="nav-link up">
                    <i class="bi bi-file-text me-2"></i>RECORDS
                </a>
            </li>
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/Activity_Logs/main_page.php" class="nav-link up">
                    <i class="bi bi-clock-history me-2"></i>ACTIVITY LOGS
                </a>
            </li>
            <li class="nav-item mt-3 border-top pt-3">
                <a href="#" id="logout-link2" class="nav-link up" style="color: #1877f2; font-weight: 500;">
                    <i class="bi bi-box-arrow-right me-2"></i>LOGOUT
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Navbar Styles */
    .navbar {
        height: 70px;
        z-index: 1030;
    }

    .navbar-brand {
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }

    .navbar-brand img {
        transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
        transform: scale(1.05);
    }

    /* Navigation Links */
    .nav-link {
        color: #444;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        border-radius: 6px;
    }

    .nav-link:hover {
        color: #1877f2;
        background-color: rgba(24, 119, 242, 0.1);
    }

    .nav-link.active {
        color: #1877f2;
        background-color: rgba(24, 119, 242, 0.1);
    }

    .nav-logout {
        transition: all 0.3s ease;
        border-radius: 6px;
    }

    .nav-logout:hover {
        color: #1877f2;
        background-color: rgba(24, 119, 242, 0.1);
    }

    /* Mobile Styles */
    @media (max-width: 991.98px) {
        .navbar {
            padding: 0.5rem 1rem;
        }

        .navbar-toggler {
            padding: 4px;
            border: none;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .offcanvas {
            max-width: 300px;
        }

        .offcanvas-header {
            padding: 1rem 1.5rem;
            background-color: #f8f9fa;
        }

        .offcanvas-body {
            padding: 1.5rem;
        }

        .offcanvas .nav-link {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
        }
    }

    /* Smooth transitions */
    .navbar,
    .nav-link,
    .navbar-brand,
    .btn {
        transition: all 0.3s ease;
    }


    /* ACCESSIBLE CSS FOR MAIN ELEMENTS */

    body {
        background: url('/tapnlog/image/logo_and_icons/bsu-bg.png') no-repeat center center fixed;
        background-size: cover;
        padding-top: 87px;
    }

    /* Customized buttons */
    .btn-custom {
        border: none;
        border-radius: 50px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        text-align: center;
        box-shadow: 0 4px 2px rgba(0, 0, 0, 0.2);
    }

    .btn-custom:hover {
        transform: translateY(-2px);
    }

    /* animation */

    .up {
        transition: all 0.3s ease;
        text-transform: uppercase;
    }

    .up:hover {
        transform: translateY(-2px);
    }

    .invalid-feedback {
        display: none;
        animation: shake 0.3s ease-in-out;
    }

    .invalid-feedback.active {
        display: block;
        color: red;
        animation: shake 0.3s ease-in-out;
    }

    @keyframes shake {
        0% {
            transform: translateX(0);
        }

        25% {
            transform: translateX(-5px);
        }

        50% {
            transform: translateX(5px);
        }

        75% {
            transform: translateX(-5px);
        }

        100% {
            transform: translateX(0);
        }
    }

    .swal2-popup .swal2-actions {
        gap: 1rem;
        width: 100%;
        margin-top: 1.5rem;
        margin-bottom: 1.5rem;
        max-width: 18em;
    }

    /* Responsive design */
    @media (max-width: 426px) {

        .back-icon {
            top: 73px;
            left: 10px;
        }
    }

    /* glass css with scroll bar */
    .glass-scroll {
        border-radius: 20px !important;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(2.8px);
        -webkit-backdrop-filter: blur(2.8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 10px 1px rgba(128, 128, 128, 0.8);
        padding-right: 8px;
    }

    /* Custom Scrollbar Styles */
    .glass-scroll::-webkit-scrollbar {
        width: 8px;
        background: transparent;
    }

    .glass-scroll::-webkit-scrollbar-thumb {
        background: rgba(128, 128, 128, 0.4);
        border-radius: 10px;
    }

    .glass-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(128, 128, 128, 0.6);
    }

    .glass-scroll::-webkit-scrollbar-track {
        background: rgba(128, 128, 128, 0.1);
        border-radius: 10px;
        margin: 10px 0;
    }


    /* TABLES */
    .table-responsive {
        height: calc(100vh - 320px);
        overflow-y: auto;
        margin-bottom: 10px;
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

    .glass {
        border-radius: 20px !important;
        background: rgba(255, 255, 255, 0.55);
        backdrop-filter: blur(2.8px);
        -webkit-backdrop-filter: blur(2.8px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 5px 10px 1px rgba(128, 128, 128, 0.8);
    }

    .page-title {
        color: #1877f2;
        font-weight: 750;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>

<script>
    $(document).ready(function() {
        $('#logout-link, #logout-link2').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you really want to logout?',
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
                    window.location.href = '/tapnlog/Starting_Folder/Co_Admin/Record_Post/Auth/logout.php';
                }
            });
        });

        $('#dashboard-link, #dashboard-link2').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to redirect to the home page?',
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
                    window.location.href = '/TAPNLOG/Starting_Folder/Co_Admin/Record_Post/Dashboard/dashboard_home.php';
                }
            });
        });

        // Set active nav item based on current page
        let currentPath = window.location.pathname;
        $('.navbar-nav .nav-link').each(function() {
            if ($(this).attr('href') === currentPath) {
                $(this).addClass('active');
            }
        });
    });
</script>