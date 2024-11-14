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

                <!-- QR Code Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block">
                    <a href="#" id="nav-showQRButton" class="nav-link">
                        <i class="bi bi-qr-code" style="font-size: 1.5rem; color: #1877f2;"></i>
                    </a>
                </li>

                
                <!-- Profile Icon with Modal Trigger (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#nav_profileModal">
                        <img src="/TAPNLOG/Image/LOGO_AND_ICONS/admin_icon.png" alt="Profile Icon" style="width: 30px; height: auto;">
                    </a>
                </li>

                <!-- Logout Button (Visible on Large Screens) -->
                <li class="nav-item d-none d-lg-block">
                    <a href="#" id="logout-link" class="nav-link">
                        <i class="bi bi-box-arrow-right" style="font-size: 1.5rem; color: #1877f2;"></i>
                    </a>
                </li>

                <!-- Website link (Visible on Small Screens) -->
                <li class="nav-item d-lg-none text-center">
                    <a href="#" id="nav-showQRButton2" class="nav-link">Website Link</a>
                </li>

                <!-- Account Settings (Visible on Small Screens) -->
                <li class="nav-item d-lg-none text-center">
                    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#nav_profileModal">Account Settings</a>
                </li>


                <!-- Logout Button -->
                <li class="nav-item d-lg-none text-center">
                    <a href="#" id="logout-link2" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Profile Modal -->
<div class="modal fade" id="nav_profileModal" tabindex="-1" aria-labelledby="nav_profileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_profileModalLabel">Account Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary w-100 mb-2" id="nav_sendCodeBtn1">Change Email</button>
                <button type="button" class="btn btn-secondary w-100" id="nav_changePasswordBtn">Change Password</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Enter OTP Code -->
<div class="modal fade" id="nav_modalOTP1" data-bs-backdrop="static" tabindex="-1" aria-labelledby="nav_modalOTP1Label" aria-hidden="true">
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

                <button id="nav_submitOtpBtn1" class="btn btn-primary mt-3">Submit</button>
                <button id="nav_resendCodeBtn1" class="btn btn-warning mt-3" disabled>Resend Code</button>
                <button id="nav_backBtn1" class="btn btn-secondary mt-3">Back</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Enter New Email -->
<div class="modal fade" id="nav_modalNewEmail" data-bs-backdrop="static" tabindex="-1" aria-labelledby="nav_modalNewEmailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nav_modalNewEmailLabel">CHANGE EMAIL</h5>
                <button type="button" class="btn-close" id="closeNewEmailBtn"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <input type="text" id="nav_newEmail" class="form-control" placeholder="Enter New Email">
                    <div id="nav_newEmail-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                </div>

                <button id="nav_sendCodeBtn2" class="btn btn-primary mt-3">Send Code</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3: Enter OTP Code for New Email -->
<div class="modal fade" id="nav_modalOTP2" data-bs-backdrop="static" tabindex="-1" aria-labelledby="nav_modalOTP2Label" aria-hidden="true">
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

                <button id="nav_submitOtpBtn2" class="btn btn-primary mt-3">Submit</button>
                <button id="nav_resendCodeBtn2" class="btn btn-warning mt-3" disabled>Resend Code</button>
                <button id="nav_backBtn2" class="btn btn-secondary mt-3">Back</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4: Change password -->
<div class="modal fade" id="nav_modalChangePassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="nav_modalChangePasswordLabel" aria-hidden="true">
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

                <button id="nav_submitChangePasswordBtn" class="btn btn-primary mt-3">Submit</button>
                <button id="nav_discardChangePasswordBtn" class="btn btn-secondary mt-3">Discard</button>
            </div>
        </div>
    </div>
</div>





<style>
    .navbar {
        background-color: white;
        /* Background color */
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
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/tapnlog/Starting_Folder/Main_Admin/Auth/logout.php';
            }
        });

        $('#dashboard-link').on('click', function(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to go to the main dashboard?')) {
                window.location.href = '/tapnlog/Starting_Folder/Main_Admin/Dashboard/dashboard_home.php';
            }
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
                url: '/tapnlog/fetch_link.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        const websiteLink = response.website_link;

                        // Generate QR code dynamically
                        QRCode.toDataURL(websiteLink, {
                            width: 200,
                            color: {
                                dark: "#1877f2", // QR code color
                                light: "#ffffff" // Background color
                            }
                        }, function(error, url) {
                            if (!error) {
                                Swal.fire({
                                    title: 'Scan this QR Code to Access the Website',
                                    html: `
                                            <div style="text-align: center;">
                                                <img src="${url}" alt="QR Code" style="width: 200px; height: 200px; margin-bottom: 15px;">
                                                <p style="margin-top: 10px;">Or type this link:</p>
                                                <a href="${websiteLink}" target="_blank" style="color: #1877f2; font-weight: bold;">${websiteLink}</a>
                                            </div>
                                        `,
                                    showConfirmButton: true,
                                    confirmButtonText: 'Close',
                                    showClass: {
                                        popup: `
                                                animate__animated
                                                animate__bounceIn
                                                animate__faster
                                                `
                                    },
                                    hideClass: {
                                        popup: `
                                                animate__animated
                                                animate__bounceOut
                                                animate__faster
                                                `
                                    }
                                });
                            } else {
                                console.error("QR Code generation error:", error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Failed to generate QR Code.',
                                    icon: 'error',
                                    confirmButtonText: 'Close',
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: response.message || 'Failed to fetch website link.',
                            icon: 'error',
                            confirmButtonText: 'Close',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Error occurred while fetching the website link.',
                        icon: 'error',
                        confirmButtonText: 'Close',
                    });
                }
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
                    $('#nav_sendCodeBtn1').prop('disabled', false).text('Change Email');
                }
            }, 1000);
        }

        // Send OTP Code on button click
        $('#nav_sendCodeBtn1').click(function() {

            nav_startSendTimer1(); // Start timer to prevent multiple requests

            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/send_otp1.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        startResendTimer1();

                        alert(response.message); // Success message

                        $('#nav_modalOTP1').modal('show'); // Show OTP modal

                        // Clear the feedback message and value initially
                        $('#nav_otpCode1').val('');
                        $('#nav_otpCode1').removeClass('is-invalid');
                        $('#nav_otpCode1-feedback').text('').removeClass('invalid-feedback');

                        $('#nav_profileModal').modal('hide');

                    } else {
                        alert(response.message); // Display error message
                        clearInterval(nav_sendTimeout1);
                        $('#nav_sendCodeBtn1').prop('disabled', false).text('Change Email');
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                    clearInterval(nav_sendTimeout1);
                    $('#nav_sendCodeBtn1').prop('disabled', false).text('Change Email');
                }
            });
        });



        // MODAL OTP 1 BUTTONS

        $('#nav_backBtn1').click(function() {
            if (confirm('You may lost the OTP code. Do you want to proceed?')) {
                clearInterval(resendTimeout1);
                $('#nav_resendCodeBtn1').prop('disabled', false).text('Resend Code');
                $('#nav_modalOTP1').modal('hide');
                $('#nav_profileModal').modal('show');
            }
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

                                // Initially clear feed back messages and value
                                $('#nav_newEmail').val('');
                                $('#nav_newEmail').removeClass('is-invalid');
                                $('#nav_newEmail-feedback').text('').removeClass('invalid-feedback');

                                // Open modal for New Email
                                $('#nav_modalNewEmail').modal('show');
                            } else {
                                let attemptsLeft = 5 - nav_otpAttemptCounter1;
                                nav_otpAttemptCounter1++;

                                alert(response.message + " You have " + attemptsLeft + " attempts left.");
                            }
                        },
                        error: function() {
                            alert('An error occurred while verifying OTP.');
                        }

                    });

                } else {
                    // If attempts reach 5, reset counter and switch to the previous modal
                    nav_otpAttemptCounter1 = 0;
                    alert('Maximum OTP attempts reached. Returning to get a new code.');

                    $('#nav_modalOTP1').modal('hide');
                    $('#nav_profileModal').modal('show');
                }
            }
        });

        let resendTimeout1;
        // Function to start timer for resend code
        function startResendTimer1() {
            let timeLeft = 30;
            resendTimeout1 = setInterval(function() {
                $('#nav_resendCodeBtn1').prop('disabled', true).text('Resend Code at ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(resendTimeout1);
                    $('#nav_resendCodeBtn1').prop('disabled', false).text('Resend Code');
                }
            }, 1000);
        }

        // Resend OTP Code on button click
        $('#nav_resendCodeBtn1').click(function() {

            startResendTimer1(); // Start timer to prevent multiple requests

            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/resend_otp1.php',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        nav_startSendTimer1();

                        alert(response.message); // Success message
                    } else {
                        alert(response.message); // Display error message
                        clearInterval(resendTimeout1);
                        $('#nav_resendCodeBtn1').prop('disabled', false).text('Resend Code');
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                    clearInterval(resendTimeout1);
                    $('#nav_resendCodeBtn1').prop('disabled', false).text('Resend Code');
                }
            });
        });



        // MODAL 2: NEW EMAIL BUTTONS

        $('#closeNewEmailBtn').click(function() {
            if (confirm('Do you want to cancel changing email?')) {
                $('#nav_modalNewEmail').modal('hide');
                $('#nav_profileModal').modal('show');
            }
        });

        // Function to start timer
        let sendTimeout2;

        function startSendTimer2() {
            let timeLeft = 30;
            sendTimeout2 = setInterval(function() {
                $('#nav_sendCodeBtn2').prop('disabled', true).text('Try again in ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(sendTimeout2);
                    $('#nav_sendCodeBtn2').prop('disabled', false).text('Send Code');
                }
            }, 1000);
        }

        // Send OTP Code for new email
        $('#nav_sendCodeBtn2').click(function() {
            let _newEmail = $('#nav_newEmail').val();

            // Check feedback message
            nav_validateEmail();

            if (nav_checksendCodeBtn2()) {

                startSendTimer2(); // Start timer to prevent multiple requests

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
                        if (response.success) {

                            startResendTimer2();

                            alert(response.message); // Success message

                            // Initially clear the feedback message and value
                            $('#nav_otpCode2').val('');
                            $('#nav_otpCode2').removeClass('is-invalid');
                            $('#nav_otpCode2-feedback').text('').removeClass('invalid-feedback');


                            $('#nav_modalOTP2').modal('show'); // Show OTP modal
                            $('#nav_modalNewEmail').modal('hide');

                        } else {
                            alert(response.message); // Display error message
                            clearInterval(sendTimeout2);
                            $('#nav_sendCodeBtn2').prop('disabled', false).text('Send Code');
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing your request.');
                        clearInterval(sendTimeout2);
                        $('#nav_sendCodeBtn2').prop('disabled', false).text('Send Code');
                    }
                });
            }
        });



        // MODAL 3 BUTTONS: OTP CODE FOR NEW EMAIL

        // back button
        $('#nav_backBtn2').click(function() {
            if (confirm('You may lost the OTP code. Do you want to proceed?')) {
                clearInterval(resendTimeout2);
                $('#nav_resendCodeBtn2').prop('disabled', false).text('Resend Code');
                $('#nav_modalOTP2').modal('hide');
                $('#nav_modalNewEmail').modal('show');
            }
        });

        // Function to start timer
        let resendTimeout2;

        function startResendTimer2() {
            let timeLeft = 30;
            resendTimeout2 = setInterval(function() {
                $('#nav_resendCodeBtn2').prop('disabled', true).text('Resend Code at ' + timeLeft + 's');
                timeLeft--;
                if (timeLeft <= 0) {
                    clearInterval(resendTimeout2);
                    $('#nav_resendCodeBtn2').prop('disabled', false).text('Resend Code');
                }
            }, 1000);
        }

        // Resend OTP Code on button click
        $('#nav_resendCodeBtn2').click(function() {

            startResendTimer2(); // Start timer to prevent multiple requests

            $.ajax({
                url: '/TAPNLOG/Starting_Folder/Main_Admin/Dashboard/resend_otp2.php',
                type: 'POST',
                data: {
                    email: nav_current.nav_newEmail
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {

                        startSendTimer2();

                        alert(response.message); // Success message
                    } else {
                        alert(response.message); // Display error message
                        clearInterval(resendTimeout2);
                        $('#nav_resendCodeBtn2').prop('disabled', false).text('Resend Code');
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                    clearInterval(resendTimeout2);
                    $('#nav_resendCodeBtn2').prop('disabled', false).text('Resend Code');
                }
            });
        });


        let nav_otpAttemptCounter2 = 0;
        // OTP submit button for updating email
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
                                if (confirm("OTP verified successfully! Do you want to update your email address?")) {
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
                                                alert(updateResponse.message); // Confirm email update success
                                                $('#nav_modalOTP2').modal('hide');
                                                $('#nav_profileModal').modal('show');

                                            } else {
                                                alert(updateResponse.message); // Show error message if email update failed
                                            }
                                        },
                                        error: function() {
                                            alert('An error occurred while updating your email.');
                                        }
                                    });
                                } else {
                                    alert("Email update canceled.");
                                    $('#nav_modalOTP2').modal('hide');
                                    $('#nav_profileModal').modal('show');
                                }
                            } else {
                                let attemptsLeft = 5 - nav_otpAttemptCounter2;
                                nav_otpAttemptCounter2++;

                                alert(response.message + " You have " + attemptsLeft + " attempts left.");
                            }
                        },
                        error: function() {
                            alert('An error occurred while verifying OTP.');
                        }
                    });
                } else {
                    // If attempts reach 5, reset counter and switch to the previous modal
                    nav_otpAttemptCounter2 = 0;
                    alert('Maximum OTP attempts reached. Returning to get a new code.');

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
                if (confirm('You are about to change your password. Do you want to proceed?')) {
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
                                alert(response.message); // Show success message

                                $('#nav_modalChangePassword').modal('hide');
                                $('#nav_profileModal').modal('show');

                            } else {
                                alert(response.message); // Show error message
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors
                            alert('An error occurred: ' + error);
                        }
                    });
                }
            }

        });

        $('#nav_discardChangePasswordBtn').on('click', function(e) {

            e.preventDefault();

            if (confirm('You are about to cancel the reset password. Do you want to proceed?')) {
                $('#nav_modalChangePassword').modal('hide');
                $('#nav_profileModal').modal('show');
            }

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