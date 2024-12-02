<style>
    /* Add margin to main content to prevent navbar overlap */
    body {
        padding-top: 87px;
    }

    .navbar {
        background-color: white;
        position: fixed;
        top: 0;
        z-index: 1030;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        height: 70px;
    }

    /* Brand/Logo Styles */
    .navbar-brand {
        display: flex;
        align-items: center;
        font-size: 1.25rem;
        padding: 0;
    }

    .navbar-brand img {
        transition: transform 0.3s ease;
        width: 40px;
        height: 40px;
    }

    .navbar-brand:hover img {
        transform: scale(1.05);
    }

    /* Desktop specific styles */
    @media (min-width: 992px) {
        .container-fluid {
            padding: 0 2rem;
        }

        /* Right section icons */
        .navbar-nav.ms-auto {
            display: flex;
            flex-direction: row;
            justify-content: flex-end;
            align-items: center;
            gap: 1.5rem;
            height: 100%;
        }

        .navbar-nav.ms-auto .nav-link {
            padding: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            top: 2px;
        }

        .navbar-nav.ms-auto .bi {
            font-size: 1.5rem;
            color: #1877f2;
        }
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
</style>

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

        <!-- Desktop Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Logout Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block">
                    <a href="#" id="logout-link" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>
            </ul>
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
                <a href="#" id="logout-link2" class="nav-link" style="color: #1877f2; font-weight: 500;">
                    <i class="bi bi-box-arrow-right me-2"></i>LOGOUT
                </a>
            </li>
        </ul>
    </div>
</div>

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

        // Handle click events to add active class
        $('.navbar-nav .nav-link').on('click', function() {
            $('.navbar-nav .nav-link').removeClass('active'); // Remove active class from all
            $(this).addClass('active'); // Add active class to clicked link
        });
    });
</script>