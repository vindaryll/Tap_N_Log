<style>
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

    .nav-logout {
        transition: all 0.3s ease;
        border-radius: 6px;
    }

    .nav-logout:hover {
        color: #1877f2;
        background-color: rgba(24, 119, 242, 0.1);
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
                    <a href="#" id="logout-link" class="nav-link nav-logout up">
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
                <a href="#" id="logout-link2" class="nav-link nav-logout up" style="color: #1877f2; font-weight: 500;">
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
                customClass: {
                    confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                    cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/TAPNLOG/Starting_Folder/Co_Admin/Vehicle_Post/Auth/logout.php';
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