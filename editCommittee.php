<?php
    if(isset($_POST['save'])){
        include "connect.php";
        error_reporting(0);

        $id = intval($_POST['id']);
        $committeeName = mysqli_real_escape_string($conn, $_POST['committeeName']);
        $committeeDescription = mysqli_real_escape_string($conn, $_POST['committeeDescription']);

        // Handle image upload
        $photo_path = $existingData['cmteImage'] ?? ""; // default to existing image
        if (!empty($_FILES['committeeImg']['name'])) {
            $targetDir = "images/committee/";
            $imageFileType = strtolower(pathinfo($_FILES['committeeImg']['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                $newFileName = uniqid() . '.' . $imageFileType;
                $photo_path = $targetDir . $newFileName;
                move_uploaded_file($_FILES['committeeImg']['tmp_name'], $photo_path);
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

        $sql = "UPDATE `committee` SET
                    `name` = '$committeeName',
                    `cmteDescription` = '$committeeDescription',
                    `cmteImage` = '$photo_path' WHERE `id` = $id";
        
        $query = mysqli_query($conn, $sql);

        
        if ($query) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Committee Updated',
                    text: 'The committee has been successfully updated.',
                    showCancelButton: true,
                    confirmButtonText: 'Continue Editing',
                    cancelButtonText: 'Return to Committee Table'
                }).then((result) => {
                    if (!result.isConfirmed) {
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
                            text: 'There was an error updating the minutes.',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
            header("Location: listOfCommittee.php");
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
                                <h4 class="card-title text-center" style="color: #098209; ">EDIT COMMITTEE</h4>
                            </div>
                            <?php
                                include("connect.php");
                                $id = intval($_GET['id']); // Ensure ID is an integer
                                $sql = "SELECT * FROM `committee` WHERE id = $id LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                
                                if ($result && mysqli_num_rows($result) > 0) {
                                    $row = mysqli_fetch_assoc($result);
                                } else {
                                    echo "<script>alert('Invalid Record ID!'); window.location.href='listOfCommittee.php';</script>";
                                    exit;
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
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Committee Name: </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." id="committeeName" name="committeeName" value="<?php echo $row['name']?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                             <label class="col-sm-3 col-form-label" style="color:#000000">Committee Description: </label>
                                             <div class="col-sm-9">
                                                <textarea class="form-control dynamic-textarea" style="resize: none; overflow: hidden;" rows="1" placeholder="Please type here..." id="committeeDescription" name="committeeDescription"><?php echo htmlspecialchars_decode($row['cmteDescription']); ?></textarea>
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
                                                <label class="custom-file-label text-truncate" for="committeeImg" style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; display:block;">
                                                    <?php echo !empty($row['cmteImage']) ? $row['cmteImage'] : "Choose file"; ?>
                                                </label>
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
        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to scrollHeight
        }

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("committeeDescription");

            // Resize on input
            textarea.addEventListener("input", function() {
                autoResizeTextarea(this);
            });

            // Resize initially in case there's preloaded content
            autoResizeTextarea(textarea);
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