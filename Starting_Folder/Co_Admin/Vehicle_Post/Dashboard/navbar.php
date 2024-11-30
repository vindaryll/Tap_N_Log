<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
    <div class="container-fluid px-lg-5">
        <!-- Mobile Logo (left-aligned) -->
        <div class="d-lg-none">
            <a href="#" id="dashboard-link2" class="navbar-brand">
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
            <a href="#" id="dashboard-link" class="navbar-brand">
                <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" alt="Logo" width="40" height="40">
                <span class="ms-2 fw-semibold">TAP-N-LOG</span>
            </a>
        </div>

        <!-- Center Menu -->
        <div class="col-8 d-none d-lg-block">
            <ul class="navbar-nav justify-content-center">
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Vehicle_Log/main_page.php" class="nav-link text-center">
                        VEHICLE LOG
                    </a>
                </li>
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Records/main_page.php" class="nav-link text-center">
                        RECORDS
                    </a>
                </li>
                <li class="nav-item mx-3 d-flex justify-content-center align-items-center">
                    <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Activity_Logs/main_page.php" class="nav-link text-center">
                        ACTIVITY LOGS
                    </a>
                </li>
            </ul>
        </div>

        <!-- Right Section -->
        <div class="col-2 d-none d-lg-block text-end">
            <a href="#" id="logout-link" class="btn btn-link text-decoration-none" style="color: #1877f2; font-weight: 500;">
                <i class="bi bi-box-arrow-right fs-4 text-primary"></i>
            </a>
        </div>
    </div>
</nav>

<!-- Right Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="navbarOffcanvasLabel">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Vehicle_Log/main_page.php" class="nav-link">
                    <i class="bi bi-calendar-check me-2"></i>VEHICLE LOG
                </a>
            </li>
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Records/main_page.php" class="nav-link">
                    <i class="bi bi-file-text me-2"></i>RECORDS
                </a>
            </li>
            <li class="nav-item">
                <a href="/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/Activity_Logs/main_page.php" class="nav-link">
                    <i class="bi bi-clock-history me-2"></i>ACTIVITY LOGS
                </a>
            </li>
            <li class="nav-item mt-3 border-top pt-3">
                <a href="#" id="logout-link2" class="nav-link" style="color: #1877f2; font-weight: 500;">
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

    /* Add margin to main content to prevent navbar overlap */
    body {
        padding-top: 87px;
    }

    /* Smooth transitions */
    .navbar,
    .nav-link,
    .navbar-brand,
    .btn {
        transition: all 0.3s ease;
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
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Auth/logout.php';
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
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Dashboard/dashboard_home.php';
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