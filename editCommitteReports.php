<?php

if(isset($_POST['save'])){
    include("connect.php");
    error_reporting(0);

    $id = intval($_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $committee_category = mysqli_real_escape_string($conn, $_POST['committee_category']);
    $committee_section = mysqli_real_escape_string($conn, $_POST['committee_section']);
    $councilor = mysqli_real_escape_string($conn, $_POST['councilor']);
    $date_report = mysqli_real_escape_string($conn, $_POST['date_report']);

    // Handle file uploads
    $attachment_path = $_FILES['attachment_path']['name'];

    $uploadDir = "uploads/";  // Define upload directory
    if (!empty($attachment_path)) {
        $attachmentPath = $uploadDir . basename($attachment_path);
        move_uploaded_file($_FILES["attachment_path"]["tmp_name"], $attachmentPath);
    } else {
        // Keep the old file if no new file is uploaded
        $attachmentQuery = "SELECT attachment_path FROM committee_reports WHERE id = $id";
        $result = mysqli_query($conn, $attachmentQuery);
        $row = mysqli_fetch_assoc($result);
        $attachmentPath = $row['attachment_path'];
    }
    $sql = "UPDATE `committee_reports` SET 
                    
                    `title`='$title', 
                    `committee_category`='$committee_category',
                    `committee_section`='$committee_section',
                    `councilor`='$councilor',
                    `date_report`='$date_report',
                    `attachment_path`='$attachmentPath' WHERE id = $id";

    $query = mysqli_query($conn, $sql);
    
    $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
    VALUES ('Edited', 'Committee Report', $id, '$title')";
    $conn->query($log_sql);

    if($query) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Report Updated',
                        text: 'The report has been successfully updated.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'files-committee-report.php';
                        }
                    });
                });
              </script>";    
    } else {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'There was an error updating the report.',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
        header("Location: files-committee-report.php");
        exit;    
    }
}    
?>

<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>

