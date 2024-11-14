<?php

// FRONT-END

session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Main_Admin/Dashboard/dashboard_home.php");
    exit();
}

// Redirect guards to landing page
if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

// Generate two random numbers
$number1 = rand(1, 10);
$number2 = rand(1, 10);

// Randomly select an operation (addition or subtraction)
$operation = rand(0, 1) ? '+' : '-'; // 0 for addition, 1 for subtraction
$captchaAnswer = $operation === '+' ? $number1 + $number2 : $number1 - $number2; // Calculate the answer
$_SESSION['captcha_answer'] = $captchaAnswer; // Store the answer in the session
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Jquery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Login Page</title>

    <style>
        /* Style for password inputs */
        .input-group {
            position: relative;
        }

        .toggle-password {
            cursor: pointer;
        }

        /* Style for login container */

        /* General Facebook theme */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            position: relative;
        }

        .back-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #1877f2;
            font-size: 40px;
            cursor: pointer;
            text-decoration: none;
        }

        .back-icon:hover {
            color: #145dbf;
        }

        .login-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 80px;
            height: 80px;
        }

        .logo-container h1 {
            color: #1877f2;
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #1877f2;
            border: none;
        }

        .btn-primary:hover {
            background-color: #145dbf;
        }

        .input-group-text {
            background-color: #f0f2f5;
            border: none;
        }

        .forgot-password-link {
            display: block;
            margin-top: 15px;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .login-container {
                margin: 10px;
                box-shadow: none;
            }

            .back-icon {
                font-size: 25px;
                top: 30px;
                left: 20px;
            }
        }
    </style>

</head>

