<?php
    if(isset($_POST['save'])){
        include 'connect.php'; 
        error_reporting(0);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titles = $_POST['title'] ?? [];
            $contents = $_POST['content'] ?? [];
            $subcontents = $_POST['subcontent'] ?? [];

            foreach ($titles as $sectionIndex => $title) {
                if (empty($title)) continue;

                // Insert into sections
                $stmt = $conn->prepare("INSERT INTO sections (title) VALUES (?)");
                $stmt->bind_param("s", $title);
                $stmt->execute();
                $section_id = $stmt->insert_id;

                // Loop through contents for this section
                if (!empty($contents[$sectionIndex])) {
                    foreach ($contents[$sectionIndex] as $contentIndex => $contentText) {
                        if (empty($contentText)) continue;

                        // Insert into contents
                        $stmt = $conn->prepare("INSERT INTO contents (section_id, content_text) VALUES (?, ?)");
                        $stmt->bind_param("is", $section_id, $contentText);
                        $stmt->execute();
                        $content_id = $stmt->insert_id;

                        // Loop through subcontents for this content
                        if (!empty($subcontents[$sectionIndex][$contentIndex])) {
                            foreach ($subcontents[$sectionIndex][$contentIndex] as $subcontentText) {
                                if (empty($subcontentText)) continue;

                                $stmt = $conn->prepare("INSERT INTO subcontents (content_id, subcontent_text) VALUES (?, ?)");
                                $stmt->bind_param("is", $content_id, $subcontentText);
                                $stmt->execute();
                            }
                        }
                    }
                }
            }

            if ($stmt) {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Rules Created',
                                text: 'The rules have been successfully created.',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'files-rules.php';
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
                                text: 'There was an error creating the rules.',
                                confirmButtonText: 'OK'
                            });
                        });
                    </script>";
                header("Location: files-rules.php");
                exit;    
            }

            $stmt->close();
            $conn->close();
        }

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
                                <h4 class="card-title text-center" style="color: #098209; ">ADD RULES CONTENTS</h4>
                            </div>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data" class="MutipleRecord" id="rules-form">
                                        <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                                            <h5 style="color: #098209;">RULE ITEM</h5>
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
        let cardIndex = 0;

        document.addEventListener("DOMContentLoaded", function () {
            addDynamicCard();
        });

        document.getElementById("add-card-btn").addEventListener("click", function () {
            let countInput = document.getElementById("formCountInput");
            let count = parseInt(countInput.value);

            if (!isNaN(count) && count > 0) {
                for (let i = 0; i < count; i++) {
                    addDynamicCard();
                }
                countInput.value = "";
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Information',
                    text: 'Please enter the number of forms to add.',
                    confirmButtonText: 'OK'
                });
            }
        });

        function addDynamicCard() {
            const currentCardIndex = cardIndex++;
            const container = document.getElementById("dynamic-form-container");

            const card = document.createElement("div");
            card.classList.add("card", "p-3", "mb-3");

            card.innerHTML = `
                <div class="card-body">
                    <label style="font-weight:bold">Rule Title:</label>
                    <textarea class="form-control dynamic-textarea auto-expand" style="resize: none; overflow: hidden;" rows="2" placeholder="Enter rule title" name="title[${currentCardIndex}]"></textarea>

                    <div class="content-container" style="margin-top: 15px"></div>

                    <div class="text-center mt-2">
                        <button type="button" class="btn btn-primary add-content-btn">
                            <i class="fa fa-plus"></i> Add Content
                        </button>
                    </div>

                    <hr>

                    <div class="form-group row d-flex justify-content-center">
                        <button type="button" class="btn btn-danger delete-btn flex"><i class='fa fa-trash' aria-hidden='true'> Delete This Card</i></button>
                    </div>
                </div>
            `;

            container.appendChild(card);

            const contentContainer = card.querySelector(".content-container");
            const addContentBtn = card.querySelector(".add-content-btn");

            addContentRow(contentContainer, currentCardIndex); // Add default content

            addContentBtn.addEventListener("click", function () {
                addContentRow(contentContainer, currentCardIndex);
            });

            card.querySelector(".delete-btn").addEventListener("click", function () {
                container.removeChild(card);
            });

            card.addEventListener("click", function (e) {
                if (e.target.closest(".delete-subcontent")) {
                    e.target.closest(".subcontent-row").remove();
                }
                if (e.target.closest(".delete-content-block")) {
                    e.target.closest(".content-block").remove();
                }
            });

            const ruleTitle = card.querySelector("textarea.auto-expand");
            ruleTitle.addEventListener("input", autoExpand);
            autoExpand({ target: ruleTitle });
        }

        function addContentRow(contentContainer, sectionIndex) {
            const contentCount = contentContainer.querySelectorAll(".content-block").length;

            const contentDiv = document.createElement("div");
            contentDiv.classList.add("content-block", "mb-3", "border", "p-2");

            const deleteButtonHTML = contentCount > 0 ? `
                <button type="button" class="btn btn-danger btn-sm delete-content-block mt-1">
                    <i class="fa fa-trash"></i>
                </button>` : "";

            contentDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <textarea class="form-control dynamic-textarea auto-expand" style="resize: none; overflow: hidden;" rows="2" placeholder="Enter section content" name="content[${sectionIndex}][]"></textarea>
                    ${deleteButtonHTML}
                </div>

                <div class="subcontent-container mt-2"></div>

                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-secondary add-subcontent-btn mt-2">
                        <i class="fa fa-plus"></i> Add Subcontent
                    </button>
                </div>
            `;

            contentContainer.appendChild(contentDiv);

            const contentTextarea = contentDiv.querySelector("textarea.auto-expand");
            contentTextarea.addEventListener("input", autoExpand);
            autoExpand({ target: contentTextarea });

            const subcontentContainer = contentDiv.querySelector(".subcontent-container");
            const addSubBtn = contentDiv.querySelector(".add-subcontent-btn");

            addSubBtn.addEventListener("click", () => {
                const subDiv = document.createElement("div");
                subDiv.classList.add("mb-2", "ms-4");
                subDiv.innerHTML = `
                    <div class="d-flex align-items-center mb-2 subcontent-row">
                        <textarea class="form-control dynamic-textarea auto-expand" style="resize: none; overflow: hidden;" rows="2" placeholder="Enter subcontent" name="subcontent[${sectionIndex}][${contentCount}][]"></textarea>
                        <button type="button" class="btn btn-danger btn-sm ms-2 delete-subcontent">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `;
                subcontentContainer.appendChild(subDiv);

                const subTextarea = subDiv.querySelector("textarea.auto-expand");
                subTextarea.addEventListener("input", autoExpand);
                autoExpand({ target: subTextarea });
            });
        }

        // Auto-expand function
        function autoExpand(event) {
            const textarea = event.target;
            textarea.style.height = "auto";
            textarea.style.height = textarea.scrollHeight + "px";
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