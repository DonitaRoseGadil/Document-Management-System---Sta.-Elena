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
        <div class="content-body">
            <div class="container-fluid" >
                <!-- row -->
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8 col-xxl-12 items-center">                        
                        <div class="card" style="align-self: center;">
                            <div class="card-header d-flex justify-content-center">
                                <h4 class="card-title text-center" style="color: #098209; ">ADD RESOLUTION</h4>
                            </div>
                            
                            <div class="card-body">
                                <div class="basic-form">
                                    <form id="myForm" action="addresolution.php" method="post" enctype="multipart/form-data">
                                    <?php
                                        if (isset($_POST['save'])) {
                                            include("connect.php");

                                            $resoNo = $_POST['resoNo'];
                                            $title = $_POST['title'];
                                            $barangay = $_POST['barangay'];
                                            $dateAdopted = $_POST['dateAdopted'];
                                            $authorSponsor = $_POST['authorSponsor'];
                                            $coAuthor = $_POST['coAuthor'];
                                            $remarks = $_POST['remarks'];
                                            $notes = $_POST['notes'];
                                            $dateForwarded = $_POST['dateForwarded'];
                                            $dateSigned = $_POST['dateSigned'];
                                            $spResoNo = $_POST['spResoNo'];
                                            $dateApproved = $_POST['dateApproved'];
                                            $attachmentPath = "";

                                            if (!empty($_FILES['attachment']['name'])) {
                                                $attachmentPath = "uploads/" . basename($_FILES['attachment']['name']);
                                                move_uploaded_file($_FILES['attachment']['tmp_name'], $attachmentPath);
                                            }

                                            // Check if Resolution No. OR Title already exists (case insensitive)
                                            $check_sql = "SELECT * FROM resolution WHERE LOWER(reso_no) = LOWER(?) AND LOWER(title) = LOWER(?)";
                                            $stmt_check = $conn->prepare($check_sql);
                                            $stmt_check->bind_param("ss", $resoNo, $title);
                                            $stmt_check->execute();
                                            $result = $stmt_check->get_result();

                                            if ($result->num_rows > 0) {
                                                // Resolution No. or Title already exists
                                                echo "<script>
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'Duplicate Entry!',
                                                            text: 'The Resolution No. or Title already exists.',
                                                            confirmButtonText: 'OK'
                                                        });
                                                    </script>";
                                            } else {
                                                // Insert new resolution
                                                $sql = "INSERT INTO resolution (reso_no, title, brgy, d_adopted, author_sponsor, co_author, remarks, d_forward, d_signed, sp_resoNo, d_approved, attachment, notes) 
                                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("sssssssssssss", $resoNo, $title, $barangay, $dateAdopted, $authorSponsor, $coAuthor, $remarks, $dateForwarded, $dateSigned, $spResoNo, $dateApproved, $attachmentPath, $notes);

                                                if ($stmt->execute()) {
                                                    $last_id = $conn->insert_id;

                                                    // Insert into History Log
                                                    $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
                                                                VALUES ('Created', 'Resolution', ?, ?)";
                                                    $log_stmt = $conn->prepare($log_sql);
                                                    $log_stmt->bind_param("is", $last_id, $title);
                                                    $log_stmt->execute();
                                                    $log_stmt->close();

                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Resolution Created',
                                                                text: 'The resolution has been successfully created.',
                                                                confirmButtonText: 'OK'
                                                            }).then(() => { window.location.href = 'files-resolution.php'; });
                                                        </script>";
                                                } else {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Error',
                                                                text: 'There was an error creating the resolution.',
                                                                confirmButtonText: 'OK'
                                                            });
                                                        </script>";
                                                }

                                                $stmt->close();
                                            }

                                            $stmt_check->close();
                                            $conn->close();
                                        }
                                        ?>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Resolution No.:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="resoNo" name="resoNo"> 
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Title:</label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." id="title" name="title"></textarea>
                                             </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Barangay:</label>
                                             <div class="col-sm-9">
                                                <select id="barangay" name="barangay" class="form-control" required>
                                                    <option value="" selected>Choose Barangay...</option>
                                                    <option value="Basiad">Basiad</option>
                                                    <option value="Bulala">Bulala</option>
                                                    <option value="Don Tomas">Don Tomas</option>
                                                    <option value="Guitol">Guitol</option>
                                                    <option value="Kabuluan">Kabuluan</option>
                                                    <option value="Kagtalaba">Kagtalaba</option>
                                                    <option value="Maulawin">Maulawin</option>
                                                    <option value="Patag Ibaba">Patag Ibaba</option>
                                                    <option value="Patag Ilaya">Patag Ilaya</option>
                                                    <option value="Plaridel">Plaridel</option>
                                                    <option value="Polangguitguit">Polangguitguit</option>
                                                    <option value="Rizal">Rizal</option>
                                                    <option value="Salvacion">Salvacion</option>
                                                    <option value="San Lorenzo">San Lorenzo</option>
                                                    <option value="San Pedro">San Pedro</option>
                                                    <option value="San Vicente">San Vicente</option>
                                                    <option value="Santa Elena (Pob.)">Santa Elena (Pob.)</option>
                                                    <option value="Tabugon">Tabugon</option>
                                                    <option value="Villa San Isidro">Villa San Isidro</option>
                                                </select>
                                             </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000;">Date Adopted:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="dateAdopted" name="dateAdopted" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Author / Sponsor:</label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." id="authorSponsor" name="authorSponsor"></textarea>
                                             </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Co-Author:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="coAuthor" name="coAuthor">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Status:</label>
                                            <div class="col-sm-9">
                                                <select id="remarks" name="remarks" class="form-control" onchange="toggleDateFields()" disabled>
                                                    <option value="" selected>Choose...</option>
                                                    <option value="Forwarded to LCE">Forwarded to LCE</option>
                                                    <option value="Signed by LCE">Signed by LCE</option>
                                                    <option value="SP Approval">SP Approval</option>
                                                    <option value="Disapprove">Disapprove</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dateFields" style="display: none;">
                                            <div class="form-group row" style="visibility: hidden; opacity: 0;" id="forwardedDateField">
                                                <label class="col-sm-3 col-form-label" style="color:#000000;">Date Forwarded to LCE:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="dateForwarded" name="dateForwarded" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="visibility: hidden; opacity: 0;" id="notesField">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">Remarks/Notes:</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" style="resize: none;" rows="4" placeholder="Please type here..." id="notes" name="notes" disabled></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="visibility: hidden; opacity: 0;" id="signedDateField">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">Date Signed by LCE:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="dateSigned" name="dateSigned" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="visibility: hidden; opacity: 0;" id="spResoNoField">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">SP Resolution No:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" placeholder="Please type here..." id="spResoNo" name="spResoNo" disabled>
                                                </div>
                                            </div>
                                            <div class="form-group row" style="visibility: hidden; opacity: 0;" id="sbApprovalDateField">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">SP Approval:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" id="dateApproved" name="dateApproved" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="attachment" name="attachment" accept=".pdf" onchange="updateFileName()">
                                                <label class="custom-file-label text-truncate" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" for="attachment">Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group row d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Save</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-resolution.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Loading Spinner Overlay -->
            <div id="loadingOverlay" style="display: none; position: fixed; z-index: 9999; background: rgba(0,0,0,0.5); top: 0; left: 0; width: 100%; height: 100%; text-align: center;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                    <div class="spinner"></div>
                    <p style="color: white; font-size: 1.2em;">Please wait...</p>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        document.getElementById("resoNo").addEventListener("input", checkResolutionData);
        // document.getElementById("title").addEventListener("input", checkResolutionData);

        function checkResolutionData() {
            const resoNo = document.getElementById("resoNo").value.trim();
            const title = document.getElementById("title").value.trim();

            // Only proceed if resoNo is filled (title can be empty!)
            if (!resoNo) {
                clearForm();
                showDuplicateEntryMessage(false);
                toggleFields(false);
                return;
            }

        function disableSaveButton(disable) {
            const saveButton = document.getElementById("save_btn");
            if (saveButton) {
                saveButton.disabled = disable;

                // Optional: change button styling to reflect disabled state
                if (disable) {
                    saveButton.style.opacity = "0.6";
                    saveButton.style.cursor = "not-allowed";
                } else {
                    saveButton.style.opacity = "1";
                    saveButton.style.cursor = "pointer";
                }
            }
        }


        fetch("check-resolution.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `reso_no=${encodeURIComponent(resoNo)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                showDuplicateEntryMessage(true);
                fillForm(data);
                toggleFields(true);
                disableSaveButton(true);  // Disable save if duplicate
            } else {
                showDuplicateEntryMessage(false);
                clearForm();
                toggleFields(false);
                disableSaveButton(false); // Enable save if new
            }
        })
        .catch(err => {
            console.error("Error checking resolution:", err);
        });


                    
        }


    // Trigger check on blur of either input field
    // document.getElementById("resoNo").addEventListener("blur", validateBeforeCheck);
    // document.getElementById("title").addEventListener("blur", validateBeforeCheck);

    function showDuplicateEntryMessage(isDuplicate) {
        const resoContainer = document.getElementById("resoNo").parentNode;
        const titleContainer = document.getElementById("title").parentNode;

        // Remove any existing messages first
        const existingResoMsg = document.getElementById("duplicate-reso-msg");
        const existingTitleMsg = document.getElementById("duplicate-title-msg");
        if (existingResoMsg) existingResoMsg.remove();
        if (existingTitleMsg) existingTitleMsg.remove();

        if (isDuplicate) {
            // Add duplicate message under Resolution No.
            const resoMsg = document.createElement("div");
            resoMsg.id = "duplicate-reso-msg";
            resoMsg.className = "text-danger mt-1";
            resoMsg.textContent = "Duplicate Entry for Resolution No.";
            resoContainer.appendChild(resoMsg);

            // Add duplicate message under Title
            const titleMsg = document.createElement("div");
            titleMsg.id = "duplicate-title-msg";
            titleMsg.className = "text-danger mt-1";
            titleMsg.textContent = "Duplicate Entry for Title";
            titleContainer.appendChild(titleMsg);
        }
    }


        function fillForm(data) {
            const titleEl = document.getElementById("title");
            titleEl.value = data.title || "";
            autoExpand({ target: titleEl }); 

            document.getElementById("title").value = data.title || "";
            document.getElementById("dateAdopted").value = data.dateAdopted || "";
            document.getElementById("authorSponsor").value = data.authorSponsor || "";
            document.getElementById("coAuthor").value = data.coAuthor || "";
            document.getElementById("remarks").value = data.remarks || "";
            document.getElementById("dateForwarded").value = data.dateForwarded || "";
            document.getElementById("dateSigned").value = data.dateSigned || "";
            document.getElementById("spResoNo").value = data.spResoNo || "";
            document.getElementById("dateApproved").value = data.dateApproved || "";
            document.getElementById("notes").value = data.notes || "";
        }

        function toggleFields(disable) {
            const fields = [
                "title", "dateAdopted", "authorSponsor", "coAuthor", "remarks",
                "dateForwarded", "dateSigned", "spResoNo", "dateApproved", "notes"
            ];

            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.disabled = disable;

                    if (disable) {
                        // Apply darker background and text color
                        el.style.backgroundColor = "#e9ecef";
                        el.style.color = "#495057";
                        el.style.cursor = "not-allowed";
                    } else {
                        // Reset to default styling
                        el.style.backgroundColor = "";
                        el.style.color = "";
                        el.style.cursor = "";
                    }
                }
            });
        }

        function clearForm() {
            const fields = [
                "title", "dateAdopted", "authorSponsor", "coAuthor", "remarks",
                "dateForwarded", "dateSigned", "spResoNo", "dateApproved", "notes"
            ];

            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = "";
            });
        }

        function updateFileName() {
            const fileInput = document.getElementById('attachment');
            const fileName = fileInput.files[0].name;
            const label = document.querySelector('.custom-file-label');
            label.textContent = fileName;
        }

        // Function to auto-expand the textarea
        function autoExpand(event) {
            const textarea = event.target;
            textarea.style.height = "auto"; 
            textarea.style.height = textarea.scrollHeight + "px"; 
        }

        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.getElementById("title");
            textarea.addEventListener("input", autoExpand);
        });

        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.getElementById("authorSponsor");
            textarea.addEventListener("input", autoExpand);
        });

        function removeFile() {
            const fileInput = document.getElementById("attachment");
            const fileLabel = fileInput.nextElementSibling;
            const errorElement = document.getElementById("attachment-error");

            fileInput.value = ""; // Clear file inputs
            fileLabel.textContent = "Choose file"; // Reset labels
            if (errorElement) errorElement.remove();  // Remove error message if present
        }

        function toggleDateFields() {
            var status = document.getElementById("remarks").value;

            // Hide all fields first
            document.getElementById("forwardedDateField").style.visibility = "hidden";
            document.getElementById("forwardedDateField").style.opacity = "0";     
            document.getElementById("signedDateField").style.visibility = "hidden";
            document.getElementById("signedDateField").style.opacity = "0";
            document.getElementById("sbApprovalDateField").style.visibility = "hidden";
            document.getElementById("sbApprovalDateField").style.opacity = "0";
            document.getElementById("spResoNoField").style.visibility = "hidden";
            document.getElementById("spResoNoField").style.opacity = "0";
            document.getElementById("notesField").style.visibility = "hidden";
            document.getElementById("notesField").style.opacity = "0";
            
            // Show the corresponding field based on selected option
            if (status === "Forwarded to LCE") {
                document.getElementById("forwardedDateField").style.visibility = "visible";
                document.getElementById("forwardedDateField").style.opacity = "1";
                document.getElementById("dateFields").style.display = "block";
                document.getElementById("signedDateField").style.display = "none";
                document.getElementById("spResoNoField").style.display = "none";
                document.getElementById("sbApprovalDateField").style.display = "none";
                document.getElementById("notesField").style.display = "none";
            } else if (status === "Signed by LCE") {
                document.getElementById("forwardedDateField").style.visibility = "visible";
                document.getElementById("forwardedDateField").style.opacity = "1";
                document.getElementById("signedDateField").style.visibility = "visible";
                document.getElementById("signedDateField").style.opacity = "1";
                document.getElementById("dateFields").style.display = "block";
                document.getElementById("signedDateField").style.display = "flex";
                document.getElementById("spResoNoField").style.display = "none";
                document.getElementById("sbApprovalDateField").style.display = "none";
                document.getElementById("notesField").style.display = "none";
            } else if (status === "SP Approval") {
                document.getElementById("forwardedDateField").style.visibility = "visible";
                document.getElementById("forwardedDateField").style.opacity = "1";
                document.getElementById("signedDateField").style.visibility = "visible";
                document.getElementById("signedDateField").style.opacity = "1";
                document.getElementById("sbApprovalDateField").style.visibility = "visible";
                document.getElementById("sbApprovalDateField").style.opacity = "1";
                document.getElementById("spResoNoField").style.visibility = "visible";
                document.getElementById("spResoNoField").style.opacity = "1";
                document.getElementById("dateFields").style.display = "block";
                document.getElementById("forwardedDateField").style.display = "flex";
                document.getElementById("signedDateField").style.display = "flex";
                document.getElementById("spResoNoField").style.display = "flex";
                document.getElementById("sbApprovalDateField").style.display = "flex";
                document.getElementById("notesField").style.display = "none";
            } else if (status === "Disapprove") {
                document.getElementById("dateFields").style.display = "block";
                document.getElementById("forwardedDateField").style.display = "none";
                document.getElementById("signedDateField").style.display = "none";
                document.getElementById("spResoNoField").style.display = "none";
                document.getElementById("sbApprovalDateField").style.display = "none";
                document.getElementById("notesField").style.visibility = "visible";
                document.getElementById("notesField").style.opacity = "1";
                document.getElementById("notesField").style.display = "flex";
                
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const requiredFields = ["resoNo", "title", "barangay", "dateAdopted", "authorSponsor"];

        function validateField(field) {
            let inputElement = document.getElementById(field);
            if (!inputElement) return true; // Skip if field is missing

            let errorElement = document.getElementById(field + "-error");
            let isEmpty = !inputElement.value.trim() || (field === "remarks" && inputElement.value === "Choose...");

            if (isEmpty) {
                if (!errorElement) {
                    let errorMsg = document.createElement("div");
                    errorMsg.id = field + "-error";
                    errorMsg.className = "text-danger mt-1";
                    errorMsg.textContent = "Required field.";
                    inputElement.parentNode.appendChild(errorMsg);
                }
                return false; // Field is invalid
            } else {
                if (errorElement) errorElement.remove();
                return true; // Field is valid
            }
        }

        function validateForm(event) {
            let isValid = true;
            let firstInvalidField = null; // Store the first empty field

            requiredFields.forEach(function (field) {
                if (!validateField(field)) {
                    isValid = false;
                    if (!firstInvalidField) firstInvalidField = field; // Capture the first invalid field
                }
            });

            // Check attachment file
            const fileInput = document.getElementById("attachment");
            const file = fileInput.files[0];

            if (file) {
                const fileName = file.name.toLowerCase();
                const isPdf = fileName.endsWith(".pdf");

                const existingError = document.getElementById("attachment-error");
                if (!isPdf) {
                    isValid = false;

                    if (!existingError) {
                        const errorMsg = document.createElement("div");
                        errorMsg.id = "attachment-error";
                        errorMsg.className = "text-danger mt-1";
                        errorMsg.textContent = "Only PDF files are allowed.";
                        fileInput.parentNode.appendChild(errorMsg);
                    }

                    if (!firstInvalidField) firstInvalidField = "attachment";
                } else {
                    if (existingError) existingError.remove();
                }
            }

            if (!isValid) {
                event.preventDefault(); // Stop submission

                // SweetAlert2 Alert
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'All required fields must be filled out before submitting!',
                    confirmButtonText: 'OK'
                });

                // Scroll to the first invalid field
                if (firstInvalidField) {
                    document.getElementById(firstInvalidField).scrollIntoView({ behavior: "smooth", block: "center" });
                    document.getElementById(firstInvalidField).focus();
                }

                return false;
            }
        }

        // Add validation to fields
        requiredFields.forEach(function (field) {
            let inputElement = document.getElementById(field);
            if (inputElement) {
                inputElement.addEventListener("input", function () { validateField(field); });
                if (field === "remarks") inputElement.addEventListener("change", function () { validateField(field); });
                inputElement.addEventListener("focusout", function () { validateField(field); });
            }
        });

        // Prevent form submission
        form.addEventListener("submit", validateForm);
    });

    function updateMinDate(fieldId, targetIds) {
        let selectedDate = document.getElementById(fieldId).value;
        if (selectedDate) {
            targetIds.forEach(targetId => {
                document.getElementById(targetId).min = selectedDate;
            });
        }
    }

    document.getElementById("dateAdopted").addEventListener("change", function () {
        updateMinDate("dateAdopted", ["dateForwarded", "dateSigned", "dateApproved"]);
    });

    document.getElementById("dateForwarded").addEventListener("change", function () {
        updateMinDate("dateForwarded", ["dateSigned", "dateApproved"]);
    });

    document.getElementById("dateSigned").addEventListener("change", function () {
        updateMinDate("dateSigned", ["dateApproved"]);
    });
    
    </script>

    <script>
    document.getElementById("attachment").addEventListener("change", function () {
    const file = this.files[0];
        if (file) {
            const fileName = file.name.toLowerCase();
            const isPdf = fileName.endsWith(".pdf");
            console.log("Changed file:", fileName, "| PDF?", isPdf);

            const existingError = document.getElementById("attachment-error");
            if (!isPdf) {
                if (!existingError) {
                    const errorMsg = document.createElement("div");
                    errorMsg.id = "attachment-error";
                    errorMsg.className = "text-danger mt-1";
                    errorMsg.textContent = "Only PDF files are allowed.";
                    this.closest('.input-group').parentNode.insertBefore(errorMsg, this.closest('.input-group').nextSibling);
                }
            } else {
                if (existingError) existingError.remove();
            }
        }
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