<head>
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const requiredFields = ["title", "committee_category", "committee_section", "councilor", "date_report"];

    function validateField(field) {
        const inputElement = document.getElementById(field);
        const errorElement = document.getElementById(field + "-error");

        const isDropdown = field === "committee_category" || field === "committee_section";
        const isEmpty = !inputElement.value.trim() || (isDropdown && inputElement.value === "");

        if (isEmpty) {
            if (!errorElement) {
                const errorMsg = document.createElement("div");
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

    requiredFields.forEach(function (field) {
        const inputElement = document.getElementById(field);
        if (!inputElement) return;

        inputElement.addEventListener("input", function () {
            validateField(field);
        });

        inputElement.addEventListener("change", function () {
            validateField(field);
        });

        inputElement.addEventListener("focusout", function () {
            validateField(field);
        });
    });

    document.querySelector("form").addEventListener("submit", function (event) {
        let isValid = true;

        requiredFields.forEach(function (field) {
            validateField(field);

            const inputElement = document.getElementById(field);
            const isDropdown = field === "committee_category" || field === "committee_section";
            const isEmpty = !inputElement.value.trim() || (isDropdown && inputElement.value === "");

            if (isEmpty) {
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
                <!-- row -->
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8 col-xxl-12 items-center">                        
                        <div class="card" style="align-self: center;">
                            <div class="card-header d-flex justify-content-center">
                                <h4 class="card-title text-center" style="color: #098209; ">EDIT COMMITTEE REPORT</h4>
                            </div>
                            <?php 
                                include "connect.php";
                                $id = $_GET['id'];
                                $sql = "SELECT * FROM committee_reports WHERE id = $id LIMIT 1";
                                $result= mysqli_query($conn, $sql);   
                                $row = mysqli_fetch_assoc($result); 

                                $sql2 = "SELECT committee_category, committee_section FROM committee_reports WHERE id = '$id'";
                                $result2 = mysqli_query($conn, $sql2);
                                $row2 = mysqli_fetch_assoc($result2);

                                $selectedCategory = $row['committee_category'];
                                $selectedSection = $row['committee_section'];

                            ?>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                            <div class="col-sm-9">
                                                <input type="hidden" class="form-control" value="<?php echo $row['id']?>" id="id" name="id">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Title:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="title" name="title" rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars_decode($row['title']); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Committee Category:</label>
                                            <div class="col-sm-9">
                                                <select id="committee_category" name="committee_category" class="form-control" onchange="filterCommittees()" required>
                                                    <option value="" disabled selected>Choose Category</option>
                                                    <option value="Economic" <?php if($row['committee_category'] == 'Economic') echo 'selected'; ?>>Economic</option>
                                                    <option value="Social" <?php if($row['committee_category'] == 'Social') echo 'selected'; ?>>Social</option>
                                                    <option value="Infrastructure" <?php if($row['committee_category'] == 'Infrastructure') echo 'selected'; ?>>Infrastructure</option>
                                                    <option value="Intitutional" <?php if($row['committee_category'] == 'Institutional') echo 'selected'; ?>>Institutional</option>
                                                    <option value="Environmental" <?php if($row['committee_category']  == 'Environment') echo 'selected'; ?>>Environment</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Committee Section:</label>
                                            <div class="col-sm-9">
                                                <select id="committee_section" name="committee_section" class="form-control" required>
                                                    <option value="" disabled selected>Select Committee</option>
                                                    <!-- JS will populate this using filterCommittees() -->
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Councilor:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['councilor'] ?? ''; ?>" id="councilor" name="councilor">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Date Reported:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" value="<?php echo $row['date_report'] ?? ''; ?>" id="date_report" name="date_report">
                                            </div>
                                        </div>

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="attachment_path" value="attachment" name="attachment_path" onchange="updateFileName('attachmentLabel')">
                                                <label class="custom-file-label" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" id="attachmentLabel"> 
                                                    <?php echo !empty($row['attachment_path']) ? $row['attachment_path'] : "Choose file"; ?>
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-group row d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Update</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-committee-report.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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
        function updateFileName(labelId) {
            const fileInput = document.getElementById(labelId.replace("Label", ""));
            const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : "Choose file";
            document.getElementById(labelId).textContent = fileName;
        }

        function removeFile() {
            const fileInput = document.getElementById("attachment_path");
            const fileLabel = fileInput.nextElementSibling;

            fileInput.value = ""; // Clear file inputs
            fileLabel.textContent = "Choose file"; // Reset labels
        }

        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to scrollHeight
        }

        document.addEventListener("DOMContentLoaded", function() {
            const textareas = [document.getElementById("title"), document.getElementById("authorSponsor")];

            textareas.forEach(textarea => {
                if (textarea) {
                    // Resize on input
                    textarea.addEventListener("input", function() {
                        autoResizeTextarea(this);
                    });

                    // Resize initially in case there's preloaded content
                    autoResizeTextarea(textarea);
                }
            });
        });

        function filterCommittees() {
            document.addEventListener("DOMContentLoaded", function () {
                const selectedSection = "<?php echo addslashes($selectedSection); ?>";

                filterCommittees(); // Populate based on selected category

                setTimeout(() => {
                    const sectionSelect = document.getElementById("committee_section");
                    const option = [...sectionSelect.options].find(opt => opt.value === selectedSection);
                    if (option) sectionSelect.value = selectedSection;
                }, 0);
            });

            const category = document.getElementById("committee_category").value;
            const sectionSelect = document.getElementById("committee_section");

            // Clear existing options
            sectionSelect.innerHTML = '<option value="" disabled selected>Select Committee</option>';

            // Define committees based on categories
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

            if (committees[category]) {
                committees[category].forEach(function(committee) {
                    const option = document.createElement("option");
                    option.value = committee;
                    option.textContent = committee;
                    sectionSelect.appendChild(option);
                });
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
        const selectedSection = "<?php echo addslashes($selectedSection); ?>";

        filterCommittees(); // First, populate committee_section options

        setTimeout(() => {
            const sectionSelect = document.getElementById("committee_section");
            const option = [...sectionSelect.options].find(opt => opt.value === selectedSection);
            if (option) sectionSelect.value = selectedSection;
        }, 0);
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


