<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>
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
                                <h4 class="card-title text-center" style="color: #098209;">VIEW OFFICIAL</h4>
                            </div>
                            <?php
                                include("connect.php");
                                $id = $_GET['id'];
                                $sql = "SELECT * FROM `officials` WHERE id = $id LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result); 
                            ?>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="addOfficials.php" method="post" enctype="multipart/form-data">

                                        <!-- Form Inputs -->             
                                        <!-- BASIC INFORMATION-->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Basic Information</h5>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Surname:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter surname" value="<?php echo $row['surname']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">First Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" value="<?php echo $row['firstname']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Middle Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter middle name (optional)" value="<?php echo $row['middlename']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Birthday:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $row['birthday']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Birthplace:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="birthplace" name="birthplace" placeholder="Enter birthplace" value="<?php echo $row['birthplace']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Address:</label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Enter address" id="address" name="address" disabled><?php echo htmlspecialchars_decode($row['address']); ?></textarea>
                                             </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Mobile Number:</label>
                                            <div class="col-sm-9">
                                                <input type="tel" class="form-control" id="mobile_number" name="mobile_number" placeholder="Enter mobile number" value="<?php echo $row['mobile_number']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Email:</label>
                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" value="<?php echo $row['email']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Gender:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="gender" name="gender" disabled>
                                                    <option value="" <?php echo ($row['gender'] == '') ? 'selected' : ''; ?>>Choose...</option>
                                                    <option value="Male" <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                    <option value="Other" <?php echo ($row['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
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
                                                <input type="text" class="form-control" id="education_attainment" name="education_attainment" placeholder="e.g. Bachelor's Degree" value="<?php echo $row['education_attainment']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">School:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="education_school" name="education_school" placeholder="Enter school name" value="<?php echo $row['education_school']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Inclusive Dates:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="education_date" name="education_date" placeholder="e.g. June 2020" value="<?php echo $row['education_date']?>" disabled>
                                            </div>
                                        </div>

                                        <hr>


                                         <!-- CIVIL STATUS INFORMATION -->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Civil Status & Family Details</h5>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Civil Status:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="civil_status" name="civil_status" disabled>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Single" <?php if($row['civil_status'] == "Single") echo 'selected'; ?>>Single</option>
                                                    <option value="Married" <?php if($row['civil_status'] == "Married") echo 'selected'; ?>>Married</option>
                                                    <option value="Widowed" <?php if($row['civil_status'] == "Widowed") echo 'selected'; ?>>Widowed</option>
                                                    <option value="Divorced" <?php if($row['civil_status'] == "Divorced") echo 'selected'; ?>>Divorced</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Name:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="Enter spouse name if applicable" value="<?php echo $row['spouse_name']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Birthday:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="spouse_birthday" name="spouse_birthday" value="<?php echo $row['spouse_birthday']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Spouse Birthplace:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="spouse_birthplace" name="spouse_birthplace" placeholder="Enter spouse birthplace" value="<?php echo $row['spouse_birthplace']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Dependents:</label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="List dependents" id="dependents" name="dependents" disabled><?php echo htmlspecialchars_decode($row['dependents']); ?></textarea>
                                             </div>
                                        </div>

                                        <hr>
                                        
                                        <!-- Government IDs & Memberships -->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Government IDs & Memberships</h5>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">GSIS Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="gsis_number" name="gsis_number" placeholder="Enter GSIS number" value="<?php echo $row['gsis_number']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Pag-IBIG Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="pagibig_number" name="pagibig_number" placeholder="Enter Pag-IBIG number" value="<?php echo $row['pagibig_number']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">PhilHealth Number:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="philhealth_number" name="philhealth_number" placeholder="Enter PhilHealth number" value="<?php echo $row['philhealth_number']?>" disabled>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Official Roles & Committee Memberships -->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Official Roles & Committee Memberships</h5>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Term Duration:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="term_duration" name="term_duration" placeholder="e.g. 2025 - 2028" value="<?php echo $row['term']?>" disabled>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Position:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="position" name="position" disabled>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Vice-Mayor" <?php if($row['position'] == "Vice-Mayor") echo 'selected'; ?>>Vice Mayor</option>
                                                    <option value="Councilor1" <?php if($row['position'] == "Councilor1") echo 'selected'; ?>>Councilor 1</option>
                                                    <option value="Councilor2" <?php if($row['position'] == "Councilor2") echo 'selected'; ?>>Councilor 2</option>
                                                    <option value="Councilor3" <?php if($row['position'] == "Councilor3") echo 'selected'; ?>>Councilor 3</option>
                                                    <option value="Councilor4" <?php if($row['position'] == "Councilor4") echo 'selected'; ?>>Councilor 4</option>
                                                    <option value="Councilor5" <?php if($row['position'] == "Councilor5") echo 'selected'; ?>>Councilor 5</option>
                                                    <option value="Councilor6" <?php if($row['position'] == "Councilor6") echo 'selected'; ?>>Councilor 6</option>
                                                    <option value="Councilor7" <?php if($row['position'] == "Councilor7") echo 'selected'; ?>>Councilor 7</option>
                                                    <option value="Councilor8" <?php if($row['position'] == "Councilor8") echo 'selected'; ?>>Councilor 8</option>
                                                    <option value="LNB" <?php if($row['position'] == "LNB") echo 'selected'; ?>>LNB</option>
                                                    <option value="PPSK" <?php if($row['position'] == "PPSK") echo 'selected'; ?>>PPSK</option>
                                                    <option value="SBsecretary" <?php if($row['position'] == "SBsecretary") echo 'selected'; ?>>SB Secretary</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Committee:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="committee" name="committee">
                                                    <option value="" selected>Choose...</option>
                                                    <?php
                                                        include("connect.php");
                                                        $sql = "SELECT id, name FROM committee";
                                                        $result = $conn->query($sql);

                                                        if ($result->num_rows > 0) {
                                                            while($row = $result->fetch_assoc()) {
                                                                // Use name as the value to be saved
                                                                echo '<option value="' . htmlspecialchars($row["name"]) . '">' . htmlspecialchars($row["name"]) . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option disabled>No committees found</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Position in the Committee:</label>
                                            <div class="col-sm-9">
                                                <select class="form-control" id="positioncmte" disabled>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Chairman" <?php if($row['committeeType'] == "Chairman") echo 'selected'; ?>>Chairman</option>
                                                    <option value="Co-Chairman" <?php if($row['committeeType'] == "Co-Chairman") echo 'selected'; ?>>Co-Chairman</option>
                                                    <option value="Member" <?php if($row['committeeType'] == "Member") echo 'selected'; ?>>Member</option>
                                                </select>
                                            </div>
                                        </div>

                                        <hr>

                                         <!-- Image -->
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="color: #098209; font-weight: bold;">Profile Image Upload</h5>
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;">
                                                    <i class="fa fa-paperclip"></i>
                                                </span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="photo" name="photo" accept="image/*" onchange="updateFileName(this)">
                                                <label class="custom-file-label text-truncate" for="photo" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">Choose image</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
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
        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to scrollHeight
        }

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("address");

            // Resize on input
            textarea.addEventListener("input", function() {
                autoResizeTextarea(this);
            });

            // Resize initially in case there's preloaded content
            autoResizeTextarea(textarea);
        });

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("dependents");

            // Resize on input
            textarea.addEventListener("input", function() {
                autoResizeTextarea(this);
            });

            // Resize initially in case there's preloaded content
            autoResizeTextarea(textarea);
        });
    </script>

    
</body>

</html>