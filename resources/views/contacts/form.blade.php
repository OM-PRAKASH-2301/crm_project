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