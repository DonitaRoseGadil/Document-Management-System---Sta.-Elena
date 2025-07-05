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
                                <h4 class="card-title text-center" style="color: #098209; ">ADD COMMITTEE REPORT</h4>
                            </div>
                            
                            <div class="card-body">
                                <div class="basic-form">
                                    <form id="myForm" action="addCommitteeReports.php" method="post" enctype="multipart/form-data">
                                    <?php
                                        if (isset($_POST['save'])) {
                                            include("connect.php");

                                            $title = $_POST['title'];
                                            $committee_category = $_POST['committee_category'];
                                            $committee_section = $_POST['committee_section'];
                                            $councilor = $_POST['councilor'];
                                            $date_report = $_POST['date_report'];
                                            $attachmentPath = "";

                                            if (!empty($_FILES['attachment']['name'])) {
                                                $attachmentPath = "uploads/" . basename($_FILES['attachment']['name']);
                                                move_uploaded_file($_FILES['attachment']['tmp_name'], $attachmentPath);
                                            }

                                            // Check if   already exists (case insensitive)
                                            $check_sql = "SELECT * FROM committee_reports WHERE LOWER(title) = LOWER(?)";
                                            $stmt_check = $conn->prepare($check_sql);
                                            $stmt_check->bind_param("s", $title);
                                            $stmt_check->execute();
                                            $result = $stmt_check->get_result();

                                            if ($result->num_rows > 0) {
                                                // title already exists
                                                echo "<script>
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'Duplicate Entry!',
                                                            text: 'The Report No. or Title already exists.',
                                                            confirmButtonText: 'OK'
                                                        });
                                                    </script>";
                                            } else {
                                                // Insert new title
                                                $sql = "INSERT INTO committee_reports (title, committee_category, committee_section, councilor, date_report, attachment_path)
                                                        VALUES (?, ?, ?, ?, ?, ?)";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("ssssss", $title, $committee_category, $committee_section, $councilor, $date_report, $attachmentPath);

                                                if ($stmt->execute()) {
                                                    $last_id = $conn->insert_id;

                                                    // Insert into History Log
                                                    $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
                                                                VALUES ('Created', 'Committee Report', ?, ?)";
                                                    $log_stmt = $conn->prepare($log_sql);
                                                    $log_stmt->bind_param("is", $last_id, $title);
                                                    $log_stmt->execute();
                                                    $log_stmt->close();

                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Report Created',
                                                                text: 'The report has been successfully created.',
                                                                confirmButtonText: 'OK'
                                                            }).then(() => { window.location.href = 'files-committee-report.php'; });
                                                        </script>";
                                                } else {
                                                    echo "<script>
                                                            Swal.fire({
                                                                icon: 'error',
                                                                title: 'Error',
                                                                text: 'There was an error creating the report.',
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
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Title:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="title" name="title"> 
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Councilor:</label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." id="councilor" name="councilor"></textarea>
                                             </div>
                                        </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000;">Date Reported:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" id="date_report" name="date_report">
                                            </div>
                                        </div>

                                        <!-- Committee Category -->
                                        <div class="form-group row">
                                            <label for="committee_category" class="col-sm-3 col-form-label" style="color:#000000;">Committee Category:</label>
                                            <div class="col-sm-9">
                                                <select id="committee_category" name="committee_category" class="form-control" onchange="filterCommittees()" required>
                                                    <option value="" disabled selected>Select Committee Category</option>
                                                    <option value="Economic">Economic</option>
                                                    <option value="Social">Social</option>
                                                    <option value="Infrastructure">Infrastructure</option>
                                                    <option value="Institutional">Institutional</option>
                                                    <option value="Environmental">Environmental</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Committee Section -->
                                        <div class="form-group row">
                                            <label for="committee_section" class="col-sm-3 col-form-label" style="color:#000000;">Committee Section:</label>
                                            <div class="col-sm-9">
                                                <select id="committee_section" name="committee_section" class="form-control" required>
                                                    <option value="" disabled selected>Select Committee</option>
                                                </select>
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
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-committee-report.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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
        document.getElementById("title").addEventListener("input", checkReportData);

        function checkReportData() {
            const title = document.getElementById("title").value.trim();

            // Only proceed if subject is filled
            if (!title) {
                clearForm();
                showDuplicateEntryMessage(false);
                toggleFields(false);
                //disableSaveButton(false); 
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

            fetch("check-report.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `title=${encodeURIComponent(title)}`
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
                console.error("Error checking title:", err);
            });
        }


        const committees = {
        Economic: [
            "Committee on Market and Slaughterhouse",
            "Committee on Cooperatives, People’s Organization and Non-Government Organizations",
            "Committee on Tourism",
            "Committee on Finance, Budget and Appropriations",
            "Committee on Agriculture and Livelihood",
            "Committee on Trade, Commerce and Industry",
            "Committee on Public Utilities and Facilities"
        ],
        Social: [
            "Committee on Youth and SK Affairs",
            "Committee on Sports",
            "Committee on Games and Amusement",
            "Committee on Housing and Land Utilization",
            "Committee on Education, Culture and Arts",
            "Committee on Peace and Order and Public Safety",
            "Committee on Health, Sanitation and Social Welfare",
            "Committee on Women, Family and Human Rights"
        ],
        Infrastructure: [
            "Committee on Public Works and Infrastructure"
        ],
        Institutional: [
            "Committee on Good Government, Public Ethics and Accountability",
            "Committee on Rules and Legal Matters",
            "Committee on Barangay Affairs"
        ],
        Environmental: [
            "Committee on Environmental Protection"
        ]

    };


    function filterCommittees() {
        const category = document.getElementById("committee_category").value;
        const sectionSelect = document.getElementById("committee_section");

        // Clear previous options
        sectionSelect.innerHTML = `<option value="" disabled selected>Select Committee</option>`;

        if (committees[category]) {
            committees[category].forEach(function(committee) {
                const option = document.createElement("option");
                option.value = committee;
                option.textContent = committee;
                sectionSelect.appendChild(option);
            });
        }
    }


    // Trigger check on blur of either input field
    // document.getElementById("title").addEventListener("blur", validateBeforeCheck);

    function showDuplicateEntryMessage(isDuplicate) {
        const titleContainer = document.getElementById("title").parentNode;

        // Remove any existing messages first
        const existingTitleMsg = document.getElementById("duplicate-title-msg");
        if (existingTitleMsg) existingTitleMsg.remove();

        if (isDuplicate) {
            // Add duplicate message under Subject
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
            document.getElementById("committee_category").value = data.committee_category || "";

                filterCommittees();

                setTimeout(() => {
                    const sectionSelect = document.getElementById("committee_section");
                    const targetValue = data.committee_section || "";

                    
                    const optionExists = [...sectionSelect.options].some(opt => opt.value === targetValue);
                    if (optionExists) {
                        sectionSelect.value = targetValue;
                    } else {
                        sectionSelect.selectedIndex = 0; 
                        console.warn("Committee section not found in options:", targetValue);
                    }
                }, 0); 

            document.getElementById("title").value = data.title || "";
            // document.getElementById("committee_category").value = data.committee_category || "";
            // document.getElementById("committee_section").value = data.committee_section || "";
            document.getElementById("councilor").value = data.councilor || "";
            document.getElementById("date_report").value = data.date_report || "";
        }

        function toggleFields(disable) {
            const fields = [
                "committee_category", "committee_section", "councilor", "date_report"
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
            "committee_category", "committee_section", "councilor", "date_report"
        ];

        fields.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (el.tagName === "SELECT") {
                    el.selectedIndex = 0;
                } else {
                    el.value = "";
                }
            }
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
            const textarea = document.getElementById("councilor");
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

        
        document.addEventListener("DOMContentLoaded", function () {
        const form = document.querySelector("form");
        const requiredFields = ["title", "committee_category", "committee_section", "councilor", "date_report",];

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