<body>

    <!-- Back Icon -->
    <a href="../../Landing_page/index.php" class="back-icon">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="login-container">

        <!-- Logo Section -->
        <div class="logo-container">
            <img src="/tapnlog/image/logo_and_icons/logo_icon.png" alt="Tap-N-Log Logo">
            <h1>Tap-N-Log</h1>
        </div>

        <!-- Login Form -->
        <form class="w-100" id="loginForm" action="validate_login.php" method="post">
            <div class="mb-3">
                <label for="usernameOrEmail" class="form-label">Admin Username or Email:</label>
                <input type="text" class="form-control" id="usernameOrEmail" name="usernameOrEmail" required>
                <div id="usernameOrEmail-feedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" required>
                    <span class="input-group-text toggle-password">
                        <i class="bi bi-eye-fill"></i>
                    </span>
                </div>
                <div id="password-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
            </div>

            <div class="mb-3">
                <label for="captcha" class="form-label">What is <?php echo $number1 . " " . $operation . " " . $number2; ?>?</label>
                <input type="text" class="form-control" id="captcha" name="captcha" required>
                <div id="captcha-feedback" class="invalid-feedback"></div>
            </div>

            <button type="button" id="loginBtn" class="btn btn-primary w-100">Login</button>
        </form>

        <a href="#" class="forgot-password-link" id="forgotPasswordButton" data-bs-toggle="modal" data-bs-target="#modalForgotPassword">Forgot Password?</a>
    </div>

    <!-- Modal 1: Enter Email/Username -->
    <div class="modal fade" id="modalForgotPassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalForgotPasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalForgotPasswordLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <input type="text" id="emailOrUsername" class="form-control" placeholder="Enter Email or Username">
                        <div id="emailOrUsername-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                    </div>

                    <button id="sendCodeBtn" class="btn btn-primary mt-3">Send Code</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 2: Enter OTP Code -->
    <div class="modal fade" id="modalOTP" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalOTPLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalOTPLabel">Enter OTP Code</h5>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <input type="text" id="otpCode" class="form-control" placeholder="Enter OTP">
                        <div id="otpCode-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                    </div>

                    <button id="backBtn" class="btn btn-secondary mt-3">Back</button>
                    <button id="resendCodeBtn" class="btn btn-warning mt-3" disabled>Resend Code</button>
                    <button id="submitOtpBtn" class="btn btn-primary mt-3">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 3: Reset Password -->
    <div class="modal fade" id="modalResetPassword" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetPasswordLabel">Reset Password</h5>
                </div>
                <div class="modal-body">

                    <form id="changePasswordForm">
                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="New Password" required>
                                <span class="input-group-text toggle-password change_toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="changePassword-feedback1" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>

                        <div class="mb-3">
                            <div class="input-group">
                                <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                                <span class="input-group-text toggle-password change_toggle-password">
                                    <i class="bi bi-eye-fill"></i>
                                </span>
                            </div>
                            <div id="changePassword-feedback2" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                        </div>

                        <button id="submitResetBtn" class="btn btn-primary mt-3">Submit</button>
                        <button id="discardBtn" class="btn btn-secondary mt-3">Discard</button>

                    </form>

                </div>
            </div>
        </div>
    </div>



    <script>
        $(document).ready(function() {


            // Toggle password visibility
            $(document).on('click', '.toggle-password', function() {
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



            // LOGIN FUNCTIONS

            $('#loginBtn').click(function() {

                // Check feedback messages
                validateLoginTextBox1();
                validateLoginTextBox2();
                validateLoginTextBox3();

                if (checkLoginButton()) {
                    // Trigger the login form
                    $('#loginForm').submit();
                }
            });


            $('#usernameOrEmail').on('input', validateLoginTextBox1);
            $('#password').on('input', validateLoginTextBox2);
            $('#captcha').on('input', validateLoginTextBox3);

            function validateLoginTextBox1() {
                const usernameOrEmail = $('#usernameOrEmail').val();

                let feedbackMessage = '';

                $('#usernameOrEmail-feedback').text('').removeClass('invalid-feedback');

                if (usernameOrEmail === "") {
                    feedbackMessage = 'Email or username cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#usernameOrEmail-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#usernameOrEmail').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#usernameOrEmail').removeClass('is-invalid');
                }
            }

            function validateLoginTextBox2() {
                const password = $('#password').val();

                let feedbackMessage = '';

                $('#password-feedback').text('').removeClass('invalid-feedback');

                if (password === "") {
                    feedbackMessage = 'Password cannot be empty.';
                } else if (password.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                }

                if (feedbackMessage) {
                    $('#password-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#password').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#password').removeClass('is-invalid');
                }
            }

            function validateLoginTextBox3() {
                const captcha = $('#captcha').val();

                let feedbackMessage = '';

                $('#captcha-feedback').text('').removeClass('invalid-feedback');

                if (captcha === "") {
                    feedbackMessage = 'Captcha cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#captcha-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#captcha').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#captcha').removeClass('is-invalid');
                }
            }

            function checkLoginButton() {
                let isTextBox1Valid = $('#usernameOrEmail').val().trim() !== "" && !$('#usernameOrEmail').hasClass('is-invalid');
                let isTextBox2Valid = $('#password').val().trim() !== "" && !$('#password').hasClass('is-invalid');
                let isTextBox3Valid = $('#captcha').val().trim() !== "" && !$('#captcha').hasClass('is-invalid');


                // If any field is invalid, return false
                if (!isTextBox1Valid || !isTextBox2Valid || !isTextBox3Valid) {
                    return false;
                } else {
                    return true;
                }
            }



            // FORGOT PASSWORD FUNCTIONS
            $('#forgotPasswordButton').click(function() {

                // Setting the value to empty initially
                $('#emailOrUsername').val('');
                $('#emailOrUsername-feedback').text('').removeClass('invalid-feedback');
                $('#emailOrUsername').removeClass('is-invalid');

            });

            // Handle the timer of send code
            let sendTimeout;

            function startSendTimer() {
                let timeLeft = 30;
                sendTimeout = setInterval(function() {
                    $('#sendCodeBtn').prop('disabled', true).text('Try again at ' + timeLeft + 's');
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(sendTimeout);
                        $('#sendCodeBtn').prop('disabled', false).text('Send Code');
                    }
                }, 1000);
            }

            // Handle Send Code button click
            $('#sendCodeBtn').click(function() {
                let _emailOrUsername = $('#emailOrUsername').val();

                // Check feedback messages
                validateEmailOrUsername();

                if (checksendCodeBtn()) {

                    // Disable the button with timer to prevent spam
                    startSendTimer();

                    current = {
                        emailOrUsername: _emailOrUsername
                    };

                    $.ajax({
                        url: 'check_user.php',
                        type: 'POST',
                        data: {
                            emailOrUsername: current.emailOrUsername
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {

                                // Start the resend button timer initially
                                startResendTimer();

                                // Alert message
                                alert(response.message);

                                // If OTP is sent successfully, hide this modal
                                $('#modalForgotPassword').modal('hide');

                                // Set the value empty initially
                                $('#otpCode').val('');
                                $('#otpCode-feedback').text('').removeClass('invalid-feedback');
                                $('#otpCode').removeClass('is-invalid');

                                $('#modalOTP').modal('show');
                            } else {
                                alert(response.message); // Show error message if user not found

                                clearInterval(sendTimeout);
                                $('#sendCodeBtn').prop('disabled', false).text('Send Code');
                            }
                        },
                        error: function() {
                            alert('An error occurred while processing your request.');
                        }
                    });
                }

            });


            // Handle Resend Code button click
            let resendTimeout;

            function startResendTimer() {
                let timeLeft = 30;
                resendTimeout = setInterval(function() {
                    $('#resendCodeBtn').prop('disabled', true).text('Resend Code at ' + timeLeft + 's');
                    timeLeft--;
                    if (timeLeft <= 0) {
                        clearInterval(resendTimeout);
                        $('#resendCodeBtn').prop('disabled', false).text('Resend Code');
                    }
                }, 1000);
            }

            // Handle Resend Code button click
            $('#resendCodeBtn').click(function() {

                startResendTimer();

                $.ajax({
                    url: 'resend_otp.php', // PHP script to handle OTP resending
                    type: 'POST',
                    data: {
                        emailOrUsername: current.emailOrUsername
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            startSendTimer();
                            alert(response.message);

                        } else {
                            alert(response.message);
                            clearInterval(resendTimeout);
                            $('#resendCodeBtn').prop('disabled', false).text('Resend Code');
                        }
                    },
                    error: function() {
                        alert('An error occurred while processing your request.');
                    }
                });
            });


            // Handle Back button click in OTP modal
            $('#backBtn').click(function() {
                if (confirm('You may lost the OTP code. Do you want to proceed?')) {
                    clearInterval(resendTimeout); // Stop the timer
                    $('#resendCodeBtn').prop('disabled', false).text('Resend Code');

                    $('#modalOTP').modal('hide'); // Hide OTP modal
                    $('#modalForgotPassword').modal('show'); // Show the forgot password modal
                }

            });

            // Variable to count OTP submission attempts
            let otpAttemptCounter = 0;

            // Handle Submit OTP button click
            $('#submitOtpBtn').click(function() {
                let otpCode = $('#otpCode').val();

                // Check feedback message
                validateOTP();

                if (checksubmitOtpBtn()) {

                    // Check if attempts are within the limit
                    if (otpAttemptCounter < 5) {

                        $.ajax({
                            url: 'verify_otp.php',
                            type: 'POST',
                            data: {
                                otpCode: otpCode
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.success) {

                                    // Reset the counter upon successful verification
                                    otpAttemptCounter = 0;

                                    $('#modalOTP').modal('hide');

                                    // Setting the value to empty initially
                                    $('#newPassword').val('');
                                    $('#confirmPassword').val('');
                                    $('#newPassword').removeClass('is-invalid');
                                    $('#confirmPassword').removeClass('is-invalid');
                                    $('#changePassword-feedback1').text('').removeClass('invalid-feedback');
                                    $('#changePassword-feedback2').text('').removeClass('invalid-feedback');

                                    // Set input types to password and reset the eye icon
                                    $('#newPassword').attr('type', 'password');
                                    $('#confirmPassword').attr('type', 'password');
                                    $('.change_toggle-password i').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');

                                    // Open modal 3
                                    $('#modalResetPassword').modal('show');
                                } else {
                                    let attemptsLeft = 5 - otpAttemptCounter;
                                    otpAttemptCounter++;

                                    alert(response.message + " You have " + attemptsLeft + " attempts left.");
                                }
                            },
                            error: function() {
                                alert('An error occurred while verifying OTP.');
                            }

                        });

                    } else {
                        // If attempts reach 5, reset counter and switch to the first modal
                        otpAttemptCounter = 0;
                        alert('Maximum OTP attempts reached. Returning to get a new code.');

                        $('#modalOTP').modal('hide'); // Hide OTP modal
                        $('#modalForgotPassword').modal('show'); // Show the forgot password modal
                    }
                }

            });

            // Handle Discard button click in Reset Password modal
            $('#discardBtn').click(function(e) {
                e.preventDefault();
                if (confirm('You are about to cancel the reset password. Do you want to proceed?')) {
                    $('#modalResetPassword').modal('hide');
                }

            });

            // Password Reset Submit
            $('#submitResetBtn').click(function(e) {
                e.preventDefault(); // Prevents default form submission

                validateNewPassword();
                validateConfirmNewPassword();

                if (checkChangePasswordButton()) {
                    $('#changePasswordForm').submit();
                }
            });

            $('#changePasswordForm').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting normally

                const emailOrUsername = current.emailOrUsername;
                const newPassword = $('#newPassword').val();
                const confirmNewPassword = $('#confirmPassword').val();

                if (confirm('Do you want to save changes?')) {
                    $.ajax({
                        url: 'reset_password.php',
                        type: 'POST',
                        data: {
                            email_or_username: emailOrUsername,
                            new_password: newPassword,
                            confirm_new_password: confirmNewPassword
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                alert(response.message); // Show success message

                                $('#modalResetPassword').modal('hide');

                            } else {
                                alert(response.message); // Show error message if unsuccessful
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error); // Log the error for debugging
                            alert('An error occurred. Please try again.');
                        }
                    });
                }
            });


            // FEEDBACK MESSAGE FUNCTION FOR MODAL 1: EMAIL OR USERNAME

            $('#emailOrUsername').on('input', validateEmailOrUsername);

            function validateEmailOrUsername() {
                const emailOrUsername = $('#emailOrUsername').val();

                let feedbackMessage = '';

                $('#emailOrUsername-feedback').text('').removeClass('invalid-feedback');

                if (emailOrUsername === "") {
                    feedbackMessage = 'Email or username cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#emailOrUsername-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#emailOrUsername').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#emailOrUsername').removeClass('is-invalid');
                }
            }

            function checksendCodeBtn() {
                let isTextBoxValid = $('#emailOrUsername').val().trim() !== "" && !$('#emailOrUsername').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isTextBoxValid) {
                    return false;
                } else {
                    return true;
                }
            }


            // FEEDBACK MESSAGE FUNCTION FOR MODAL 2: OTP

            $('#otpCode').on('input', validateOTP);

            function validateOTP() {
                const otpCode = $('#otpCode').val();

                let feedbackMessage = '';

                $('#otpCode-feedback').text('').removeClass('invalid-feedback');

                if (otpCode === "") {
                    feedbackMessage = 'OTP code cannot be empty.';
                }

                if (feedbackMessage) {
                    $('#otpCode-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#otpCode').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#otpCode').removeClass('is-invalid');
                }
            }

            function checksubmitOtpBtn() {
                let isTextBoxValid = $('#otpCode').val().trim() !== "" && !$('#otpCode').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isTextBoxValid) {
                    return false;
                } else {
                    return true;
                }
            }




            // FEEDBACK MESSAGE FUNCTIONS FOR MODAL 3: CHANGE PASSWORD

            $('#newPassword').on('input', validateNewPassword);
            $('#confirmPassword').on('input', validateConfirmNewPassword);

            function validateNewPassword() {
                const password = $('#newPassword').val();
                let feedbackMessage = '';

                $('#changePassword-feedback1').text('').removeClass('invalid-feedback');

                if (password === "") {
                    feedbackMessage = 'New password cannot be empty.';
                } else if (password.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                }

                if (feedbackMessage) {
                    $('#changePassword-feedback1').text(feedbackMessage).addClass('invalid-feedback');
                    $('#newPassword').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#newPassword').removeClass('is-invalid');
                }
            }

            function validateConfirmNewPassword() {
                const password = $('#newPassword').val();
                const confirmPassword = $('#confirmPassword').val();
                let feedbackMessage = '';

                $('#changePassword-feedback2').text('').removeClass('invalid-feedback');

                if (confirmPassword === "") {
                    feedbackMessage = 'Please confirm your new password.';
                } else if (confirmPassword.length < 8) {
                    feedbackMessage = 'Password must be at least 8 characters long.';
                } else if (password !== confirmPassword) {
                    feedbackMessage = 'Passwords do not match.';
                }

                if (feedbackMessage) {
                    $('#changePassword-feedback2').text(feedbackMessage).addClass('invalid-feedback');
                    $('#confirmPassword').addClass('is-invalid'); // Mark input as invalid
                } else {
                    $('#confirmPassword').removeClass('is-invalid');
                }
            }

            function checkChangePasswordButton() {
                let isNewPasswordValid = $('#newPassword').val().trim() !== "" && !$('#newPassword').hasClass('is-invalid');
                let isConfirmNewPasswordValid = $('#confirmPassword').val().trim() !== "" && !$('#confirmPassword').hasClass('is-invalid');

                // If any field is invalid, return false
                if (!isNewPasswordValid || !isConfirmNewPasswordValid) {
                    return false;
                } else {
                    return true;
                }
            }

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>