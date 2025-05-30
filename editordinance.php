<?php

if(isset($_POST['save'])){
    include("connect.php");
    error_reporting(0);

    $id = intval($_POST['id']);
    $moNo = mysqli_real_escape_string($conn, $_POST['moNo']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $dateAdopted = mysqli_real_escape_string($conn, $_POST['dateAdopted']);
    $authorSponsor = mysqli_real_escape_string($conn, $_POST['authorSponsor']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    $dateForwarded = mysqli_real_escape_string($conn, $_POST['dateForwarded']);
    $dateSigned = mysqli_real_escape_string($conn, $_POST['dateSigned']);
    $spResoNo = mysqli_real_escape_string($conn, $_POST['spResoNo']);
    $dateApproved = mysqli_real_escape_string($conn, $_POST['dateApproved']);

    // Handle file uploads
    $attachment = $_FILES['attachment']['name'];

    $uploadDir = "uploads/";  // Define upload directory
    if (!empty($attachment)) {
        $attachmentPath = $uploadDir . basename($attachment);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);
    } else {
        // Keep the old file if no new file is uploaded
        $attachmentQuery = "SELECT attachment FROM ordinance WHERE id = $id";
        $result = mysqli_query($conn, $attachmentQuery);
        $row = mysqli_fetch_assoc($result);
        $attachmentPath = $row['attachment'];
    }
    $sql = "UPDATE `ordinance` SET 
                    `mo_no`='$moNo', 
                    `title`='$title', 
                    `date_adopted`='$dateAdopted', 
                    `author_sponsor`='$authorSponsor', 
                    `date_fwd`='$dateForwarded',
                    `date_signed`='$dateSigned',
                    `sp_resoNo`='$spResoNo',
                    `sp_approval`='$dateApproved',
                    `remarks`='$remarks',
                    `notes`='$notes',
                    `attachment`='$attachmentPath' WHERE id = $id";

    $query = mysqli_query($conn, $sql);
    
    $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
    VALUES ('Edited', 'Ordinance', $id, '$title')";
    $conn->query($log_sql);

    if($query) {
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ordinance Updated',
                        text: 'The ordinance has been successfully updated.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'files-ordinances.php';
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
                        text: 'There was an error updating the ordinance.',
                        confirmButtonText: 'OK'
                    });
                });
              </script>";
        header("Location: files-ordinances.php");
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
        const requiredFields = ["moNo", "title", "dateAdopted", "authorSponsor"];

        function validateField(field) {
            let inputElement = document.getElementById(field);
            let errorElement = document.getElementById(field + "-error");

            if (!inputElement.value.trim() || (field === "remarks" && inputElement.value === "Choose...")) {
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
                if (field === "remarks") {
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
                    (field === "remarks" && document.getElementById(field).value === "Choose...")) {
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
                                <h4 class="card-title text-center" style="color: #098209; ">EDIT ORDINANCE</h4>
                            </div>
                            <?php 
                                include "connect.php";
                                $id = $_GET['id'];
                                $sql = "SELECT * FROM ordinance WHERE id = $id LIMIT 1";
                                $result= mysqli_query($conn, $sql);   
                                $row = mysqli_fetch_assoc($result); 

                                $sql2 = "SELECT remarks FROM ordinance WHERE id = '$id'";
                                $result2 = mysqli_query($conn, $sql2);
                                $row2 = mysqli_fetch_assoc($result2);

                                $selectedRemarks = $row2['remarks']; 
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
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Resolution No. / MO No.: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['mo_no']?>" id="moNo" name="moNo">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Title:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="title" name="title" rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars_decode($row['title']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Date Adopted:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" value="<?php echo $row['date_adopted']?>" id="dateAdopted" name="dateAdopted">
                                            </div>
                                        </div>
                                        <!-- <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Description:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" style="resize: none;" rows="4" value=" id="description" name="description"></textarea>
                                            </div>
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Author / Sponsor:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="authorSponsor" name="authorSponsor" rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars_decode($row['author_sponsor']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Status:</label>
                                            <div class="col-sm-9">
                                                <select id="remarks" name="remarks" class="form-control" onchange="toggleDateFields()">
                                                    <option value="" <?php echo ($selectedRemarks == '') ? 'selected' : ''; ?>>Choose...</option>
                                                    <option value="Forwarded to LCE" <?php echo ($selectedRemarks == 'Forwarded to LCE') ? 'selected' : ''; ?>
                                                        <?php echo ($selectedRemarks == 'Signed by LCE' || $selectedRemarks == 'SP Approval') ? 'selected' : ''; ?>>
                                                        Forwarded to LCE
                                                    </option>
                                                    <option value="Signed by LCE" <?php echo ($selectedRemarks == 'Signed by LCE') ? 'selected' : ''; ?>
                                                        <?php echo ($selectedRemarks == 'SP Approval') ? 'selected' : ''; ?>>
                                                        Signed by LCE
                                                    </option>
                                                    <option value="SP Approval" <?php echo ($selectedRemarks == 'SP Approval') ? 'selected' : ''; ?>>
                                                        SP Approval
                                                    </option>
                                                    <option value="Disapprove" <?php echo ($selectedRemarks == 'Disapprove') ? 'selected' : ''; ?>>Disapprove</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="dateFields">
                                            <div class="form-group row" id="forwardedDateField" style="display: none;">
                                                <label class="col-sm-3 col-form-label" style="color:#000000;">Date Forwarded to LCE:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" value="<?php echo $row['date_fwd']?>" id="dateForwarded" name="dateForwarded">
                                                </div>
                                            </div>
                                            <div class="form-group row" id="notesField" style="display: none;">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">Remarks/Notes:</label>
                                                <div class="col-sm-9">
                                                    <textarea class="form-control" id="notes" name="notes" rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars_decode($row['notes']); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row" id="signedDateField" style="display: none;">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">Date Signed by LCE:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" value="<?php echo $row['date_signed']?>" id="dateSigned" name="dateSigned">
                                                </div>
                                            </div>
                                            <div class="form-group row" id="spResoNoField" style="display: none;">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">SP Resolution No:</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" value="<?php echo $row['sp_resoNo']?>" id="spResoNo" name="spResoNo">
                                                </div>
                                            </div>
                                            <div class="form-group row" id="sbApprovalDateField" style="display: none;">
                                                <label class="col-sm-3 col-form-label" style="color:#000000">SP Approval:</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" value="<?php echo $row['sp_approval']?>" id="dateApproved" name="dateApproved">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="attachment" value="attachment" name="attachment" onchange="updateFileName('attachmentLabel')">
                                                <label class="custom-file-label" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" id="attachmentLabel"> 
                                                    <?php echo !empty($row['attachment']) ? $row['attachment'] : "Choose file"; ?>
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group row d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Update</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-ordinances.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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
            const fileInput = document.getElementById("attachment");
            const fileLabel = fileInput.nextElementSibling;

            fileInput.value = ""; // Clear file inputs
            fileLabel.textContent = "Choose file"; // Reset labels
        }

        document.addEventListener("DOMContentLoaded", function () {
        toggleDateFields(); 
        });

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


        function toggleDateFields() {
            var status = document.getElementById("remarks").value;

            document.getElementById("forwardedDateField").style.display = "none";
            document.getElementById("signedDateField").style.display = "none";
            document.getElementById("spResoNoField").style.display = "none";
            document.getElementById("sbApprovalDateField").style.display = "none";
            document.getElementById("notesField").style.display = "none";


            if (status === "Forwarded to LCE") {
                document.getElementById("forwardedDateField").style.display = "flex";
            } else if (status === "Signed by LCE") {
                document.getElementById("forwardedDateField").style.display = "flex";
                document.getElementById("signedDateField").style.display = "flex";
            } else if (status === "SP Approval") {
                document.getElementById("forwardedDateField").style.display = "flex";
                document.getElementById("signedDateField").style.display = "flex";
                document.getElementById("spResoNoField").style.display = "flex";
                document.getElementById("sbApprovalDateField").style.display = "flex";
            } else if (status === "Disapprove") {
                document.getElementById("notesField").style.display = "flex";
            }

        }

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