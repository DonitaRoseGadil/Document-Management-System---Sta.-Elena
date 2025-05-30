<?php

    if(isset($_POST['save'])) {
        include("connect.php");
        error_reporting(0);

        $id = intval($_POST['id']);
        $no_regSession = mysqli_real_escape_string($conn, $_POST['no_regSession']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $resNo = mysqli_real_escape_string($conn, $_POST['resNo']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $optRadio = $_POST['optradio'];

        // Initialize fields
        $returnNo = mysqli_real_escape_string($conn, $_POST['returnNo']);
        $returnDate = mysqli_real_escape_string($conn, $_POST['returnDate']);
        $resolutionNo = mysqli_real_escape_string($conn, $_POST['resolutionNo']);
        $resolutionDate = mysqli_real_escape_string($conn, $_POST['resolutionDate']);

        // Use switch case to handle conditions
        switch ($optRadio) {
            case 'Approved':
                $returnNo = "";
                $returnDate = "";
                break;
            case 'Returned':
                $resolutionNo = "";
                $resolutionDate = "";
                break;
            default:
                $returnNo = "";
                $returnDate = "";
                $resolutionNo = "";
                $resolutionDate = "";
                break;
        }

        // Handle file uploads
        $genAttachment = $_FILES['genAttachment']['name'];
        $attachment = $_FILES['attachment']['name'];

        $uploadDir = "uploads/";  // Define upload directory

        if (!empty($genAttachment)) {
            $genAttachmentPath = $uploadDir . basename($genAttachment);
            move_uploaded_file($_FILES["genAttachment"]["tmp_name"], $genAttachmentPath);
        } else {
            // Keep the old file if no new file is uploaded
            $genAttachmentQuery = "SELECT genAttachment FROM minutes WHERE id = $id";
            $result = mysqli_query($conn, $genAttachmentQuery);
            $row = mysqli_fetch_assoc($result);
            $genAttachmentPath = $row['genAttachment'];
        }

        if (!empty($attachment)) {
            $attachmentPath = $uploadDir . basename($attachment);
            move_uploaded_file($_FILES["attachment"]["tmp_name"], $attachmentPath);
        } else {
            // Keep the old file if no new file is uploaded
            $attachmentQuery = "SELECT attachment FROM minutes WHERE id = $id";
            $result = mysqli_query($conn, $attachmentQuery);
            $row = mysqli_fetch_assoc($result);
            $attachmentPath = $row['attachment'];
        }

        // Update query
        $sql = "UPDATE `minutes` SET 
                `no_regSession` = '$no_regSession',
                `date` = '$date',
                `genAttachment` = '$genAttachmentPath',
                `resNo` = '$resNo',
                `title` = '$title',
                `status` = '$status',
                `returnNo` = '$returnNo',
                `returnDate` = '$returnDate',
                `resolutionNo` = '$resolutionNo',
                `resolutionDate` = '$resolutionDate',
                `attachment` = '$attachmentPath' WHERE `id` = $id";

        $query = mysqli_query($conn, $sql);

        $log_sql = "INSERT INTO history_log (action, file_type, status, file_id, title) 
        VALUES ('Edited', 'Minutes', '$status', $id, '$title')";
        $conn->query($log_sql);

        if ($query) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Meeting Minutes Updated',
                    text: 'The minutes have been successfully updated.',
                    showCancelButton: true,
                    confirmButtonText: 'Continue Editing',
                    cancelButtonText: 'Return to Minutes Table'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        window.location.href = 'files-meetingminutes.php';
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
                            text: 'There was an error updating the minutes.',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
            header("Location: files-meetingminutes.php");
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<?php 
    include "header.php"; 
    include "connect.php";

?>

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
                                <h4 class="card-title text-center" style="color: #098209; ">EDIT ORDER OF BUSINESS</h4>
                            </div>
                            <?php
                                include("connect.php");
                                $id = intval($_GET['id']); // Ensure ID is an integer
                                $sql = "SELECT * FROM `minutes` WHERE id = $id LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                } else {
                                    echo "<script>alert('Invalid Record ID!'); window.location.href='files-meetingminutes.php';</script>";
                                    exit;
                                }

                                // Determine the value for optRadio based on conditions
                                $optRadio = "";
                                if ($row['resolutionNo'] != "" && $row['resolutionDate'] != "") {
                                    $optRadio = "Approved";
                                } elseif ($row['returnNo'] != "" &&  $row['returnDate'] != "") {
                                    $optRadio = "Returned";
                                } 
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
                                            <label class="col-sm-3 col-form-label" style="color: #000000">No. of Regular Session</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." value="<?php echo $row['no_regSession']?>" id="no_regSession" name="no_regSession">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Date:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" placeholder="Please type here..." value="<?php echo $row['date']?>" id="date" name="date">
                                            </div>
                                        </div>
                                        <label style="color: #000000">Upload Attachment for Order of Business:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" value="genAttachment" id="genAttachment" name="genAttachment" onchange="updateFileName('genAttachmentLabel')">
                                                <label class="custom-file-label" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" id="genAttachmentLabel"> 
                                                    <?php echo !empty($row['genAttachment']) ? $row['genAttachment'] : "Choose file"; ?>
                                                </label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Item No.:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['resNo']?>" name="resNo" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Title:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="title" name="title" rows="1" style="resize: none; overflow: hidden;"><?php echo htmlspecialchars_decode($row['title']); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Status:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['status']?>" id="status" name="status" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Select option if applicable:</label>
                                            <div class="col-sm-9 mt-2">
                                                <label class="radio-inline mr-5" style="color: #000000">
                                                    <input type="radio" name="optradio" class="deselectable-radio" value="Returned" <?php echo ($optRadio === "Returned") ? 'checked' : ''; ?>> Returned
                                                </label>
                                                <label class="radio-inline" style="color: #000000">
                                                    <input type="radio" name="optradio" class="deselectable-radio" value="Approved" <?php echo ($optRadio === "Approved") ? 'checked' : ''; ?>> Approved
                                                </label>
                                            </div>
                                        </div>
                                        <!-- Hidden fields section -->
                                        <div class="form-group row extra-fields mt-3" style="display: none;">
                                            <div class="col-sm-12" id="extraFields">
                                                <!-- Dynamic fields go here -->
                                            </div>
                                        </div>
                                        <label style="color: #000000">Upload Attachment as Supporting Documents:</label>
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
                                                <button class="btn btn-danger" type="button" onclick="removeFile2()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group row d-flex justify-content-center mt-5">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Update</button>
                                            <a href="files-meetingminutes.php" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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
    </script>

    <script>
        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to scrollHeight
        }

        function removeFile() {
            const fileInput = document.getElementById("genAttachment");
            const fileLabel = fileInput.nextElementSibling;

            fileInput.value = ""; // Clear file inputs
            fileLabel.textContent = "Choose file"; // Reset labels
        }

        function removeFile2() {
            const fileInput = document.getElementById("attachment");
            const fileLabel = fileInput.nextElementSibling;

            fileInput.value = ""; // Clear file inputs
            fileLabel.textContent = "Choose file"; // Reset labels
        }

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("title");

            // Resize on input
            textarea.addEventListener("input", function() {
                autoResizeTextarea(this);
            });

            // Resize initially in case there's preloaded content
            autoResizeTextarea(textarea);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll("input[name='optradio']");
            const extraFieldsContainer = document.querySelector("#extraFields");
            const extraFieldsRow = document.querySelector(".extra-fields");
            const statusInputField = document.querySelectorAll("input[name='status']");
            const statusInput = document.querySelector("#status");

            function showExtraFields(selectedValue) {
                extraFieldsRow.style.display = "block";

                if (selectedValue === "Returned") {
                    extraFieldsContainer.innerHTML = `
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Return No.:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo isset($row['returnNo']) ? $row['returnNo'] : ''; ?>" name="returnNo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Return Date:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" value="<?php echo isset($row['returnDate']) ? $row['returnDate'] : ''; ?>" name="returnDate">
                            </div>
                        </div>
                    `;
                } else if (selectedValue === "Approved") {
                    extraFieldsContainer.innerHTML = `
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Resolution No.:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo isset($row['resolutionNo']) ? $row['resolutionNo'] : ''; ?>" name="resolutionNo">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Resolution Date Approved:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" value="<?php echo isset($row['resolutionDate']) ? $row['resolutionDate'] : ''; ?>" name="resolutionDate">
                            </div>
                        </div>
                    `;
                }
            }

            // Check if a radio button is already selected on page load
            const selectedRadio = document.querySelector("input[name='optradio']:checked");
            if (selectedRadio) {
                showExtraFields(selectedRadio.value);
            } else {
                extraFieldsRow.style.display = "none";
            }

            // Add event listeners to radio buttons
            radios.forEach(radio => {
                radio.addEventListener('click', function () {
                    if (this.checked) {
                        if (this.previousChecked) {
                            this.checked = false;
                            this.previousChecked = false;
                            extraFieldsRow.style.display = "none";
                            extraFieldsContainer.innerHTML = "";
                            statusInput.value = "";
                        } else {
                            radios.forEach(r => r.previousChecked = false);
                            this.previousChecked = true;
                            showExtraFields(this.value);
                            statusInput.value = this.value;
                        }
                    }
                });
            });
        });
    </script>

    
    
</body>

</html>