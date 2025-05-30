<?php
    if(isset($_POST['save'])){
        include "connect.php";
        error_reporting(0);

        $no_regSession = $_POST['no_regSession'];
        $date = $_POST['date'];

        // Upload General Attachment
        $genAttachmentPath  = "";
        if (!empty($_FILES['genAttachment']['name'])) {
            $genAttachmentPath = "uploads/" . basename($_FILES['genAttachment']['name']);
            move_uploaded_file($_FILES['genAttachment']['tmp_name'], $genAttachmentPath);
        }

        // Check if attachments exist
        if (isset($_POST['resNo']) && is_array($_POST['resNo'])) {
            foreach ($_POST['resNo'] as $key => $resNo) {
                $title = $_POST['title'][$key];
                $status = $_POST['status'][$key];
                $returnNo = $_POST['returnNo'][$key]; 
                $resolutionNo = $_POST['resolutionNo'][$key]; 
                $returnDate = $_POST['returnDate'][$key]; 
                $resolutionDate = $_POST['resolutionDate'][$key];

                // Handle attachment for each resolution
                $attachmentPath = "";
                if (!empty($_FILES['attachment']['name'][$key])) {
                    $attachmentName = $_FILES['attachment']['name'][$key];
                    $attachmentTmpName = $_FILES['attachment']['tmp_name'][$key];
                    $attachmentPath = "uploads/" . basename($attachmentName);
                    move_uploaded_file($attachmentTmpName, $attachmentPath);
                }

                // Insert into database
                $sql = "INSERT INTO minutes (no_regSession, date, genAttachment, resNo, title, status, returnNo, resolutionNo, attachment, returnDate, resolutionDate) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssss", $no_regSession, $date, $genAttachmentPath, $resNo, $title, $status, $returnNo, $resolutionNo, $attachmentPath, $returnDate, $resolutionDate);
                $stmt->execute();

                $log_sql = "INSERT INTO history_log (action, file_type, file_id, title, status) 
                VALUES ('Created', 'Minutes', LAST_INSERT_ID(), ?, ?)";

                $log_stmt = $conn->prepare($log_sql);
                $log_stmt->bind_param("ss", $title, $status);
                $log_stmt->execute();
    
            }
        }       

        if ($stmt) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Minutes Created',
                            text: 'The minutes have been successfully created.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
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
                            text: 'There was an error creating the minutes.',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
            header("Location: files-meetingminutes.php");
            exit;    
        }

        $stmt->close();
        $conn->close();
    }

?>

<!DOCTYPE html>
<html lang="en">

<?php 
    include "header.php"; 
    include "connect.php";

?>

