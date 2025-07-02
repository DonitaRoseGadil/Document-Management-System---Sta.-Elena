<?php
    if(isset($_POST['save'])){
        include "connect.php";
        error_reporting(0);

        $committeeName = $_POST['committeeName'];
        $committeeDescription = $_POST['committeeDescription'];
        $committeeImg = $_FILES['committeeImg']['name'];

        // Insert into database
        $sql = "INSERT INTO committee (name, cmteDescription, cmteImage) 
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $committeeName, $committeeDescription, $committeeImg);
        $stmt->execute();
    

        if ($stmt) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Committee Created',
                            text: 'The committee has been successfully created.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'listOfCommittee.php';
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
                            text: 'There was an error creating the committee.',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
            header("Location: listOfCommittee.php");
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
                                <h4 class="card-title text-center" style="color: #098209; ">ADD COMMITTEE</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Committee Name: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="committeeName" name="committeeName" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Committee Description: </label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." id="committeeDescription" name="committeeDescription"></textarea>
                                             </div>
                                        </div>
                                        <label style="color: #000000">Upload Image of the Committee:</label>
                                        <!-- Image -->
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="background-color: #098209;">
                                                    <i class="fa fa-paperclip"></i>
                                                </span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="committeeImg" name="committeeImg" accept="image/*" onchange="updateFileName(this)">
                                                <label class="custom-file-label text-truncate" for="committeeImg" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">Choose image</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="removeFile()"><i class="fa fa-close"></i></button>
                                            </div>
                                        </div>
                                        <div class="form-group row d-flex justify-content-center mt-5">
                                            <button type="submit" class="btn btn-primary" id="save_btn" name="save" value="Save Data" style="background-color: #098209; border: none; width: 100px; color: #FFFFFF;">Save</button>
                                            <a href="#" class="btn btn-danger ml-2" id="cancel_btn" name="cancel" value="Cancel" data-href="listOfCommittee.php" style="background-color: red; border: none; width: 100px; color: #FFFFFF;">Cancel</a>
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

        // Function to auto-expand the textarea
        function autoExpand(event) {
            const textarea = event.target;
            textarea.style.height = "auto"; 
            textarea.style.height = textarea.scrollHeight + "px"; 
        }

        document.addEventListener("DOMContentLoaded", function () {
            const textarea = document.getElementById("committeeDescription");
            textarea.addEventListener("input", autoExpand);
        });
    </script>

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

        function removeFile() {
            const fileInput = document.getElementById("committeeImg");
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