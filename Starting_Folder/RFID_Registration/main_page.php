<?php
session_start();

if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged']) || isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (!isset($_SESSION['directory']) || !isset($_SESSION['ip_address']) || !isset($_SESSION['website_link'])) {
    header("Location: /TAPNLOG/index.php");
    exit();
}
?>

<!DOCTYPE html>
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

    <!-- QR Code Library -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

    <!-- Cropper.js CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">

    <title>RFID Profile Registration</title>
    <style>
        #profileImg {
            width: 100%;
            max-width: 350px;
            aspect-ratio: 1 / 1;
            border: 3px solid #ddd;
            object-fit: cover;
        }

        .form-container {
            padding-top: 1rem;
        }

        .is-invalid-image {
            border: 2px solid red !important;
            /* Add a red border for invalid image */
        }

        .text-color_ {
            color: #1877f2;
        }

        /* Style for the Terms and Conditions link */
        #viewTerms {
            color: #007bff;
            text-decoration: underline;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        #viewTerms:hover {
            color: #0056b3;
            text-decoration: none;
        }

        /* Ensure the SweetAlert2 modal content is scrollable on mobile */
        .swal2-container .swal2-html-container {
            max-height: 350px;
            overflow-y: auto;
        }



        body {
            background: url('/tapnlog/image/logo_and_icons/bsu-bg.png') no-repeat center center fixed;
            background-size: cover;
        }

        .glass {
            background: rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.10);
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

        .page-title {
            color: #1877f2;
            font-weight: 750;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* ANIMATION */
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
    </style>

</head>

<body>

    <div class="container glass p-md-3 my-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-5 col-md-6 p-0 mb-3 mb-md-4 ms-md-5">
                <div class="row d-flex justify-content-center align-items-center p-0 m-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 p-0 d-flex justify-content-center justify-content-md-end align-items-center">
                        <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" id="img_logo" class="img-fluid" alt="Logo" style="max-width: 60px; height: auto;">
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 d-flex justify-content-center justify-content-md-start align-items-center p-0 m-0">
                        <h2 class="page-title">RFID REGISTRATION</h2>
                    </div>
                </div>
            </div>

            <hr>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                <img id="profileImg" src="../../Image/logo_and_icons/default_avatar.png" alt="Profile Image" class="img-thumbnail">
                <div id="profileImg-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-12 text-center">
                        <button class="btn btn-primary btn-custom mt-2 w-100" data-bs-toggle="modal" data-bs-target="#uploadModal">UPLOAD IMAGE</button>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center">
                        <button class="btn btn-danger btn-custom mt-2 w-100" id="removePicBtn">REMOVE IMAGE</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 form-container d-flex align-items-center">
                <div class="row p-0 m-0 w-100 h-100">
                    <form id="profileForm" class="d-flex flex-column justify-content-between">

                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="firstName" class="form-label"><strong>FIRST NAME</strong></label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                            <div id="firstName-feedback" class="invalid-feedback"></div>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="lastName" class="form-label"><strong>LAST NAME</strong></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                            <div id="lastName-feedback" class="invalid-feedback"></div>
                        </div>

                        <!-- Type of profile -->
                        <div class="mb-3">
                            <label for="profileType" class="form-label"><strong>TYPE OF PROFILE</strong></label>
                            <select class="form-select" id="profileType" name="type_of_profile" required>
                                <option value="EMPLOYEE">EMPLOYEE</option>
                                <option value="OJT">ON THE JOB TRAINEE</option>
                                <option value="CFW">CASH FOR WORK</option>
                            </select>
                        </div>

                        <!-- Terms and condition -->
                        <div class="mb-3 form-check">
                            <div class="container-fluid d-flex justify-content-center p-0 m-0">
                                <div class="row p-0 m-0">
                                    <div class="col-12 p-0">
                                        <input type="checkbox" class="form-check-input" id="agreeTerms" disabled>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#" id="viewTerms"><span class="up">Terms and Conditions</span></a>.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-100 mt-3">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-6 mb-2">
                                    <button id="discardBtn" type="button" class="btn btn-secondary btn-custom text-uppercase w-100">BACK</button>
                                </div>
                                <div class="col-6 mb-2">
                                    <button id="saveBtn" type="button" class="btn btn-success btn-custom text-uppercase w-100">REGISTER</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal for Picture Upload -->
    <div class="modal fade" id="uploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">CROP AND UPLOAD IMAGE</h5>
                    <button type="button" class="btn-close up" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" id="fileInput" accept="image/*" class="form-control mb-3">
                    <div class="d-flex justify-content-center">
                        <div style="max-width: 100%; max-height: 70vh; overflow: hidden; position: relative;">
                            <img id="imageToCrop" src="#" alt="Image for cropping" style="display: none; width: 100%; height: auto; max-height: 100%;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <div class="w-100 mt-3">
                        <div class="row d-flex justify-content-center align-items-center">
                            <div class="col-6 mb-2">
                                <button id="cancelCrop" class="btn btn-danger btn-custom text-uppercase w-100">CANCEL</button>
                            </div>
                            <div class="col-6 mb-2">
                                <button id="saveCrop" class="btn btn-success btn-custom text-uppercase w-100">UPLOAD</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#firstName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            // Add event listener for the last name input
            $('#lastName').on('input', function() {
                $(this).val($(this).val().toUpperCase()); // Convert to uppercase
            });

            let cropper;
            let croppedImage = null;

            // Initialize Cropper
            $('#fileInput').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imageToCrop').attr('src', e.target.result).show();
                        if (cropper) cropper.destroy();
                        cropper = new Cropper(document.getElementById('imageToCrop'), {
                            aspectRatio: 1,
                            viewMode: 1,
                            movable: true,
                            zoomable: true,
                            scalable: true,
                            rotatable: true,
                            cropBoxMovable: true,
                            cropBoxResizable: true,

                        });
                    };
                    reader.readAsDataURL(file);
                }
            });

            $('#saveCrop').on('click', function() {
                if (cropper) {
                    const canvas = cropper.getCroppedCanvas({
                        width: 600,
                        height: 600
                    });
                    croppedImage = canvas.toDataURL('image/png');
                    $('#profileImg').attr('src', croppedImage);

                    // Destroy the cropper instance
                    cropper.destroy();
                    cropper = null; // Set cropper to null after destroying it
                    $('#imageToCrop').attr('src', '').hide(); // Clear and hide the cropped image
                    $('#fileInput').val(''); // Clear the file input

                    $('#uploadModal').modal('hide');

                    validateImageUpload(); // Validating input for feedback-message
                }
            });

            $('#cancelCrop').on('click', function() {

                $('#uploadModal').modal('hide');

                // Destroy the cropper instance
                if (cropper) {
                    cropper.destroy();
                    cropper = null; // Set cropper to null after destroying it
                    $('#imageToCrop').attr('src', '').hide(); // Clear and hide the cropped image
                    $('#fileInput').val(''); // Clear the file input               
                }
            });

            $('#removePicBtn').on('click', function() {
                // go back to landing page
                if (croppedImage) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to remove the uploaded image?',
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
                            $('#profileImg').attr('src', '../../Image/logo_and_icons/default_avatar.png');
                            croppedImage = null;
                            checkDiscardBtn();
                        }
                    });
                } else {
                    showAlert("No image is available for removal.", "error");
                }
            });


            $('#saveBtn').on('click', function() {

                // Check feedback messages
                validateFirstName();
                validateLastName();
                validateImageUpload();
                validateTnC();
                if (checksaveBtn()) {
                    // Trigger the login form
                    $('#profileForm').submit();
                }
            });

            $('#profileForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission

                showConfirmation('Do you want to register this profile?', function() {

                    Swal.fire({
                        title: 'Saving Changes...',
                        text: 'Please wait while we update the profile.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                    });

                    $.ajax({
                        url: 'upload_profile.php', // The server endpoint to handle the request
                        type: 'POST', // Method type
                        data: {
                            first_name: $('#firstName').val(),
                            last_name: $('#lastName').val(),
                            type_of_profile: $('#profileType').val(),
                            profile_img: croppedImage ? croppedImage : ''
                        },
                        dataType: 'json', // Expecting a JSON response
                        success: function(response) {
                            Swal.close();
                            if (response.success) {

                                showAlert(response.message, "success"); // Show success message
                                setTimeout(() => {
                                    window.location.href = '/TAPNLOG/Starting_Folder/Landing_page/index.php';
                                }, 1000);

                            } else {
                                showAlert(response.message, "error");
                            }
                        },
                        error: function() {
                            Swal.close();
                            showAlert("An error occurred while saving the profile.", "error");
                        }
                    });

                });
            });

            $('#viewTerms').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Terms and Conditions',
                    html: `
                        <div style="text-align: left; -webkit-overflow-scrolling: touch; touch-action: pan-y; padding: 20px;">
                            <h3 style="color: #2c3e50; margin-bottom: 15px; font-weight: 600;">Introduction</h3>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">Welcome to the RFID Registration System. This system is designed to streamline the registration and monitoring processes for Employees, Cash for Work staff, and On the Job Trainees (collectively referred to as "Users"). By accessing and using this system, you acknowledge and agree to the following Terms and Conditions governing the collection, use, and safeguarding of your personal data. These terms are intended to ensure transparency, compliance, and mutual understanding between the system administrators and its users. If you disagree with any part of these terms, we advise you to refrain from registering or using the system.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Data Collection and Usage</h3>
                            <p style="margin-bottom: 15px; line-height: 1.6; text-align: justify;">During registration, the RFID Registration System collects and stores specific information to facilitate its functions effectively. The collected data includes personal details such as full name, type of profile, and a profile photo for identification purposes.</p>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">The collected information is used solely to generate and issue RFID cards for identification and monitoring, record attendance, work hours, and facility access, and ensure organizational security and operational efficiency. By registering, you also grant permission for the use of your profile photo, type of profile, and name for identification purposes and internal records management.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Data Protection and Privacy</h3>
                            <p style="margin-bottom: 15px; line-height: 1.6; text-align: justify;">We are committed to safeguarding your personal data and ensuring its security. To this end, reasonable technical and organizational measures are in place to protect your information from unauthorized access, disclosure, or misuse. Your personal data will remain confidential and will only be shared with authorized personnel or third parties in compliance with Philippine laws and regulations or in response to requests from government authorities or law enforcement agencies.</p>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">Your data will be retained for the duration of your employment, program participation, or training. Once your engagement concludes, your information will be archived in accordance with our data retention policy and applicable legal requirements.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">User Responsibilities</h3>
                            <p style="margin-bottom: 15px; line-height: 1.6; text-align: justify;">As a user of the RFID Registration System, you are expected to adhere to the following responsibilities. It is your obligation to provide accurate and up-to-date information during registration, as any false or misleading data may lead to the rejection of your registration or result in disciplinary action.</p>
                            <p style="margin-bottom: 15px; line-height: 1.6; text-align: justify;">The RFID issued to you is strictly for personal use and must not be shared, transferred, or tampered with. You are responsible for its safekeeping and must promptly report any loss, theft, or damage to the system administrator. In the event of a lost RFID card, you will be required to pay for its replacement in accordance with the system's policies.</p>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">Additionally, users are strictly prohibited from engaging in unauthorized activities such as using the RFID for unauthorized access, fraudulent purposes, or attempting to manipulate or interfere with the system's functionality.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Limitation of Liability</h3>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">While every effort is made to ensure the reliability and security of the RFID system, uninterrupted operation and error-free performance cannot be guaranteed. The organization is not liable for any damages or losses arising from system malfunctions, user negligence, or unauthorized use of the RFID.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Amendments</h3>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">We reserve the right to amend or update these Terms and Conditions at any time. Any changes will be communicated to users, and continued use of the RFID system will indicate your acceptance of the revised terms.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Governing Law and Jurisdiction</h3>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">These Terms and Conditions are governed by the laws of the Republic of the Philippines. Any disputes related to the RFID system shall fall under the jurisdiction of the courts located in Batangas City.</p>

                            <h3 style="color: #2c3e50; margin: 25px 0 15px; font-weight: 600;">Contact Information</h3>
                            <p style="margin-bottom: 10px; line-height: 1.6; text-align: justify;">For any inquiries, concerns, or assistance, please reach out to us through the following contact details:</p>
                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: left; color: #3498db;">Email: tapnlog.official@gmail.com</p>

                            <p style="margin-bottom: 20px; line-height: 1.6; text-align: justify;">By completing the RFID registration process, you acknowledge that you have read, understood, and agreed to these Terms and Conditions. To proceed, you must scroll to the bottom of this document and click the "Accept" button, which will automatically check the Terms and Conditions box as confirmation of your acceptance.</p>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'ACCEPT',
                    cancelButtonText: 'CANCEL',
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                        cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                    },
                    buttonsStyling: false,
                    didOpen: () => {
                        const container = document.querySelector('.swal2-html-container');
                        const confirmButton = Swal.getConfirmButton();

                        // Disable confirm button initially
                        confirmButton.disabled = true;

                        // Add scroll event listener
                        container.addEventListener('scroll', () => {
                            const isScrolledToBottom =
                                container.scrollHeight - container.scrollTop <= container.clientHeight + 1; // Account for rounding

                            // Enable confirm button if scrolled to the bottom
                            confirmButton.disabled = !isScrolledToBottom;
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#agreeTerms').prop('checked', true); // Enable checkbox if accepted
                    } else {
                        $('#agreeTerms').prop('checked', false); // Uncheck if canceled
                    }

                    // Trigger validation
                    validateTnC();
                    checkDiscardBtn();
                });
            });


            // FEEDBACK MESSAGE FUNCTION FOR NAME
            $('#firstName').on('input', validateFirstName);
            $('#lastName').on('input', validateLastName);

            function validateFirstName() {
                const firstName = $('#firstName').val();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;
                let feedbackMessage = '';

                $('#firstName-feedback').text('').removeClass('invalid-feedback');

                if (firstName === "") {
                    feedbackMessage = 'First name cannot be empty.';
                } else if (!nameRegex.test(firstName)) {
                    feedbackMessage = 'First name can only contain letters, dots, hyphens, apostrophes, and spaces.';
                }

                if (feedbackMessage) {
                    $('#firstName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#firstName').addClass('is-invalid');
                } else {
                    $('#firstName').removeClass('is-invalid');
                }
            }

            function validateLastName() {
                const lastName = $('#lastName').val();
                const nameRegex = /^[A-Za-z.\-'\s]+$/;
                let feedbackMessage = '';

                $('#lastName-feedback').text('').removeClass('invalid-feedback');

                if (lastName === "") {
                    feedbackMessage = 'Last name cannot be empty.';
                } else if (!nameRegex.test(lastName)) {
                    feedbackMessage = 'Last name can only contain letters, dots, hyphens, apostrophes, and spaces.';
                }

                if (feedbackMessage) {
                    $('#lastName-feedback').text(feedbackMessage).addClass('invalid-feedback');
                    $('#lastName').addClass('is-invalid');
                } else {
                    $('#lastName').removeClass('is-invalid');
                }
            }

            // FEEDBACK MESSAGE FUNCTION FOR IMAGE UPLOAD

            function validateImageUpload() {
                $('#profileImg-feedback').text('').removeClass('invalid-feedback');

                if (!croppedImage) { // Check if image is uploaded
                    $('#profileImg-feedback').text('Please upload a profile picture.').addClass('invalid-feedback');
                    $('#profileImg').addClass('is-invalid-image'); // Mark image upload as invalid
                } else {
                    $('#profileImg').removeClass('is-invalid-image'); // Remove invalid mark if image is uploaded
                }
            }

            // FEEDBACK MESSAGE FUNCTION FOR TNC
            function validateTnC() {
                const isChecked = $('#agreeTerms').is(':checked');

                // Resetting the validation state
                $('#agreeTerms').removeClass('is-invalid');
                $('label[for="agreeTerms"]').removeClass('is-invalid');

                if (!isChecked) {
                    $('#agreeTerms').addClass('is-invalid');
                    $('label[for="agreeTerms"]').addClass('is-invalid');
                }
            }

            function checksaveBtn() {
                let isFnameValid = $('#firstName').val().trim() !== "" && !$('#firstName').hasClass('is-invalid');
                let isLnameValid = $('#lastName').val().trim() !== "" && !$('#lastName').hasClass('is-invalid');
                let isImageValid = croppedImage !== null && !$('#profileImg').hasClass('is-invalid-image');
                let isTnCValid = $('#agreeTerms').is(':checked');

                // If any field is invalid, return false
                if (!isFnameValid || !isLnameValid || !isImageValid || !isTnCValid) {
                    return false;
                } else {
                    return true;
                }
            }

            // Function for dynamic discard/back button
            function checkDiscardBtn() {
                let isFnameValid = $('#firstName').val().trim() !== "" && !$('#firstName').hasClass('is-invalid');
                let isLnameValid = $('#lastName').val().trim() !== "" && !$('#lastName').hasClass('is-invalid');
                let isImageValid = croppedImage !== null && !$('#profileImg').hasClass('is-invalid-image');
                let isTnCValid = $('#agreeTerms').is(':checked');

                $('#discardBtn').off('click');

                if (isImageValid || isFnameValid || isLnameValid || isTnCValid) {
                    // Change the button to "DISCARD" with a confirmation message
                    $('#discardBtn').text('DISCARD');
                    $('#discardBtn').removeClass('btn-secondary').addClass('btn-danger');
                    $('#discardBtn').on('click', function() {
                        Swal.fire({
                            title: 'Unsaved Changes Detected',
                            text: 'You have unsaved changes. Are you sure you want to DISCARD the changes?',
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

                                window.location.href = '/TAPNLOG/Starting_Folder/Landing_page/index.php';

                            }
                        });

                    });
                } else {
                    // Change the button to "BACK" without a confirmation message
                    $('#discardBtn').text('BACK');
                    $('#discardBtn').removeClass('btn-danger').addClass('btn-secondary');
                    $('#discardBtn').on('click', function() {

                        window.location.href = '/TAPNLOG/Starting_Folder/Landing_page/index.php';

                    });
                }
            }

            checkDiscardBtn();
            $('#firstName, #lastName').on('input change', checkDiscardBtn);
            $('#saveCrop').on('click', checkDiscardBtn);
            $('#cancelCrop').on('click', checkDiscardBtn);
            $('#saveBtn').on('click', checkDiscardBtn);



            function showAlert(message, type = "error") {
                Swal.fire({
                    position: "top",
                    title: type === "success" ? 'Success!' : 'Error!',
                    text: message,
                    icon: type,
                    timer: 1000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }

            function showConfirmation(message, callback, _icon = 'question', confirmText = 'YES', cancelText = 'NO') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: message,
                    icon: _icon,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true,
                    customClass: {
                        confirmButton: 'col-5 btn btn-success btn-custom text-uppercase',
                        cancelButton: 'col-5 btn btn-danger btn-custom text-uppercase',
                    },
                    buttonsStyling: false,

                }).then((result) => {
                    if (result.isConfirmed) {
                        callback(); // Execute the callback function if confirmed
                    }
                });
            }

        });
    </script>
</body>

</html>