<head>
    <style>
        .card {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>


</head>


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
                                <h4 class="card-title text-center" style="color: #098209; ">ADD ORDER OF BUSINESS</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">No. of Regular Session</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="no_regSession" name="no_regSession" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Date:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" placeholder="Please type here..." id="date" name="date" required>
                                            </div>
                                        </div>
                                        <label style="color: #000000">Upload Attachment for Order of Business:</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="genAttachment" name="genAttachment" onchange="updateFileName(this)">
                                                <label class="custom-file-label text-truncate" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" for="genAttachment">Choose file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                                            <h5 style="color: #098209;">AGENDA ITEM</h5>
                                            <div class="d-flex align-items-center">
                                                <input type="number" id="formCountInput" class="form-control mr-2" min="1" max="50" style="width: 60px; outline: 1px solid #098209;" placeholder="0">
                                                <button type="button" class="btn btn-primary" id="add-card-btn" value="Save Data" style="background-color: #098209; border: none; color: #FFFFFF; height: 4vh;"><i class="fa fa-plus"></i> Form</button>
                                            </div>
                                        </div>
                                        <div id="dynamic-form-container">
                                            <!-- Dynamic cards will be appended here -->
                                        </div>
                                        <div class="form-group row d-flex justify-content-center mt-5">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Save</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="files-meetingminutes.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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

        document.addEventListener("DOMContentLoaded", function() {
            addDynamicCard(); // Add a default card when the page loads
        });

        document.getElementById("add-card-btn").addEventListener("click", function() {
            // let count = parseInt(document.getElementById("formCountInput").value) || 1;
            // for (let i = 0; i < count; i++) {
            //     addDynamicCard();
            // }
            let countInput = document.getElementById("formCountInput");
            let count = parseInt(countInput.value);
            
            // Check if count is a valid number and greater than 0
            if (!isNaN(count) && count > 0) {
                for (let i = 0; i < count; i++) {
                    addDynamicCard();
                }
                
                // Reset the input field after adding forms
                countInput.value = "";
            } else {
                // Alert the user if no valid number was entered
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please enter the number of forms to add.',
                    confirmButtonText: 'OK'
                });
            }
        });

        function addDynamicCard() {
            const container = document.getElementById("dynamic-form-container");

            const card = document.createElement("div");
            card.classList.add("card", "p-3");

            // Unique name per card for the radio buttons to work independently
            const uniqueId = Date.now();
            
            card.innerHTML = `
                <div class="card-body mt-3">
                    <div class="basic-form">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color: #000000">Item No.:</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Please type here..." class="form-control" name="resNo[]" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Title:</label>
                            <div class="col-sm-9">
                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." name="title[]" required></textarea>
                            </div>
                        </div>
                       <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Status:</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="Please type here..." class="form-control status-input" id="status_${uniqueId}" name="status[]">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Select option if applicable:</label>
                            <div class="col-sm-9 mt-2">
                                <label class="radio-inline mr-5" style="color: #000000">
                                    <input type="radio" name="optradio_${uniqueId}" class="deselectable-radio" value="Returned"> Returned
                                </label>
                                <label class="radio-inline" style="color: #000000">
                                    <input type="radio" name="optradio_${uniqueId}" class="deselectable-radio" value="Approved"> Approved
                                </label>
                            </div>
                        </div>
                        <!-- Hidden fields section -->
                        <div class="form-group row extra-fields mt-3" style="display: none;">
                            <div class="col-sm-12" id="extraFields_${uniqueId}">
                                <!-- Dynamic fields go here -->
                            </div>
                        </div>
                        <!-- Hidden fields -->
                            <input type="hidden" name="returnNo[]" value="">
                            <input type="hidden" name="resolutionNo[]" value="">
                            <input type="hidden" name="returnDate[]" value="">
                            <input type="hidden" name="resolutionDate[]" value="">
                        <label style="color: #000000">Upload Attachment as Supporting Documents:</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="background-color: #098209;"> <i class="fa fa-paperclip"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="attachment" name="attachment[]" onchange="updateFileName(this)">
                                <label class="custom-file-label text-truncate" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;" for="attachment">Choose file</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-danger" type="button" onclick="removeFile2()"><i class="fa fa-close"></i></button>
                            </div>
                        </div>
                        <div class="form-group row d-flex justify-content-center">
                            <button type="button" class="btn btn-danger delete-btn flex"><i class='fa fa-trash' aria-hidden='true'> Delete</i></button>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(card);

            // Add event listener for textarea auto-expand
            const textarea = card.querySelector(".dynamic-textarea");
            textarea.addEventListener("input", autoExpand);

            // Delete button function
            card.querySelector(".delete-btn").addEventListener("click", function () {
                container.removeChild(card);
            });

            // Radio button behavior
            const radios = card.querySelectorAll(`input[name='optradio_${uniqueId}']`);
            const extraFieldsContainer = card.querySelector(`#extraFields_${uniqueId}`);
            const extraFieldsRow = card.querySelector(".extra-fields");
            const statusInput = card.querySelector(`.status-input`);


            radios.forEach(radio => {
                radio.addEventListener('click', function (e) {
                    if (this.checked) {
                        // Deselect logic
                        if (this.previousChecked) {
                            this.checked = false;
                            this.previousChecked = false;
                            extraFieldsRow.style.display = "none";
                            extraFieldsContainer.innerHTML = "";
                            statusInput.value = "";
                        } else {
                            radios.forEach(r => r.previousChecked = false);
                            this.previousChecked = true;
                            statusInput.value = this.value;

                            // Show input fields based on selected option
                            extraFieldsRow.style.display = "block";
                            if (this.value === "Returned") {
                                extraFieldsContainer.innerHTML = `
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color:#000000">Return No.:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" placeholder="Please type here..." name="returnNo[]">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color:#000000">Return Date:</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="returnDate[]">
                                        </div>
                                    </div>
                                `;
                            } else if (this.value === "Approved") {
                                extraFieldsContainer.innerHTML = `
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color:#000000">Resolution No.:</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" placeholder="Please type here..." name="resolutionNo[]">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label" style="color:#000000">Resolution Date Approved:</label>
                                        <div class="col-sm-9">
                                            <input type="date" class="form-control" name="resolutionDate[]">
                                        </div>
                                    </div>
                                `;
                            }
                        }
                    }
                });
            });
        }

        // Function to auto-expand the textarea
        function autoExpand() {
            this.style.height = "auto"; // Reset height
            this.style.height = (this.scrollHeight) + "px"; // Adjust height based on content
        }

        function updateFileName(input) {
            if (input.files.length > 0) {
                let fileName = input.files[0].name;
                let label = input.nextElementSibling; 
                label.innerText = fileName;
            }
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