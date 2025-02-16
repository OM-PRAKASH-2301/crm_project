function showNotification(message, type = 'success') {
    let notificationBox = $('#notificationBox');
    
    notificationBox
        .text(message)
        .removeClass('success-notification error-notification')
        .addClass(type === 'success' ? 'success-notification' : 'error-notification')
        .fadeIn();

    setTimeout(() => {
        notificationBox.fadeOut();
    }, 3000);
}
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
        showLoader();
        $.ajax({
            url: '/contacts/list',
            type: 'GET',
            success: function(response) {
                hideLoader();
                $('#contactTableBody').html(response);
            },
            error: function(xhr) {
                showNotification(response.message, 'failed');
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
                    showNotification(response.message, 'success');
                    location.reload();
                } else {
                    showNotification(response.message, 'failed');
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
    $('.phoneInput').on('input', function () {
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
                    showNotification(response.message, 'success');
                    location.reload(); // Reload the page to update the contact list
                },
                error: function (xhr) {
                    showNotification(response.message, 'failed');
                }
            });
        }
    });
    $(document).on('click', '.editContact', function () {
        let contactId = $(this).data('id');
    
        $.ajax({
            url: '/contacts/' + contactId + '/edit',
            type: 'GET',
            success: function (response) {
                if (response.status === 'success') {
                    $('#editContactModal').modal('show');
                    // Populate form fields
                    $('#phone').val('');  // Clear value first
                    $('#id').val(response.contact.id);
                    $('#name').val(response.contact.name);
                    $('#email').val(response.contact.email);
                    $('#phone_no').val(response.contact.phone);
                    $('#gender').val(response.contact.gender);
    
                    $('#currentProfileImage').attr('src', response.contact.profile_image || 'default_image.jpg');
                } else {
                    alert('Contact not found!');
                }
            },
            error: function () {
                alert('Error fetching contact details!');
            }
        });
    });
    
    // Handle form submission
    $('#editContactForm').on('submit', function (e) {
        e.preventDefault();
        $('.error-message').text(''); // Clear previous error messages
    
        let formData = new FormData(this);
    
        $.ajax({
            url: "/contacts/update",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 'success') {
                    showNotification(response.message, 'success');
                    location.reload(); // Reload to update changes
                } else {
                    showNotification(response.message, 'failed');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) { // Laravel validation error
                    let errors = xhr.responseJSON.errors;
                    
                    if (errors.name) {
                        $('#error-name').text(errors.name[0]);
                    }
                    if (errors.email) {
                        $('#error-email').text(errors.email[0]);
                    }
                    if (errors.phone) {
                        $('#error-phone').text(errors.phone[0]);
                    }
                    if (errors.gender) {
                        $('#error-gender').text(errors.gender[0]);
                    }
                    if (errors.profile_image) {
                        $('#error-profile_image').text(errors.profile_image[0]);
                    }
                } else {
                    showNotification(response.message, 'failed');
                }
            }
        });
    });
    
    
    
    
});