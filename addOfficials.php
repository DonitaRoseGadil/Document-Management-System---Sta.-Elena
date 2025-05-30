<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const requiredFields = [
            "position",
            "surname",
            "firstname",
            "birthday",
            "birthplace",
            "address",
            "mobile_number",
            "email",
            "gender",
            // "civil_status",
            // "spouse_name",
            // "spouse_birthday",
            // "spouse_birthplace",
            // "dependents",
            "gsis_number",
            "pagibig_number",
            "philhealth_number"
            // photo_path handled via PHP upload, so not validated here
        ];

        function validateField(field) {
            let inputElement = document.getElementById(field);
            let errorElement = document.getElementById(field + "-error");

            if (!inputElement.value.trim() || (field === "position" && inputElement.value === "Choose...")) {
                if (!errorElement) {
                    let errorMsg = document.createElement("div");
                    errorMsg.id = field + "-error";
                    errorMsg.className = "text-danger mt-1";
                    errorMsg.textContent = "Required missing field.";
                    inputElement.parentNode.appendChild(errorMsg);
                }
            } else {
                if (errorElement) {
                    errorElement.remove();
                }
            }
        }

        // Add event listeners for real-time validation
        requiredFields.forEach(function (field) {
            let inputElement = document.getElementById(field);

            if (inputElement) {
                // "input" event - Hide error while typing
                inputElement.addEventListener("input", function () {
                    validateField(field);
                });

                // "change" event for dropdown validation
                if (field === "position") {
                    inputElement.addEventListener("change", function () {
                        validateField(field);
                    });
                }

                // "focusout" event - Show error if empty when user leaves field
                inputElement.addEventListener("focusout", function () {
                    validateField(field);
                });
            }
        });

        // Form submit validation
        document.querySelector("form").addEventListener("submit", function (event) {
            let isValid = true;
            requiredFields.forEach(function (field) {
                validateField(field);
                if (!document.getElementById(field).value.trim() || 
                    (field === "position" && document.getElementById(field).value === "Choose...")) {
                    isValid = false;
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>


<body>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include "sidebar.php"; ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="background-color: #f1f9f1">
            <div class="container-fluid" >
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8 col-xxl-12 items-center">                        
                        <div class="card" style="align-self: center;">
                            <div class="card-header d-flex justify-content-center">
                                <h4 class="card-title text-center" style="color: #098209;">ADD OFFICIALS</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="addOfficials.php" method="post" enctype="multipart/form-data">
                                        <?php
                                            if (isset($_POST['save'])) {
                                                include("connect.php");
                                                error_reporting(0);

                                                // Collect POST data
                                                $position = $_POST['position'];
                                                $surname = $_POST['surname'];
                                                $firstname = $_POST['firstname'];
                                                $middlename = $_POST['middlename'];
                                                $birthday = $_POST['birthday'];
                                                $birthplace = $_POST['birthplace'];
                                                $address = $_POST['address'];
                                                $mobile_number = $_POST['mobile_number'];
                                                $email = $_POST['email'];
                                                $gender = $_POST['gender'];

                                                $education_attainment = $_POST['education_attainment'];
                                                $education_school = $_POST['education_school'];
                                                $education_date = $_POST['education_date'];

                                                $civil_status = $_POST['civil_status'];
                                                $spouse_name = $_POST['spouse_name'];
                                                $spouse_birthday = $_POST['spouse_birthday'];
                                                $spouse_birthplace = $_POST['spouse_birthplace'];

                                                $dependents = $_POST['dependents'];
                                                $gsis_number = $_POST['gsis_number'];
                                                $pagibig_number = $_POST['pagibig_number'];
                                                $philhealth_number = $_POST['philhealth_number'];

                                                // Handle image upload
                                                $photo_path = "";
                                                if (!empty($_FILES['photo']['name'])) {
                                                    $targetDir = "uploads/";
                                                    $imageFileType = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                                                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

                                                    if (in_array($imageFileType, $allowedTypes)) {
                                                        $newFileName = uniqid() . '.' . $imageFileType; // avoid file name collisions
                                                        $photo_path = $targetDir . $newFileName;
                                                        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
                                                    } else {
                                                        echo "<script>
                                                                Swal.fire({
                                                                    icon: 'error',
                                                                    title: 'Invalid File Type',
                                                                    text: 'Please upload a valid image (JPG, PNG, or GIF).'
                                                                });
                                                            </script>";
                                                        exit;
                                                    }
                                                }

                                                // Prepare SQL insert
                                                $sql = "INSERT INTO officials 
                                                    (position, surname, firstname, middlename, birthday, birthplace, address, mobile_number, email, gender,
                                                    education_attainment, education_school, education_date,
                                                    civil_status, spouse_name, spouse_birthday, spouse_birthplace,
                                                    dependents, gsis_number, pagibig_number, philhealth_number, photo_path) 
                                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("ssssssssssssssssssssss", 
                                                    $position, $surname, $firstname, $middlename, $birthday, $birthplace, $address, $mobile_number, $email, $gender,
                                                    $education_attainment, $education_school, $education_date,
                                                    $civil_status, $spouse_name, $spouse_birthday, $spouse_birthplace,
                                                    $dependents, $gsis_number, $pagibig_number, $philhealth_number, $photo_path);

                                                if ($stmt->execute()) {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Official Added',
                                                                text: 'The official has been successfully added.'
                                                            }).then(() => { window.location.href = 'files-officials.php'; });
                                                        </script>";
                                                } else {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Error',
                                                                text: 'There was an error creating the official.',
                                                                confirmButtonText: 'OK'
                                                            });
                                                        </script>";
                                                }

                                                $stmt->close();
                                                $conn->close();
                                            }
                                        ?>

                                        <!-- Form Inputs -->
                                        
                                        <!-- BASIC INFORMATION-->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Basic Information</h5>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Position:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="position" name="position" required>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Vice-Mayor">Vice-Mayor</option>
                                                    <!-- diko pa sure if need na may 1-8 yun sa option ng councilor para ma-identify sino yun -->
                                                    <option value="Councilor">Councilor</option>
                                                    <option value="LNB">LNB</option>
                                                    <option value="PPSK">PPSK</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Surname:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="surname" name="surname" required placeholder="Enter surname">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">First Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="firstname" name="firstname" required placeholder="Enter first name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Middle Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter middle name (optional)">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Birthday:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="birthday" name="birthday" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Birthplace:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="birthplace" name="birthplace" required placeholder="Enter birthplace">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Address:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="address" name="address" rows="2" required placeholder="Enter address"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Mobile Number:</label>
                                            <div class="col-sm-9">
                                                <input type="tel" class="form-control" id="mobile_number" name="mobile_number" required placeholder="Enter mobile number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Email:</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email address">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Gender:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="gender" name="gender" required>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>
                                        
                                         <!-- EDUCATIONAL BACKGROUND-->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Educational Background</h5>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Highest Education Attainment:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="education_attainment" name="education_attainment" placeholder="e.g. Bachelor's Degree">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">School:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="education_school" name="education_school" placeholder="Enter school name">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Inclusive Dates:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="education_date" name="education_date" placeholder="e.g. June 2020">
                                            </div>
                                        </div>

                                        <hr>


                                         <!-- CIVIL STATUS INFORMATION -->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Civil Status</h5>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Civil Status:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="civil_status" name="civil_status" required>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Widowed">Widowed</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="Enter spouse name if applicable">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Birthday:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="spouse_birthday" name="spouse_birthday">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Birthplace:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="spouse_birthplace" name="spouse_birthplace" placeholder="Enter spouse birthplace">
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
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Dependents:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="dependents" name="dependents" rows="2" placeholder="List dependents"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">GSIS Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="gsis_number" name="gsis_number" placeholder="Enter GSIS number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Pag-IBIG Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pagibig_number" name="pagibig_number" placeholder="Enter Pag-IBIG number">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">PhilHealth Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="philhealth_number" name="philhealth_number" placeholder="Enter PhilHealth number">
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;">
                                                    <i class="fa fa-paperclip"></i>
                                                </span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*" onchange="updateFileName(this)" required>
                                                <label class="custom-file-label text-truncate" for="photo" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">Choose image</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-group row d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Save</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-officials.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
                                        </div>
                                    </form>
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

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script>

        function updateFileName(input) {
        const fileInput = input;
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            const label = fileInput.nextElementSibling; // label associated with input
            label.textContent = fileName;
        } else {
            const label = fileInput.nextElementSibling;
            label.textContent = "Choose image";
        }
    }

    // Auto-expand textarea (if you still want for age textarea)
    function autoExpand(event) {
        const textarea = event.target;
        textarea.style.height = "auto"; 
        textarea.style.height = textarea.scrollHeight + "px"; 
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Auto-expand for age textarea
        const ageTextarea = document.getElementById("age");
        if (ageTextarea) {
            ageTextarea.addEventListener("input", autoExpand);
        }
    });

    function removeFile() {
        const fileInput = document.getElementById("attachment");
        const fileLabel = fileInput.nextElementSibling;

        fileInput.value = ""; // Clear file input
        fileLabel.textContent = "Choose image"; // Reset label text
    }

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const requiredFields = [
            "position", "surname", "firstname", "birthday", "birthplace",
            "address", "mobile_number", "email", "gender",
            "gsis_number", "pagibig_number", "philhealth_number"
        ];

        function validateField(field) {
            const inputElement = document.getElementById(field);
            if (!inputElement) return true;

            const errorElement = document.getElementById(field + "-error");
            const isEmpty = !inputElement.value.trim() || 
                            (field === "position" && inputElement.value === "Choose...");

            if (isEmpty) {
                if (!errorElement) {
                    const errorMsg = document.createElement("div");
                    errorMsg.id = field + "-error";
                    errorMsg.className = "text-danger mt-1";
                    errorMsg.textContent = "Required field.";
                    inputElement.parentNode.appendChild(errorMsg);
                }
                inputElement.classList.add("is-invalid");
                return false;
            } else {
                if (errorElement) errorElement.remove();
                inputElement.classList.remove("is-invalid");
                return true;
            }
        }

        function validateForm(event) {
            let isValid = true;
            let firstInvalid = null;

            requiredFields.forEach(function (field) {
                if (!validateField(field)) {
                    isValid = false;
                    if (!firstInvalid) firstInvalid = field;
                }
            });

            if (!isValid) {
                event.preventDefault(); // stop form submission

                Swal.fire({
                    icon: 'error',
                    title: 'Incomplete Form',
                    text: 'All required fields must be filled out before submitting!',
                    confirmButtonText: 'OK'
                });

                if (firstInvalid) {
                    document.getElementById(firstInvalid).scrollIntoView({ behavior: "smooth", block: "center" });
                    document.getElementById(firstInvalid).focus();
                }

                return false;
            }

            return true; // allow submission
        }

        // Attach validation on submit
        form.addEventListener("submit", validateForm);

        // Optional: live feedback on blur/input
        requiredFields.forEach(function (field) {
            const inputElement = document.getElementById(field);
            if (inputElement) {
                inputElement.addEventListener("input", () => validateField(field));
                inputElement.addEventListener("focusout", () => validateField(field));
            }
        });
    });


    </script>    

    <script>
        document.getElementById('cancel_btn').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent immediate navigation
            const redirectUrl = this.getAttribute('data-href');

            Swal.fire({
                title: 'Are you sure?',
                text: "All unsaved changes will be lost.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = redirectUrl;
                }
            });
        });
    </script>
</body>

</html>