<?php
session_start();

if (isset($_SESSION['record_guard_logged']) || isset($_SESSION['vehicle_guard_logged']) || isset($_SESSION['admin_logged'])) {
    header("Location: /TAPNLOG/Starting_Folder/Landing_page/index.php");
    exit();
}

if (!isset($_SESSION['directory']) || !isset($_SESSION['ip_address']) || !isset($_SESSION['website_link'])) {
    header("Location: /tapnlog/index.php");
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
            border: 1px solid #ddd;
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
            /* Bright blue for better visibility */
            text-decoration: underline;
            /* Make it clear it's a link */
            font-weight: bold;
            /* Emphasize the link */
            transition: color 0.3s ease;
            /* Smooth transition on hover */
        }

        #viewTerms:hover {
            color: #0056b3;
            /* Slightly darker blue on hover for contrast */
            text-decoration: none;
            /* Remove underline on hover for effect */
        }

        /* Ensure the SweetAlert2 modal content is scrollable on mobile */
        .swal2-container .swal2-html-container {
            max-height: 300px;
            /* Adjust as necessary */
            overflow-y: auto;
        }
    </style>

</head>

<body>

    <div class="container mt-4">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-5 col-md-6 p-0 mb-3 mb-md-4 ms-md-5">
                <div class="row d-flex justify-content-center align-items-center p-0 m-0">
                    <div class="col-lg-2 col-md-2 col-sm-12 d-flex justify-content-center justify-content-md-end align-items-center">
                        <img src="/TAPNLOG/Image/LOGO_AND_ICONS/logo_icon.png" id="img_logo" class="img-fluid" alt="Logo" style="max-width: 60px; height: auto;">
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-12 d-flex justify-content-center justify-content-md-start align-items-center p-0 m-0">
                        <h2>RFID REGISTRATION</h2>
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
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#uploadModal">UPLOAD IMAGE</button>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center">
                        <button class="btn btn-danger mt-2 w-100" id="removePicBtn">REMOVE IMAGE</button>
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
                            <div id="firstName-feedback" class="invalid-feedback" style="display: block;"></div>
                        </div>

                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="lastName" class="form-label"><strong>LAST NAME</strong></label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                            <div id="lastName-feedback" class="invalid-feedback" style="display: block;"></div>
                        </div>

                        <!-- Type of profile -->
                        <div class="mb-3">
                            <label for="profileType" class="form-label"><strong>TYPE OF PROFILE</strong></label>
                            <select class="form-select" id="profileType" name="type_of_profile" required>
                                <option value="OJT">On-the-job training</option>
                                <option value="CFW">Cash for Work</option>
                                <option value="EMPLOYEE">Employee</option>
                            </select>
                        </div>

                        <!-- Terms and condition -->
                        <div class="mb-3 form-check">
                            <div class="container-fluid d-flex justify-content-center p-0 m-0">
                                <div class="row p-0 m-0">
                                    <div class="col-12 p-0">
                                        <input type="checkbox" class="form-check-input" id="agreeTerms" disabled>
                                        <label class="form-check-label" for="agreeTerms">
                                            I agree to the <a href="#" id="viewTerms">Terms and Conditions</a>.
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-secondary flex-fill" id="discardBtn">BACK</button>
                            <button type="button" class="btn btn-success flex-fill" id="saveBtn">REGISTER</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" id="cancelCrop">CANCEL</button>
                    <button type="button" class="btn btn-primary" id="saveCrop">UPLOAD</button>
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
                        reverseButtons: true
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
                    html: ` <div style="text-align: left; -webkit-overflow-scrolling: touch; touch-action: pan-y;">
                            <p>Welcome to our service! By using this service, you agree to the following terms and conditions:</p>
                            <ul>
                                <li>You shall comply with all applicable laws and regulations.</li>
                                <li>Do not misuse the platform or services.</li>
                                <li>All content uploaded must be your original work or have proper authorization.</li>
                                <li>We reserve the right to terminate accounts violating these terms.</li>
                                <li>Your data will be handled per our privacy policy.</li>
                            </ul>
                            <p><strong>Scroll to the bottom to accept.</strong></p>
                        </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'ACCEPT',
                    cancelButtonText: 'CANCEL',
                    reverseButtons: true,
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
                            confirmButtonText: 'YES, DISCARD CHANGES',
                            cancelButtonText: 'NO, KEEP EDITING',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                showAlert("Thank you!", "success"); // Show success message
                                setTimeout(() => {
                                    window.location.href = '/TAPNLOG/Starting_Folder/Landing_page/index.php';
                                }, 1000);
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
                    reverseButtons: true
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