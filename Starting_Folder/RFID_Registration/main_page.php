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
            height: auto;
            max-width: 400px;
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
    </style>

</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-12 text-center">
                <img id="profileImg" src="../../Image/logo_and_icons/default_avatar.png" alt="Profile Image" class="img-thumbnail">
                <div id="profileImg-feedback" class="invalid-feedback" style="display: block;"> <!-- Message will display here --> </div>
                <div class="row justify-content-center">
                    <div class="col-md-6 col-sm-12 text-center">
                        <button class="btn btn-primary mt-2 w-100" data-bs-toggle="modal" data-bs-target="#uploadModal">Upload Picture</button>
                    </div>
                    <div class="col-md-6 col-sm-12 text-center">
                        <button class="btn btn-danger mt-2 w-100" id="removePicBtn">Remove Picture</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 form-container">
                <form id="profileForm">

                    <!-- First Name -->
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="first_name" required>
                        <div id="firstName-feedback" class="invalid-feedback" style="display: block;"></div>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="last_name" required>
                        <div id="lastName-feedback" class="invalid-feedback" style="display: block;"></div>
                    </div>

                    <!-- Type of profile -->
                    <div class="mb-3">
                        <label for="profileType" class="form-label">Type of Profile</label>
                        <select class="form-select" id="profileType" name="type_of_profile" required>
                            <option value="OJT">On-the-job training</option>
                            <option value="CFW">Cash for Work</option>
                            <option value="EMPLOYEE">Employee</option>
                        </select>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-secondary flex-fill" id="discardBtn">BACK</button>
                        <button type="button" class="btn btn-success flex-fill" id="saveBtn">REGISTER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for Picture Upload -->
    <div class="modal fade" id="uploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload and Crop Picture</h5>
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
                    <button type="button" class="btn btn-secondary" id="cancelCrop">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveCrop">Save Crop</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        $(document).ready(function() {

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

            $('#saveCrop').click(function() {
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

            $('#discardBtn').click(function() {

                window.location.href = '../Landing_page/index.php';
            });

            $('#cancelCrop').click(function() {

                $('#uploadModal').modal('hide');

                // Destroy the cropper instance
                if (cropper) {
                    cropper.destroy();
                    cropper = null; // Set cropper to null after destroying it
                    $('#imageToCrop').attr('src', '').hide(); // Clear and hide the cropped image
                    $('#fileInput').val(''); // Clear the file input               
                }
            });

            $('#removePicBtn').click(function() {
                // go back to landing page
                if (croppedImage) {
                    if (confirm('Are you sure you want to remove the image?')) {
                        $('#profileImg').attr('src', '../../Image/logo_and_icons/default_avatar.png');
                        croppedImage = null;
                    }
                }
            });


            $('#saveBtn').click(function() {

                // Check feedback messages
                validateFirstName();
                validateLastName();
                validateImageUpload();
                if (checksaveBtn()) {
                    // Trigger the login form
                    $('#profileForm').submit();
                }
            });

            $('#profileForm').submit(function(e) {
                e.preventDefault(); // Prevent the default form submission
                if (confirm('Are you sure you want to save this profile?')) {
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
                            // Check if the response indicates success
                            if (response.success) {
                                alert(response.message); // Show success message

                                // Head to main menu to avoid double sending the request
                                window.location.href = '/TAPNLOG/Starting_Folder/Landing_page/index.php';

                                // $('#profileForm')[0].reset(); // Reset the form fields
                                // $('#profileImg').attr('src', '../../Image/logo_and_icons/default_avatar.png'); // Reset the profile image
                                // croppedImage = null;


                            } else {
                                alert(response.message); // Show error message
                            }
                        },
                        error: function() {
                            alert("An error occurred while saving the profile."); // General error message
                        }
                    });
                }
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

            function checksaveBtn() {
                let isFnameValid = $('#firstName').val().trim() !== "" && !$('#firstName').hasClass('is-invalid');
                let isLnameValid = $('#lastName').val().trim() !== "" && !$('#lastName').hasClass('is-invalid');
                let isImageValid = croppedImage !== null && !$('#profileImg').hasClass('is-invalid-image');

                // If any field is invalid, return false
                if (!isFnameValid || !isLnameValid || !isImageValid) {
                    return false;
                } else {
                    return true;
                }
            }

            // Function for dynamic discard/back button
            function checkDiscardBtn() {
                let isImageValid = croppedImage !== null; // Check if a cropped image exists
                let isFirstNameValid = $('#firstName').val().trim() !== "" && !$('#firstName').hasClass('is-invalid');
                let isLastNameValid = $('#lastName').val().trim() !== "" && !$('#lastName').hasClass('is-invalid');

                if (isImageValid || isFirstNameValid || isLastNameValid) {
                    // Change the button to "DISCARD" with a confirmation message
                    $('#discardBtn').text('DISCARD');
                    $('#discardBtn').removeClass('btn-secondary').addClass('btn-danger');
                    $('#discardBtn').off('click').on('click', function() {
                        if (confirm('Are you sure you want to discard changes?')) {
                            window.location.href = '../Landing_page/index.php';
                        }
                    });
                } else {
                    // Change the button to "BACK" without a confirmation message
                    $('#discardBtn').text('BACK');
                    $('#discardBtn').removeClass('btn-danger').addClass('btn-secondary');
                    $('#discardBtn').off('click').on('click', function() {
                        window.location.href = '../Landing_page/index.php';
                    });
                }
            }


            $('#firstName, #lastName').on('input change', checkDiscardBtn);
            $('#removePicBtn').on('click', checkDiscardBtn);
            $('#saveCrop').on('click', checkDiscardBtn);
            $('#cancelCrop').on('click', checkDiscardBtn);
            $('#saveBtn').on('click', checkDiscardBtn);


        });
    </script>
</body>

</html>