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
                <th>Profile Pic</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="contactTableBody">
            <!-- Contacts will be dynamically loaded here -->
        </tbody>
    </table>
</div>
