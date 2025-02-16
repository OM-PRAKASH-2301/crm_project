<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <title>Customer Relationship Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .crm-heading {
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 15px;
            border-radius: 5px;
        }
        .toggle-buttons {
            text-align: right;
            margin-bottom: 15px;
        }
        .ajax-loader {
            display: none; /* Hide initially */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }

    </style>
</head>
<body>
    <div class="container mt-4">
        <h2 class="crm-heading">Customer Relationship Management</h2>

        <div class="toggle-buttons">
            <button class="btn btn-primary" id="addContactBtn">Add New Contact</button>
            <button class="btn btn-secondary" id="showContactBtn" style="display: none;">Show Contacts</button>
        </div>

        <div id="contentBox" class="card p-4 mb-4">
            <!-- Contact List Section -->
            <div id="contactSection">
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <input type="text" id="searchName" class="form-control" placeholder="Search by Name">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="searchEmail" class="form-control" placeholder="Search by Email">
                    </div>
                    <div class="col-md-3">
                        <input type="text" id="searchPhone" class="form-control" placeholder="Search by Phone">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" id="searchBtn">Search</button>
                    </div>
                </div>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="contactTableBody">
                        <!-- Contacts will be dynamically loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Contact Form Section (Initially Hidden) -->
            <div id="contactFormContainer" style="display: none;">
                <form id="contactForm" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label>Name:</label>
                            <input type="text" name="name" class="form-control">
                            <div class="error-message text-danger"></div> <!-- Name error -->
                        </div>

                        <div class="col-md-4">
                            <label>Email:</label>
                            <input type="text" name="email" class="form-control">
                            <div class="error-message text-danger"></div> <!-- Email error -->
                        </div>

                        <div class="col-md-4">
                            <label>Phone:</label>
                            <input type="text" name="phone" class="form-control" id="phoneInput" maxlength="10">
                            <div class="error-message text-danger"></div> <!-- Phone error -->
                        </div>

                        <div class="col-md-4">
                            <label>Gender:</label>
                            <div class="d-flex align-items-center">
                                <div class="form-check me-4">
                                    <input type="radio" id="male" name="gender" value="Male" class="form-check-input">
                                    <label for="male" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check me-4">
                                    <input type="radio" id="female" name="gender" value="Female" class="form-check-input">
                                    <label for="female" class="form-check-label">Female</label>
                                </div>
                                <div class="form-check me-4">
                                    <input type="radio" id="other" name="gender" value="Other" class="form-check-input">
                                    <label for="other" class="form-check-label">Other</label>
                                </div>
                            </div>
                            <div id="genderError" class="error-message text-danger"></div> <!-- Single Gender error -->
                        </div>

                        <div class="col-md-4">
                            <label>Profile Image:</label>
                            <input type="file" name="profile_image" class="form-control">
                            <div class="error-message text-danger"></div> <!-- Profile image error -->
                        </div>

                        <div class="col-md-4">
                            <label>Additional File:</label>
                            <input type="file" name="additional_file" class="form-control">
                            <div class="error-message text-danger"></div> <!-- Additional file error -->
                        </div>
                    </div>

                    <div id="customFields" class="row g-3 mt-3"></div>

                    <button type="button" id="addCustomField" class="btn btn-secondary mt-3">Add Custom Field</button>
                    <button type="submit" class="btn btn-success mt-3">Save Contact</button>
                </form>
            </div>


        </div>
    </div>
    <div id="ajaxLoader" class="ajax-loader">
    <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function showLoader() {
                $('#ajaxLoader').show();
            }

            function hideLoader() {
                $('#ajaxLoader').hide();
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
            loadContacts();
            function loadContacts() {
                $.ajax({
                    url: '/contacts/list',
                    type: 'GET',
                    success: function(response) {
                        $('#contactTableBody').html(response);
                    },
                    error: function(xhr) {
                        alert('Failed to load contacts.');
                    }
                });
            }

            // Load contacts when the page loads

            // Show Add Contact Form and Hide Contact List
            $('#addContactBtn').click(function() {
                $('#contactSection').hide();
                $('#contactFormContainer').show();
                $('#showContactBtn').show();
                $('#addContactBtn').hide();
            });

            // Show Contact List and Hide Add Contact Form
            $('#showContactBtn').click(function() {
                $('#contactFormContainer').hide();
                $('#contactSection').show();
                $('#showContactBtn').hide();
                $('#addContactBtn').show();
            });

            // Function to Add Custom Field
            $('#addCustomField').click(function() {
                let lastField = $('#customFields input[name="custom_fields[]"]').last();
                
                // Check if last custom field is empty
                if (lastField.length > 0 && lastField.val().trim() === "") {
                    alert("Please fill the previous custom field before adding a new one.");
                    return;
                }

                // Create the new input field
                let fieldHtml = `
                    <div class="mb-3 custom-field">
                        <input type="text" name="custom_fields[]" class="form-control" placeholder="Enter custom field">
                    </div>`;

                // Append the new custom field **right after the additional file input box**
                $('#customFields').append(fieldHtml);
            });

            // Search Functionality (On Button Click)
            $('#searchBtn').click(function() {
                showLoader(); 
                let name = $('#searchName').val();
                let phone = $('#searchPhone').val();
                let email = $('#searchEmail').val();

                $.ajax({
                    url: '/contacts/search',
                    type: 'GET',
                    data: { name: name, phone: phone, email: email },
                    success: function(data) {
                        hideLoader();
                        $('#contactTableBody').html(data);
                    }
                });
            });

            $('#contactForm').submit(function (e) {
                e.preventDefault();
                showLoader();
                let formData = new FormData(this);

                $('.error-message').text('');

                $.ajax({
                    url: '/contacts/store',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        hideLoader();
                        if (response.status == 'Success') {
                            alert(response.message);
                            $('#contactForm')[0].reset();
                            $('#contactFormContainer').hide();
                            $('#contactSection').show();
                        } else {
                            alert('Unexpected error occurred.');
                        }
                    },
                    error: function (xhr) {
                        hideLoader();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (field, messages) {
                                if (field === 'gender') {
                                    $('#genderError').text(messages[0]); // Show error only once
                                } else {
                                    let inputField = $('[name="' + field + '"]');
                                    if (inputField.length) {
                                        inputField.closest('div').find('.error-message').text(messages[0]);
                                    }
                                }
                            });
                        } else {
                            alert('Error: ' + (xhr.responseJSON.message || 'Something went wrong.'));
                        }
                    }
                });
            });

            // Remove error message when the user starts typing
            $(document).on('input', '.form-control', function () {
                $(this).closest('div').find('.error-message').text('');
            });

            // Remove gender error when selecting an option
            $(document).on('change', '.form-check-input', function () {
                $('#genderError').text('');
            });

            // Allow only numbers in the phone input
            $('#phoneInput').on('input', function () {
                this.value = this.value.replace(/[^0-9]/g, ''); // Remove non-numeric characters
            });
            $(document).on('click', '.deleteContact', function () {
                showLoader()
                let contactId = $(this).data('id');

                if (confirm('Are you sure you want to delete this contact?')) {
                    $.ajax({
                        url: '/contacts/delete', // Ensure the correct route
                        type: 'POST',
                        data: {
                            id: contactId,
                            _token: $('input[name="_token"]').val() // CSRF token for Laravel
                        },
                        success: function (response) {
                            alert(response.message);
                            location.reload(); // Reload the page to update the contact list
                        },
                        error: function (xhr) {
                            alert('Error: ' + xhr.responseJSON.message);
                        }
                    });
                }
            });
        });
    </script>

</body>
</html>
