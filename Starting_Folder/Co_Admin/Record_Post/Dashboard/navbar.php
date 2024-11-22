<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
    <div class="container-fluid ms-3 me-3">
        <!-- Logo with Dashboard Link -->
        <a href="#" id="dashboard-link" class="navbar-brand">
            <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" alt="Logo" style="width: 40px; height: auto;">
        </a>

        <!-- Hamburger Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <!-- Logout Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block">
                    <a href="#" id="logout-link" class="nav-link">
                        <i class="bi bi-box-arrow-right" style="font-size: 1.5rem; color: #1877f2;"></i>
                    </a>
                </li>

                <!-- Logout Button -->
                <li class="nav-item d-lg-none text-center">
                    <a href="#" id="logout-link2" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>




<style>
    .navbar {
        background-color: white;
        /* Background color */
        position: sticky;
        /* Makes the navbar sticky */
        top: 0;
        /* Sticks to the top of the viewport */
        z-index: 1030;
        /* Ensures it stays above other elements */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Optional shadow for better visual hierarchy */
    }

    .navbar-nav .nav-link {
        color: red;
        /* Default text color */
        transition: background-color 0.3s, color 0.3s;
        /* Smooth transition */
        height: 56px;
        /* Set a fixed height for all nav links */
        display: flex;
        /* Use flex to center items */
        align-items: center;
        /* Center items vertically */
        justify-content: center;
        /* Center text horizontally */
    }

    .navbar-nav .nav-link:hover {
        background-color: red;
        /* Background color on hover */
        color: white;
        /* Text color on hover */
    }

    .navbar-nav .nav-link.active {
        background-color: transparent;
        /* Remove background color for active state */
        color: red;
        /* Keep the text color */
    }

    /* Center alignment for medium and smaller screens */
    @media (max-width: 992px) {
        .navbar-nav {
            flex-direction: column;
            /* Stack items vertically */
            align-items: center;
            /* Center items horizontally */
            width: 100%;
            /* Full width for nav items */
        }

        .navbar-nav .nav-item {
            width: 100%;
            /* Ensure full width for each item */
        }
    }
</style>

<script>
    $(document).ready(function() {

        // Toggle password visibility
        $(document).on('click', '.nav-toggle-password', function() {
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
                    window.location.href = '/tapnlog/Starting_Folder/Co_Admin/Record_Post/Auth/logout.php';
                }
            });
        });

        $('#dashboard-link').on('click', function(event) {
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
                    window.location.href = '/tapnlog/Starting_Folder/Co_Admin/Record_Post/Dashboard/dashboard_home.php';
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