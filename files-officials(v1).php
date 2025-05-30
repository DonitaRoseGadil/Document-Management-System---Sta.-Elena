<?php 
    error_reporting(E_ALL); // Enable error reporting for development
    ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>

<body>
    
    <!--*******************
        Preloader start
    ********************-->
    <?php include"loadingscreen.php" ?>
    <!--*******************
        Preloader end
    ********************-->
    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php 
            
            include "sidebar.php";
            
            // Fetch role from session
            $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
        ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="color: #098209; background-color: #f1f9f1;">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">ORGANIZATIONAL CHART</h1>
                                <div class="button-container d-flex justify-content-end">
                                    <?php if ($role === 'admin' || $role === 'master') { ?>
                                        <a href="addOfficials.php">
                                            <button type="button" class="btn btn-primary" style="background-color: #098209; color:#FFFFFF; border: none;"><i class="fa fa-plus"></i>&nbsp; Add Officials</button>
                                        </a>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <!-- Vice Mayor -->
                                    <?php
                                        $query = mysqli_query($conn, "SELECT * FROM officials WHERE position = 'Vice-Mayor'");

                                        if ($query && mysqli_num_rows($query) > 0) {
                                            $row = mysqli_fetch_assoc($query);
                                    ?>
                                            <div class="col-md-3 text-center mb-4">
                                                <div class="card" onclick='openModal(<?= json_encode($row) ?>)'>
                                                    <img src="<?= htmlspecialchars($row['photo_path']) ?>" class="card-img-top">
                                                    <!-- Pabago nalang here ng bg nung card if di bagay yung green ahhahaha -->
                                                    <div class="card-body text-white text-center" style="background-color: #098209; padding: 1rem;">
                                                        <h3 class="card-title mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                                                            <?= htmlspecialchars($row['firstname'] . ' ' . $row['surname']) ?>
                                                        </h3>
                                                        <p class="card-text mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                                                            <?= htmlspecialchars($row['position']) ?>
                                                        </p>
                                                    </div>  
                                                </div>
                                            </div>

                                    <?php
                                        } else {
                                            echo "<p class='text-center text-muted'>No Vice Mayor added yet.</p>";
                                        }
                                    ?>
                                </div>
                                <!-- First row of 4 Councilors -->
                                <div class="row justify-content-center">
                                    <?php
                                        $query = mysqli_query($conn, "SELECT * FROM officials WHERE position = 'Councilor' LIMIT 4");
                                        if ($query && mysqli_num_rows($query) > 0) {
                                            while ($row = mysqli_fetch_assoc($query)):
                                    ?>
                                        <div class="col-md-3 text-center mb-4">
                                            <div class="card" onclick='openModal(<?= json_encode($row) ?>)' style="cursor: pointer;">
                                                <img src="<?= htmlspecialchars($row['photo_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                <div class="card-body text-white text-center" style="background-color: #098209; padding: 1rem;">
                                                    <h3 class="card-title mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                                                        <?= htmlspecialchars($row['firstname'] . ' ' . $row['surname']) ?>
                                                    </h3>
                                                    <p class="card-text mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                                                        <?= htmlspecialchars($row['position']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    <?php
                                            endwhile;
                                        } else {
                                            echo "<p class='text-center text-muted w-100'>No Councilors added yet.</p>";
                                        }
                                    ?>
                                </div>
                                <!-- Second row of 4 Councilors -->
                                <div class="row justify-content-center">
                                    <?php
                                        $query = mysqli_query($conn, "SELECT * FROM officials WHERE position = 'Councilor' LIMIT 4 OFFSET 4");
                                        if ($query && mysqli_num_rows($query) > 0) {
                                            while ($row = mysqli_fetch_assoc($query)):
                                    ?>
                                        <div class="col-md-3 text-center mb-4">
                                            <div class="card" onclick='openModal(<?= json_encode($row) ?>)' style="cursor: pointer;">
                                                <img src="<?= htmlspecialchars($row['photo_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                <div class="card-body text-white text-center" style="background-color: #098209; padding: 1rem;">
                                                    <h3 class="card-title mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                                                        <?= htmlspecialchars($row['firstname'] . ' ' . $row['surname']) ?>
                                                    </h3>
                                                    <p class="card-text mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                                                        <?= htmlspecialchars($row['position']) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                            endwhile;
                                        } else {
                                            echo "<p class='text-center text-muted w-100'>No additional Councilors added yet.</p>";
                                        }
                                    ?>
                                </div>

                                
                                <!-- LNB and PPSK -->
                                <div class="row justify-content-center">
                                    <?php
                                        $positions = ['LNB', 'PPSK'];
                                        foreach ($positions as $pos):
                                            $query = mysqli_query($conn, "SELECT * FROM officials WHERE position = '$pos'");
                                            if ($query && mysqli_num_rows($query) > 0) {
                                                if ($row = mysqli_fetch_assoc($query)):
                                    ?>
                                        <div class="col-md-3 text-center mb-4">
                                            <div class="card">
                                                <img src="<?= htmlspecialchars($row['photo_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                <div class="col-md-3 text-center mb-4">
                                                    <div class="card" onclick='openModal(<?= json_encode($row) ?>)' style="cursor: pointer;">
                                                        <img src="<?= htmlspecialchars($row['photo_path']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                        <div class="card-body text-white text-center" style="background-color: #098209; padding: 1rem;">
                                                            <h3 class="card-title mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                                                                <?= htmlspecialchars($row['firstname'] . ' ' . $row['surname']) ?>
                                                            </h3>
                                                            <p class="card-text mb-0" style="font-size: 0.95rem; opacity: 0.9;">
                                                                <?= htmlspecialchars($row['position']) ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                                endif;
                                            } else {
                                                echo "<p class='text-center text-muted w-100'>No $pos added yet.</p>";
                                            }
                                        endforeach;
                                    ?>

                                </div>

                                <!-- MODAL -->
                                <div class="modal fade" id="officialModal" tabindex="-1" aria-labelledby="officialModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl"> <!-- wider modal -->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-success" id="officialModalLabel">Official Details</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <!-- View Mode -->
                                                <div id="viewMode">
                                                    <div class="row mb-4">
                                                        <div class="col-md-4">
                                                            <div style="background-color: #098209; color: white; padding: 1.5rem; border-radius: 0.5rem; height: 100%; display: flex; flex-direction: column; align-items: center;">
                                                                
                                                                <!-- Image with top margin -->
                                                                <img id="modalImage" src="" class="img-fluid rounded shadow mb-3" style="max-height: 250px; margin-top: 2rem;">

                                                                <!-- Full Name and Position -->
                                                                <h4 id="modalName" class="mb-1 text-white text-center"></h4>
                                                                <p id="modalPosition" class="mb-3 text-white text-center"></p>

                                                                <!-- Contact Information -->
                                                                <div class="d-flex flex-column align-items-center px-3 mt-4">
                                                                    <p class="mb-1 text-white d-flex align-items-start" style="font-size: 0.9rem; text-align: justify;">
                                                                        <i class="fa fa-envelope me-2 mt-1"></i>
                                                                        <span id="modalEmailleft"></span>
                                                                    </p>
                                                                    <p class="mb-1 text-white d-flex align-items-start" style="font-size: 0.9rem; text-align: justify;">
                                                                        <i class="fa fa-phone me-2 mt-1"></i>
                                                                        <span id="modalMobileleft"></span>
                                                                    </p>
                                                                    <p class="mb-3 text-white d-flex align-items-start" style="font-size: 0.9rem; text-align: justify;">
                                                                        <i class="fa fa-map-marker me-2 mt-1"></i>
                                                                        <span id="modalAddressleft"></span>
                                                                    </p>
                                                                </div>

                                                                <!-- Buttons -->
                                                                <div class="mt-auto d-flex justify-content-center gap-2">
                                                                    <button type="button" class="btn btn-light btn-sm me-2" onclick="editOfficial(currentOfficialId)">
                                                                        <i class="fa fa-pencil"></i> Edit
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteOfficial(currentOfficialId)">
                                                                        <i class="fa fa-trash text-white"></i> Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-8">

                                                            <!-- Personal Info -->
                                                            <h5 class="border-bottom pb-1 mb-2" style="color: #28a745;">Personal Information</h5>
                                                            <div class="row text-dark">
                                                                <div class="col-md-6 mb-2"><strong>Surname:</strong> <span id="modalSurname"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Firstname:</strong> <span id="modalFirstname"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Middlename:</strong> <span id="modalMiddlename"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Birthday:</strong> <span id="modalBirthday"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Birthplace:</strong> <span id="modalBirthplace"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Address:</strong> <span id="modalAddress"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Mobile Number:</strong> <span id="modalMobile"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Email:</strong> <span id="modalEmail"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Gender:</strong> <span id="modalGender"></span></div>
                                                            </div>

                                                            <!-- Education -->
                                                            <h5 class="border-bottom pb-1 mt-4 mb-2" style="color: #28a745;">Highest Educational Attaintment</h5>
                                                            <div class="row text-dark">
                                                                <div class="col-md-6 mb-2"><strong>Degree/Level:</strong> <span id="modalEduAttain"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>School:</strong> <span id="modalEduSchool"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Year Graduated:</strong> <span id="modalEduDate"></span></div>
                                                            </div>

                                                            <!-- Family -->
                                                            <h5 class="border-bottom pb-1 mt-4 mb-2" style="color: #28a745;">Family Information</h5>
                                                            <div class="row text-dark">
                                                                <div class="col-md-6 mb-2"><strong>Civil Status:</strong> <span id="modalCivilStatus"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Spouse Name:</strong> <span id="modalSpouseName"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Spouse Birthday:</strong> <span id="modalSpouseBday"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Spouse Birthplace:</strong> <span id="modalSpouseBirthplace"></span></div>
                                                                <div class="col-md-6 mb-2"><strong>Dependents:</strong> <span id="modalDependents"></span></div>
                                                            </div>

                                                            <!-- Government Info -->
                                                            <h5 class="border-bottom pb-1 mt-4 mb-2" style="color: #28a745;">Government Details</h5>
                                                            <div class="row text-dark">
                                                                <div class="col-md-4 mb-2"><strong>GSIS No:</strong> <span id="modalGSIS"></span></div>
                                                                <div class="col-md-4 mb-2"><strong>PAG-IBIG No:</strong> <span id="modalPAGIBIG"></span></div>
                                                                <div class="col-md-4 mb-2"><strong>PhilHealth No:</strong> <span id="modalPhilHealth"></span></div>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="modal-footer">
                                                            <button class="btn btn-warning" onclick="toggleEdit()">Toggle Edit</button>
                                                        </div> -->
                                                        <div class="modal-footer">
                                                        </div> <!-- end #viewMode -->
                                                    </div>
                                                </div>

                                                <!-- Edit Mode -->
                                                <form id="editMode" style="display: none;" method="post" action="editofficial.php" enctype="multipart/form-data">
                                                    <input type="hidden" name="official_id" id="edit_id">

                                                    <!-- BASIC INFORMATION -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h5 style="color: #098209; font-weight: bold;">Basic Information</h5>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Position:</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="edit_position" name="position" required>
                                                                <option value="">Choose...</option>
                                                                <option value="Vice-Mayor">Vice-Mayor</option>
                                                                <option value="Councilor">Councilor</option>
                                                                <option value="LNB">LNB</option>
                                                                <option value="PPSK">PPSK</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Surname:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_surname" name="surname" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">First Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_firstname" name="firstname" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Middle Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_middlename" name="middlename">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Age:</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" class="form-control" id="edit_age" name="age">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Birthday:</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control" id="edit_birthday" name="birthday">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Birthplace:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_birthplace" name="birthplace">
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- EDUCATIONAL BACKGROUND -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h5 style="color: #098209; font-weight: bold;">Educational Background</h5>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Attainment:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_education_attainment" name="education_attainment">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">School Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_education_school" name="education_school">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Date Graduated:</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control" id="edit_education_date" name="education_date">
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- CIVIL STATUS -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h5 style="color: #098209; font-weight: bold;">Civil Status</h5>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Civil Status:</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" id="edit_civil_status" name="civil_status">
                                                                <option value="">Choose...</option>
                                                                <option value="Single">Single</option>
                                                                <option value="Married">Married</option>
                                                                <option value="Widowed">Widowed</option>
                                                                <option value="Divorced">Divorced</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Spouse Name:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_spouse_name" name="spouse_name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Spouse Birthday:</label>
                                                        <div class="col-sm-9">
                                                            <input type="date" class="form-control" id="edit_spouse_birthday" name="spouse_birthday">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Spouse Birthplace:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_spouse_birthplace" name="spouse_birthplace">
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- PERSONAL INFORMATION -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-12">
                                                            <h5 style="color: #098209; font-weight: bold;">Personal Information</h5>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Dependents:</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" class="form-control" id="edit_dependents" name="dependents">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">GSIS Number:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_gsis_number" name="gsis_number">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">PAG-IBIG Number:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_pagibig_number" name="pagibig_number">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">PhilHealth Number:</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" class="form-control" id="edit_philhealth_number" name="philhealth_number">
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <!-- PHOTO -->
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text" style="background-color: #098209;">
                                                                <i class="fa fa-paperclip"></i>
                                                            </span>
                                                        </div>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input" id="edit_photo" name="photo" accept="image/*" onchange="updateFileName(this)">
                                                            <label class="custom-file-label" for="edit_photo">Choose image</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                                        </div>
                                                    </div>

                                                    <!-- BUTTONS -->
                                                    <div class="form-group row d-flex justify-content-center">
                                                        <button type="submit" class="btn btn-primary" style="background-color: #098209; border: none; width: 100px;">Save</button>
                                                        <a href="#" class="btn btn-danger ml-2" style="width: 100px;" onclick="document.getElementById('editMode').style.display='none'">Cancel</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--**********************************
            Content body end
        ***********************************-->
        
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <style>
        .card {
            border: 2px solid #098209; /* Green border */
            cursor: pointer;
            transition: box-shadow 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 0 15px rgba(9, 130, 9, 0.6); /* Glowing shadow on hover */
        }

    </style>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function openModal(data) {
            // Compose full name with optional middlename
            const fullName = `${data.firstname} ${data.middlename ? data.middlename + ' ' : ''}${data.surname}`.trim();

            // Set the full name into one element
            document.getElementById('modalName').textContent = fullName;

            // Set other fields as before
            document.getElementById('modalImage').src = data.photo_path;
            document.getElementById('modalPosition').textContent = data.position;
            // If you want to keep separate name fields, you can set them too:
            document.getElementById('modalSurname').textContent = data.surname;
            document.getElementById('modalFirstname').textContent = data.firstname;
            document.getElementById('modalMiddlename').textContent = data.middlename;

            document.getElementById('modalAddressleft').textContent = data.address;
            document.getElementById('modalMobileleft').textContent = data.mobile_number;
            document.getElementById('modalEmailleft').textContent = data.email;

            document.getElementById('modalBirthday').textContent = data.birthday;
            document.getElementById('modalBirthplace').textContent = data.birthplace;
            document.getElementById('modalAddress').textContent = data.address;
            document.getElementById('modalMobile').textContent = data.mobile_number;
            document.getElementById('modalEmail').textContent = data.email;
            document.getElementById('modalGender').textContent = data.gender;
            document.getElementById('modalEduAttain').textContent = data.education_attainment;
            document.getElementById('modalEduSchool').textContent = data.education_school;
            document.getElementById('modalEduDate').textContent = data.education_date;
            document.getElementById('modalCivilStatus').textContent = data.civil_status;
            document.getElementById('modalSpouseName').textContent = data.spouse_name;
            document.getElementById('modalSpouseBday').textContent = data.spouse_birthday;
            document.getElementById('modalSpouseBirthplace').textContent = data.spouse_birthplace;
            document.getElementById('modalDependents').textContent = data.dependents;
            document.getElementById('modalGSIS').textContent = data.gsis_number;
            document.getElementById('modalPAGIBIG').textContent = data.pagibig_number;
            document.getElementById('modalPhilHealth').textContent = data.philhealth_number;

            document.getElementById('viewMode').style.display = 'block';
            document.getElementById('editMode').style.display = 'none';

            new bootstrap.Modal(document.getElementById('officialModal')).show();
        }


        function editOfficial(id) {
            fetch('getOfficialsData.php?id=' + id)
                .then(response => response.json())
                .then(official => {
                document.getElementById('edit_id').value = official.id;
                document.getElementById('edit_position').value = official.position;
                document.getElementById('edit_surname').value = official.surname;
                document.getElementById('edit_firstname').value = official.firstname;
                document.getElementById('edit_middlename').value = official.middlename;
                document.getElementById('edit_birthday').value = official.birthday;
                document.getElementById('edit_birthplace').value = official.birthplace;
                document.getElementById('edit_address').value = official.address;
                document.getElementById('edit_contact').value = official.mobile_number;
                document.getElementById('edit_email').value = official.email;
                document.getElementById('edit_gender').value = official.gender;
                document.getElementById('edit_civil_status').value = official.civil_status;

                document.getElementById('edit_edu_attainment').value = official.education_attainment;
                document.getElementById('edit_edu_school').value = official.education_school;
                document.getElementById('edit_edu_date').value = official.education_date;

                document.getElementById('edit_spouse_name').value = official.spouse_name;
                document.getElementById('edit_spouse_birthday').value = official.spouse_birthday;
                document.getElementById('edit_spouse_birthplace').value = official.spouse_birthplace;
                document.getElementById('edit_dependents').value = official.dependents;

                document.getElementById('edit_gsis').value = official.gsis_number;
                document.getElementById('edit_pagibig').value = official.pagibig_number;
                document.getElementById('edit_philhealth').value = official.philhealth_number;

                // Optional: show/hide or scroll to the edit form
                document.getElementById('viewMode').style.display = 'none';
                document.getElementById('editMode').style.display = 'block';
                })
                .catch(error => console.error('Error fetching official data:', error));
        }

        function toggleEdit() {
            const view = document.getElementById('viewMode');
            const edit = document.getElementById('editMode');
            if (edit.style.display === 'none') {
                view.style.display = 'none';
                edit.style.display = 'block';
            } else {
                edit.style.display = 'none';
                view.style.display = 'block';
            }
        }

    </script>

</body>

</html>