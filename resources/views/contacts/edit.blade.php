<!-- Edit Contact Modal -->
<div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editContactModalLabel">Edit Contact</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editContactForm" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                        <span class="text-danger error-message" id="error-name"></span>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" id="email" name="email" class="form-control">
                        <span class="text-danger error-message" id="error-email"></span>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" id="phone_no" name="phone" class="form-control phoneInput" maxlength="10">
                        <span class="text-danger error-message" id="error-phone"></span>
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <span class="text-danger error-message" id="error-gender"></span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Current Profile Image</label><br>
                        <img id="currentProfileImage" src="default_image.jpg" width="100">
                    </div>

                    <div class="mb-3">
                        <label for="profile_image" class="form-label">Upload New Profile Image</label>
                        <input type="file" id="profile_image" name="profile_image" class="form-control">
                        <span class="text-danger error-message" id="error-profile_image"></span>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
