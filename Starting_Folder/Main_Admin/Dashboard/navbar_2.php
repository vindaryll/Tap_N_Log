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

    /* Navigation Links */
    .navbar-nav .nav-link {
        color: #444;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        border-radius: 6px;
        gap: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: start;
    }

    .navbar-nav .nav-link:hover {
        color: #1877f2;
        background-color: rgba(24, 119, 242, 0.1);
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
            gap: 0.5rem;
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
            <a href="#" id="dashboard-link" class="navbar-brand up">
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
            <a href="#" id="dashboard-link2" class="navbar-brand up">
                <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" alt="Logo" width="40" height="40">
                <span class="ms-2 fw-semibold">TAP-N-LOG</span>
            </a>
        </div>

        <!-- Desktop Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- QR Code Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block mx-2">
                    <a href="#" id="nav-showQRButton" class="nav-link up">
                        <i class="bi bi-qr-code"></i>
                    </a>
                </li>

                <!-- Profile Icon with Modal Trigger (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block mx-2">
                    <a href="#" class="nav-link up" data-bs-toggle="modal" data-bs-target="#nav_profileModal">
                        <i class="bi bi-person-circle"></i>
                    </a>
                </li>

                <!-- Logout Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block mx-2">
                    <a href="#" id="logout-link" class="nav-link up">
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
        <button type="button" class="btn-close up" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" id="nav-showQRButton2" style="color: #1877f2; font-weight: 500;" class="nav-link">
                    <i class="bi bi-qr-code me-2"></i>WEBSITE LINK
                </a>
            </li>
            <li class="nav-item">
                <a href="#" style="color: #1877f2; font-weight: 500;" class="nav-link" data-bs-toggle="modal" data-bs-target="#nav_profileModal">
                    <i class="bi bi-person-circle me-2"></i>ACCOUNT SETTINGS
                </a>
            </li>
            <li class="nav-item">
                <a href="#" style="color: #1877f2; font-weight: 500;" id="logout-link2" class="nav-link">
                    <i class="bi bi-box-arrow-right me-2"></i>LOGOUT
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="nav_profileModal" tabindex="-1" aria-labelledby="nav_profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_profileModalLabel">ACCOUNT SETTINGS</h5>
                <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary btn-custom w-100 mb-2" id="nav_sendCodeBtn1">CHANGE EMAIL</button>
                <button type="button" class="btn btn-secondary btn-custom w-100" id="nav_changePasswordBtn">CHANGE PASSWORD</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Enter OTP Code -->
<div class="modal fade" id="nav_modalOTP1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nav_modalOTP1Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_modalOTP1Label">VERIFYING CURRENT EMAIL</h5>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <input type="text" id="nav_otpCode1" class="form-control" placeholder="Enter OTP">
                    <div id="nav_otpCode1-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="w-100 mt-3">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-12 col-sm-3 mb-2">
                            <button id="nav_backBtn1" class="btn btn-secondary btn-custom text-uppercase w-100">BACK</button>
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <button id="nav_resendCodeBtn1" class="btn btn-warning btn-custom text-uppercase w-100" disabled>RESEND CODE</button>
                        </div>
                        <div class="col-12 col-sm-3 mb-2">
                            <button id="nav_submitOtpBtn1" class="btn btn-primary btn-custom text-uppercase w-100">SUBMIT</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Enter New Email -->
<div class="modal fade" id="nav_modalNewEmail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nav_modalNewEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_modalNewEmailLabel">CHANGE EMAIL</h5>
                <button type="button" class="btn-close up" id="Nav_closeNewEmailBtn"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <input type="text" id="nav_newEmail" class="form-control" placeholder="Enter New Email">
                    <div id="nav_newEmail-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button id="nav_sendCodeBtn2" class="btn btn-primary btn-custom col-6 mt-3">SEND CODE</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Enter OTP Code for New Email -->
<div class="modal fade" id="nav_modalOTP2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nav_modalOTP2Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_modalOTP2Label">VERIFYING NEW EMAIL</h5>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <input type="text" id="nav_otpCode2" class="form-control" placeholder="Enter OTP code">
                    <div id="nav_otpCode2-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="w-100 mt-3">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-12 col-sm-3 mb-2">
                            <button id="nav_backBtn2" class="btn btn-secondary btn-custom text-uppercase w-100">BACK</button>
                        </div>
                        <div class="col-12 col-sm-6 mb-2">
                            <button id="nav_resendCodeBtn2" class="btn btn-warning btn-custom text-uppercase w-100" disabled>RESEND CODE</button>
                        </div>
                        <div class="col-12 col-sm-3 mb-2">
                            <button id="nav_submitOtpBtn2" class="btn btn-primary btn-custom text-uppercase w-100">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: Change password -->
<div class="modal fade" id="nav_modalChangePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nav_modalChangePasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_modalChangePasswordLabel">CHANGE PASSWORD</h5>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="nav_current_password" class="form-control" placeholder="Enter current password" required>
                        <span class="input-group-text nav-toggle-password nav_change_toggle-password">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>
                    <div id="nav_current_password-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="nav_new_password" class="form-control" placeholder="Enter new password" required>
                        <span class="input-group-text nav-toggle-password nav_change_toggle-password">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>
                    <div id="nav_new_password-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" id="nav_confirm_password" class="form-control" placeholder="Re-enter new password" required>
                        <span class="input-group-text nav-toggle-password nav_change_toggle-password">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>
                    <div id="nav_confirm_password-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <div class="w-100 mt-3">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col-6 mb-2">
                            <button id="nav_discardChangePasswordBtn" class="btn btn-danger btn-custom text-uppercase w-100">DISCARD</button>
                        </div>
                        <div class="col-6 mb-2">
                            <button id="nav_submitChangePasswordBtn" class="btn btn-primary btn-custom text-uppercase w-100">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                customClass: {
                    confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                    cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                },
                buttonsStyling: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/tapnlog/Starting_Folder/Main_Admin/Auth/logout.php';
                }
            });
        });

        // Handle click events to add active class
        $('.navbar-nav .nav-link').on('click', function() {
            $('.navbar-nav .nav-link').removeClass('active'); // Remove active class from all
            $(this).addClass('active'); // Add active class to clicked link
        });


        // QR CODE
        $('#nav-showQRButton, #nav-showQRButton2').click(function() {
            // Fetch the website link from the backend
            $.ajax({
                url: '/tapnlog/fetch_link.php', // Ensure this endpoint is correct
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.website_link) {
                        const websiteLink = response.website_link;

                        // Generate QR code dynamically
                        QRCode.toDataURL(
                            websiteLink, {
                                width: 200,
                                color: {
                                    dark: "#1877f2", // QR code color
                                    light: "#ffffff", // Background color
                                },
                            },
                            function(error, url) {
                                if (!error) {
                                    Swal.fire({
                                        title: 'Scan this QR Code',
                                        html: `
                                                <div style="text-align: center;">
                                                    <img src="${url}" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;">
                                                    <p style="margin-top: 10px;">Or type this link:</p>
                                                    <a href="${websiteLink}" target="_blank" style="color: #1877f2; font-weight: bold;">${websiteLink}</a>
                                                </div>
                                        `,
                                        showConfirmButton: true,
                                        confirmButtonText: 'CLOSE',
                                        customClass: {
                                            confirmButton: 'col-12 btn btn-primary btn-custom text-uppercase',
                                        },
                                        buttonsStyling: false,
                                        showClass: {
                                            popup: 'animate__animated animate__zoomIn animate__faster',
                                        },
                                        hideClass: {
                                            popup: 'animate__animated animate__zoomOut animate__faster',
                                        },
                                    });
                                } else {
                                    console.error("QR Code generation error:", error);
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: 'Failed to generate QR Code.',
                                        icon: 'error',
                                        timer: 1500,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                }
                            }
                        );
                    } else {
                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: response.message || 'Failed to fetch website link.',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'Error occurred while fetching the website link.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });
                },
            });
        });


        // PROFILE MODAL BUTTON: CHANGE EMAIL

        // Function to start timer for send code
        let nav_sendTimeout1;

        function nav_startSendTimer1() {
            let timeLeft = 30;
            nav_sendTimeout1 = setInterval(function() {
                $('#nav_sendCodeBtn1').prop('disabled', true).text('Try again in ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(nav_sendTimeout1);
                    $('#nav_sendCodeBtn1').prop('disabled', false).text('CHANGE EMAIL');
                }
            }, 1000);
        }

        // Send OTP Code on button click
        $('#nav_sendCodeBtn1').click(function() {

            // Show a loading indicator
            Swal.fire({
                title: 'Sending OTP...',
                text: 'Please wait while we process your request.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading animation
                }
            });



            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/send_otp1.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    // close loading
                    Swal.close();
                    nav_startResendTimer1();

                    if (response.success) {

                        nav_startSendTimer1(); // Start timer to prevent multiple requests

                        // Success Message
                        Swal.fire({
                            position: 'top',
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        $('#nav_modalOTP1').modal('show'); // Show OTP modal

                        // Clear the feedback message and value initially
                        $('#nav_otpCode1').val('');
                        $('#nav_otpCode1').removeClass('is-invalid');
                        $('#nav_otpCode1-feedback').text('').removeClass('invalid-feedback');

                        $('#nav_profileModal').modal('hide');

                    } else {

                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        clearInterval(nav_sendTimeout1);
                        $('#nav_sendCodeBtn1').prop('disabled', false).text('CHANGE EMAIL');
                    }
                },
                error: function() {
                    Swal.close();

                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });

                    clearInterval(nav_sendTimeout1);
                    $('#nav_sendCodeBtn1').prop('disabled', false).text('CHANGE EMAIL');
                }
            });
        });



        // MODAL OTP 1 BUTTONS

        $('#nav_backBtn1').click(function() {

            Swal.fire({
                title: 'Warning!',
                text: 'You may lose the OTP code. Do you want to proceed?',
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
                    clearInterval(resendTimeout1);
                    $('#nav_resendCodeBtn1').prop('disabled', false).text('RESEND CODE');
                    $('#nav_modalOTP1').modal('hide');
                    $('#nav_profileModal').modal('show');
                }
            });
        });

        let nav_otpAttemptCounter1 = 0;
        $('#nav_submitOtpBtn1').click(function() {
            let otpCode = $('#nav_otpCode1').val();

            // Check feedback message
            nav_validateOTP1();

            if (nav_checksubmitOtpBtn1()) {

                // Check if attempts are within the limit
                if (nav_otpAttemptCounter1 < 5) {

                    $.ajax({
                        url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/verify_otp.php',
                        type: 'POST',
                        data: {
                            otpCode: otpCode
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {

                                // Reset the counter upon successful verification
                                nav_otpAttemptCounter1 = 0;

                                $('#nav_modalOTP1').modal('hide');

                                // Initially clear the feedback message and value
                                $('#nav_newEmail').val('');
                                $('#nav_newEmail').removeClass('is-invalid');
                                $('#nav_newEmail-feedback').text('').removeClass('invalid-feedback');

                                // Open modal for New Email
                                $('#nav_modalNewEmail').modal('show');
                            } else {
                                let attemptsLeft = 5 - nav_otpAttemptCounter1;
                                nav_otpAttemptCounter1++;

                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: response.message + " You have " + attemptsLeft + " attempts left.",
                                    icon: 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: 'An error occurred while verifying OTP.',
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }

                    });

                } else {
                    // If attempts reach 5, reset counter and switch to the previous modal
                    nav_otpAttemptCounter1 = 0;
                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'Maximum OTP attempts reached. Returning to get a new code.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });

                    $('#nav_modalOTP1').modal('hide');
                    $('#nav_profileModal').modal('show');
                }
            }
        });

        let resendTimeout1;
        // Function to start timer for resend code
        function nav_startResendTimer1() {
            let timeLeft = 30;
            resendTimeout1 = setInterval(function() {
                $('#nav_resendCodeBtn1').prop('disabled', true).text('RESEND CODE AT ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(resendTimeout1);
                    $('#nav_resendCodeBtn1').prop('disabled', false).text('RESEND CODE');
                }
            }, 1000);
        }

        // Resend OTP Code on button click
        $('#nav_resendCodeBtn1').click(function() {

            // Show a loading indicator
            Swal.fire({
                title: 'Resending OTP...',
                text: 'Please wait while we process your request.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading animation
                }
            });

            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/resend_otp1.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    nav_startResendTimer1();

                    if (response.success) {

                        nav_startSendTimer1();

                        // Success Message
                        Swal.fire({
                            position: 'top',
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                    } else {

                        // Display error message
                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        clearInterval(resendTimeout1);
                        $('#nav_resendCodeBtn1').prop('disabled', false).text('RESEND CODE');
                    }
                },
                error: function() {

                    Swal.close();

                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });

                    clearInterval(resendTimeout1);
                    $('#nav_resendCodeBtn1').prop('disabled', false).text('RESEND CODE');
                }
            });
        });



        // MODAL 2: NEW EMAIL BUTTONS

        $('#Nav_closeNewEmailBtn').click(function() {
            Swal.fire({
                position: 'top',
                title: 'Are you sure?',
                text: 'Do you want to cancel changing your email?',
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
                    $('#nav_modalNewEmail').modal('hide');
                    $('#nav_profileModal').modal('show');
                }
            });
        });

        // Function to start timer
        let sendTimeout2;

        function nav_startSendTimer2() {
            let timeLeft = 30;
            sendTimeout2 = setInterval(function() {
                $('#nav_sendCodeBtn2').prop('disabled', true).text('Try again in ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(sendTimeout2);
                    $('#nav_sendCodeBtn2').prop('disabled', false).text('SEND CODE');
                }
            }, 1000);
        }

        // Send OTP Code for new email
        $('#nav_sendCodeBtn2').click(function() {
            let _newEmail = $('#nav_newEmail').val();

            // Check feedback message
            nav_validateEmail();

            if (nav_checksendCodeBtn2()) {

                // Show a loading indicator
                Swal.fire({
                    title: 'Sending OTP...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading(); // Show loading animation
                    }
                });

                nav_current = {
                    nav_newEmail: _newEmail
                };

                $.ajax({
                    url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/send_otp2.php',
                    type: 'POST',
                    data: {
                        email: nav_current.nav_newEmail
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        nav_startSendTimer2();

                        if (response.success) {

                            startResendTimer2();

                            // Success Message
                            Swal.fire({
                                position: 'top',
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });

                            // Initially clear the feedback message and value
                            $('#nav_otpCode2').val('');
                            $('#nav_otpCode2').removeClass('is-invalid');
                            $('#nav_otpCode2-feedback').text('').removeClass('invalid-feedback');


                            $('#nav_modalOTP2').modal('show'); // Show OTP modal
                            $('#nav_modalNewEmail').modal('hide');

                        } else {
                            // Display error message
                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });

                            clearInterval(sendTimeout2);
                            $('#nav_sendCodeBtn2').prop('disabled', false).text('SEND CODE');
                        }
                    },
                    error: function() {

                        Swal.close();

                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: 'An error occurred while processing your request.',
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        clearInterval(sendTimeout2);
                        $('#nav_sendCodeBtn2').prop('disabled', false).text('SEND CODE');
                    }
                });
            }
        });



        // MODAL 3 BUTTONS: OTP CODE FOR NEW EMAIL

        // back button
        $('#nav_backBtn2').click(function() {
            Swal.fire({
                title: 'Warning!',
                text: 'You may lose the OTP code. Do you want to proceed?',
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
                    clearInterval(resendTimeout2);
                    $('#nav_resendCodeBtn2').prop('disabled', false).text('RESEND CODE');
                    $('#nav_modalOTP2').modal('hide');
                    $('#nav_modalNewEmail').modal('show');
                }
            });
        });

        // Function to start timer
        let resendTimeout2;

        function startResendTimer2() {
            let timeLeft = 30;
            resendTimeout2 = setInterval(function() {
                $('#nav_resendCodeBtn2').prop('disabled', true).text('RESEND CODE AT ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(resendTimeout2);
                    $('#nav_resendCodeBtn2').prop('disabled', false).text('RESEND CODE');
                }
            }, 1000);
        }

        // Resend OTP Code on button click
        $('#nav_resendCodeBtn2').click(function() {

            // Show a loading indicator
            Swal.fire({
                title: 'Resending OTP...',
                text: 'Please wait while we process your request.',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loading animation
                }
            });

            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/resend_otp2.php',
                type: 'POST',
                data: {
                    email: nav_current.nav_newEmail
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    startResendTimer2();

                    if (response.success) {

                        nav_startSendTimer2();

                        // Success Message
                        Swal.fire({
                            position: 'top',
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                    } else {

                        // Error Message
                        Swal.fire({
                            position: 'top',
                            title: 'Error!',
                            text: response.message,
                            icon: 'error',
                            timer: 1500,
                            timerProgressBar: true,
                            showConfirmButton: false,
                        });

                        clearInterval(resendTimeout2);
                        $('#nav_resendCodeBtn2').prop('disabled', false).text('RESEND CODE');
                    }
                },
                error: function() {
                    Swal.close();

                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'An error occurred while processing your request.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });

                    clearInterval(resendTimeout2);
                    $('#nav_resendCodeBtn2').prop('disabled', false).text('RESEND CODE');
                }
            });
        });

        let nav_otpAttemptCounter2 = 0;
        $('#nav_submitOtpBtn2').click(function() {
            let otpCode = $('#nav_otpCode2').val();

            nav_validateOTP2();

            if (nav_checksubmitOtpBtn2()) {

                if (nav_otpAttemptCounter2 < 5) {
                    $.ajax({
                        url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/verify_otp.php',
                        type: 'POST',
                        data: {
                            otpCode: otpCode
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {

                                nav_otpAttemptCounter2 = 0;

                                // Prompt for confirmation before updating the email
                                Swal.fire({
                                    title: 'Email has been verified.',
                                    text: 'Do you want to update your email address?',
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

                                        // Get the new email from a previously set variable or input
                                        let nav_newEmail = nav_current.nav_newEmail;

                                        $.ajax({
                                            url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/change_email.php',
                                            type: 'POST',
                                            data: {
                                                nav_newEmail: nav_newEmail
                                            },
                                            dataType: 'json',
                                            success: function(updateResponse) {
                                                if (updateResponse.success) {

                                                    // Success Message
                                                    Swal.fire({
                                                        position: 'top',
                                                        title: 'Success!',
                                                        text: updateResponse.message,
                                                        icon: 'success',
                                                        timer: 1500,
                                                        timerProgressBar: true,
                                                        showConfirmButton: false,
                                                    });

                                                    $('#nav_modalOTP2').modal('hide');
                                                    $('#nav_profileModal').modal('show');

                                                } else {

                                                    // Show error message if email update failed
                                                    Swal.fire({
                                                        position: 'top',
                                                        title: 'Error!',
                                                        text: updateResponse.message,
                                                        icon: 'error',
                                                        timer: 1500,
                                                        timerProgressBar: true,
                                                        showConfirmButton: false,
                                                    });
                                                }
                                            },
                                            error: function() {

                                                Swal.fire({
                                                    position: 'top',
                                                    title: 'Error!',
                                                    text: 'An error occurred while updating your email.',
                                                    icon: 'error',
                                                    timer: 1500,
                                                    timerProgressBar: true,
                                                    showConfirmButton: false,
                                                });
                                            }
                                        });

                                    } else {
                                        Swal.fire({
                                            position: 'top',
                                            title: 'Update Canceled',
                                            text: 'Your email address has not been updated.',
                                            icon: 'info',
                                            timer: 1500,
                                            timerProgressBar: true,
                                            showConfirmButton: false,
                                        });

                                        $('#nav_modalOTP2').modal('hide');
                                        $('#nav_profileModal').modal('show');
                                    }
                                });

                            } else {
                                let attemptsLeft = 5 - nav_otpAttemptCounter2;
                                nav_otpAttemptCounter2++;

                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: response.message + " You have " + attemptsLeft + " attempts left.",
                                    icon: 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        },
                        error: function() {

                            Swal.fire({
                                position: 'top',
                                title: 'Error!',
                                text: 'An error occurred while verifying OTP.',
                                icon: 'error',
                                timer: 1500,
                                timerProgressBar: true,
                                showConfirmButton: false,
                            });
                        }
                    });
                } else {
                    // If attempts reach 5, reset counter and switch to the previous modal
                    nav_otpAttemptCounter2 = 0;

                    Swal.fire({
                        position: 'top',
                        title: 'Error!',
                        text: 'Maximum OTP attempts reached. Returning to get a new code.',
                        icon: 'error',
                        timer: 1500,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    });

                    $('#nav_modalOTP2').modal('hide');
                    $('#nav_modalNewEmail').modal('show');
                }
            }
        });



        // FEEDBACK MESSAGE FUNCTION FOR MODAL 1: OTP

        $('#nav_otpCode1').on('input', nav_validateOTP1);

        function nav_validateOTP1() {
            const otpCode = $('#nav_otpCode1').val();

            let feedbackMessage = '';

            $('#nav_otpCode1-feedback').text('').removeClass('invalid-feedback');

            if (otpCode === "") {
                feedbackMessage = 'OTP code cannot be empty.';
            }

            if (feedbackMessage) {
                $('#nav_otpCode1-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_otpCode1').addClass('is-invalid'); // Mark input as invalid
            } else {
                $('#nav_otpCode1').removeClass('is-invalid');
            }
        }

        function nav_checksubmitOtpBtn1() {
            let isTextBoxValid = $('#nav_otpCode1').val().trim() !== "" && !$('#nav_otpCode1').hasClass('is-invalid');

            // If any field is invalid, return false
            if (!isTextBoxValid) {
                return false;
            } else {
                return true;
            }
        }


        // FEEDBACK MESSAGE FOR MODAL 2: ENTER NEW EMAIL

        $('#nav_newEmail').on('input', nav_validateEmail);

        function nav_validateEmail() {
            const email = $('#nav_newEmail').val().trim();
            const emailPattern = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;

            // Clear previous feedback
            $('#nav_newEmail-feedback').text('').removeClass('invalid-feedback');

            let feedbackMessage = '';

            // Validate email input
            if (email === "") {
                feedbackMessage = 'Email cannot be empty.';
            } else if (!emailPattern.test(email)) {
                feedbackMessage = 'Invalid email format.';
            }

            // Display feedback message if there are errors
            if (feedbackMessage) {
                $('#nav_newEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_newEmail').addClass('is-invalid');
            } else {
                $('#nav_newEmail').removeClass('is-invalid');
            }
        }

        function nav_checksendCodeBtn2() {
            let isEmailValid = $('#nav_newEmail').val().trim() !== "" && !$('#nav_newEmail').hasClass('is-invalid');

            // If any field is invalid, return false
            if (!isEmailValid) {
                return false;
            } else {
                return true;
            }
        }


        // FEEDBACK MESSAGE FUNCTION FOR MODAL 3: Verifying new email with OTP CODE

        $('#nav_otpCode2').on('input', nav_validateOTP2);

        function nav_validateOTP2() {
            const otpCode = $('#nav_otpCode2').val();

            let feedbackMessage = '';

            $('#nav_otpCode2-feedback').text('').removeClass('invalid-feedback');

            if (otpCode === "") {
                feedbackMessage = 'OTP code cannot be empty.';
            }

            if (feedbackMessage) {
                $('#nav_otpCode2-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_otpCode2').addClass('is-invalid'); // Mark input as invalid
            } else {
                $('#nav_otpCode2').removeClass('is-invalid');
            }
        }

        function nav_checksubmitOtpBtn2() {
            let isTextBoxValid = $('#nav_otpCode2').val().trim() !== "" && !$('#nav_otpCode2').hasClass('is-invalid');

            // If any field is invalid, return false
            if (!isTextBoxValid) {
                return false;
            } else {
                return true;
            }
        }




        // PROFILE MODAL BUTTON: CHANGE PASSWORD

        $('#nav_changePasswordBtn').on('click', function(e) {
            e.preventDefault();

            $('#nav_modalChangePassword').modal('show');

            // initially clear the value and feedback message
            $('#nav_current_password').val('');
            $('#nav_new_password').val('');
            $('#nav_confirm_password').val('');
            $('#nav_current_password-feedback').text('').removeClass('invalid-feedback');
            $('#nav_new_password-feedback').text('').removeClass('invalid-feedback');
            $('#nav_confirm_password-feedback').text('').removeClass('invalid-feedback');
            $('#nav_current_password').removeClass('is-invalid');
            $('#nav_new_password').removeClass('is-invalid');
            $('#nav_confirm_password').removeClass('is-invalid');

            // Set input types to password and reset the eye icon
            $('#nav_current_password').attr('type', 'password');
            $('#nav_new_password').attr('type', 'password');
            $('#nav_confirm_password').attr('type', 'password');
            $('.nav_change_toggle-password i').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');


            $('#nav_profileModal').modal('hide');
        });


        $('#nav_submitChangePasswordBtn').on('click', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Validate before submission
            nav_validateCurrentPassword();
            nav_validateNewPassword();
            nav_validateConfirmNewPassword();

            if (nav_checkChangePasswordButton()) {

                const currentPassword = $('#nav_current_password').val().trim();
                const newPassword = $('#nav_new_password').val().trim();
                const confirmPassword = $('#nav_confirm_password').val().trim();

                // Make the AJAX request
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to change your password. Do you want to proceed?',
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
                        $.ajax({
                            url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/change_password.php',
                            type: 'POST',
                            data: {
                                nav_current_password: currentPassword,
                                nav_new_password: newPassword,
                                confirm_new_password: confirmPassword
                            },
                            dataType: 'json',
                            success: function(response) {
                                // Handle the response from the server
                                if (response.success) {

                                    // Success Message
                                    Swal.fire({
                                        position: 'top',
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        timer: 1500,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });

                                    $('#nav_modalChangePassword').modal('hide');
                                    $('#nav_profileModal').modal('show');

                                } else {

                                    Swal.fire({
                                        position: 'top',
                                        title: 'Error!',
                                        text: response.message,
                                        icon: 'error',
                                        timer: 1500,
                                        timerProgressBar: true,
                                        showConfirmButton: false,
                                    });
                                }
                            },
                            error: function(xhr, status, error) {

                                // Handle any errors
                                Swal.fire({
                                    position: 'top',
                                    title: 'Error!',
                                    text: 'An error occurred: ' + error,
                                    icon: 'error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                });
                            }
                        });
                    }
                });
            }

        });

        $('#nav_discardChangePasswordBtn').on('click', function(e) {

            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to cancel the password reset process. Do you want to proceed?',
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
                    $('#nav_modalChangePassword').modal('hide');
                    $('#nav_profileModal').modal('show');
                }
            });

        });


        // FEEDBACK MESSAGE FOR CHANGE PASSWORD

        $('#nav_current_password').on('input', nav_validateCurrentPassword);
        $('#nav_new_password').on('input', nav_validateNewPassword);
        $('#nav_confirm_password').on('input', nav_validateConfirmNewPassword);

        function nav_validateCurrentPassword() {
            const currentPassword = $('#nav_current_password').val();
            let feedbackMessage = '';

            $('#nav_current_password-feedback').text('').removeClass('invalid-feedback');

            if (currentPassword === "") {
                feedbackMessage = 'Current password cannot be empty.';
            } else if (currentPassword.length < 8) {
                feedbackMessage = 'Current password must be at least 8 characters long.';
            }

            if (feedbackMessage) {
                $('#nav_current_password-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_current_password').addClass('is-invalid'); // Mark input as invalid
            } else {
                $('#nav_current_password').removeClass('is-invalid');
            }
        }

        function nav_validateNewPassword() {
            const newPassword = $('#nav_new_password').val();
            let feedbackMessage = '';

            $('#nav_new_password-feedback').text('').removeClass('invalid-feedback');

            if (newPassword === "") {
                feedbackMessage = 'New password cannot be empty.';
            } else if (newPassword.length < 8) {
                feedbackMessage = 'Password must be at least 8 characters long.';
            }

            if (feedbackMessage) {
                $('#nav_new_password-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_new_password').addClass('is-invalid'); // Mark input as invalid
            } else {
                $('#nav_new_password').removeClass('is-invalid');
            }
        }

        function nav_validateConfirmNewPassword() {
            const newPassword = $('#nav_new_password').val();
            const confirmPassword = $('#nav_confirm_password').val();
            let feedbackMessage = '';

            $('#nav_confirm_password-feedback').text('').removeClass('invalid-feedback');

            if (confirmPassword === "") {
                feedbackMessage = 'Please confirm your new password.';
            } else if (confirmPassword.length < 8) {
                feedbackMessage = 'Password must be at least 8 characters long.';
            } else if (newPassword !== confirmPassword) {
                feedbackMessage = 'Passwords do not match.';
            }

            if (feedbackMessage) {
                $('#nav_confirm_password-feedback').text(feedbackMessage).addClass('invalid-feedback');
                $('#nav_confirm_password').addClass('is-invalid'); // Mark input as invalid
            } else {
                $('#nav_confirm_password').removeClass('is-invalid');
            }
        }

        function nav_checkChangePasswordButton() {
            const isCurrentPasswordValid = $('#nav_current_password').val().trim() !== "" && !$('#nav_current_password').hasClass('is-invalid');
            const isNewPasswordValid = $('#nav_new_password').val().trim() !== "" && !$('#nav_new_password').hasClass('is-invalid');
            const isConfirmNewPasswordValid = $('#nav_confirm_password').val().trim() !== "" && !$('#nav_confirm_password').hasClass('is-invalid');

            if (!isCurrentPasswordValid || !isNewPasswordValid || !isConfirmNewPasswordValid) {
                return false;
            } else {
                return true;
            }
        }

    });
</script>