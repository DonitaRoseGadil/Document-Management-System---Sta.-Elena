<?php
if (isset($_POST['save'])) {
    include 'connect.php'; 
    error_reporting(0);

    $sectionId = $_GET['id'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && $sectionId) {
        $title = $_POST['title'];
        $contents = $_POST['content'];
        $subcontents = $_POST['subcontent'];

        $success = true;

        // Update section title
        $stmt = $conn->prepare("UPDATE sections SET title = ? WHERE id = ?");
        $stmt->bind_param("si", $title, $sectionId);
        if (!$stmt->execute()) {
            $success = false;
        }

        $log_sql = "INSERT INTO history_log (action, file_type, file_id, title) 
        VALUES ('Edited', 'Rules', $sectionId, '$title')";
        $conn->query($log_sql);

        // Delete old contents and subcontents
        if (!$conn->query("DELETE FROM subcontents WHERE content_id IN (SELECT id FROM contents WHERE section_id = $sectionId)") ||
            !$conn->query("DELETE FROM contents WHERE section_id = $sectionId")) {
            $success = false;
        }
        

        // Insert updated contents and subcontents
        foreach ($contents as $index => $contentText) {
            $stmt = $conn->prepare("INSERT INTO contents (section_id, content_text) VALUES (?, ?)");
            $stmt->bind_param("is", $sectionId, $contentText);
            if (!$stmt->execute()) {
                $success = false;
                break;
            }
            $contentId = $stmt->insert_id;

            if (isset($subcontents[$index])) {
                foreach ($subcontents[$index] as $subText) {
                    $stmtSub = $conn->prepare("INSERT INTO subcontents (content_id, subcontent_text) VALUES (?, ?)");
                    $stmtSub->bind_param("is", $contentId, $subText);
                    if (!$stmtSub->execute()) {
                        $success = false;
                        break 2; // exit both loops
                    }
                }
            }
        }

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        if ($success) {
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Rules Updated',
                        text: 'The rules have been successfully updated.',
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
                        text: 'There was an error updating the rules.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
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
                                <h4 class="card-title text-center" style="color: #098209; ">EDIT RULE CONTENTS</h4>
                            </div>
                            <?php
                                include("connect.php");

                                $sectionId = $_GET['id'] ?? null;

                                if ($sectionId) {
                                    // Fetch rule title
                                    $stmt = $conn->prepare("SELECT * FROM sections WHERE id = ?");
                                    $stmt->bind_param("i", $sectionId);
                                    $stmt->execute();
                                    $sectionResult = $stmt->get_result()->fetch_assoc();

                                    // Fetch contents under that section
                                    $contents = [];
                                    $stmt = $conn->prepare("SELECT * FROM contents WHERE section_id = ?");
                                    $stmt->bind_param("i", $sectionId);
                                    $stmt->execute();
                                    $contentResult = $stmt->get_result();

                                    while ($row = $contentResult->fetch_assoc()) {
                                        // Fetch subcontents for each content
                                        $contentId = $row['id'];
                                        $stmtSub = $conn->prepare("SELECT * FROM subcontents WHERE content_id = ?");
                                        $stmtSub->bind_param("i", $contentId);
                                        $stmtSub->execute();
                                        $subcontents = $stmtSub->get_result()->fetch_all(MYSQLI_ASSOC);

                                        $row['subcontents'] = $subcontents;
                                        $contents[] = $row;
                                    }
                                }
                            ?>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="editrules.php?id=<?= $sectionId ?>" method="post" enctype="multipart/form-data" class="MutipleRecord" id="rules-form">
                                        <div id="dynamic-form-container">
                                            <div class="card-body">
                                                <label style="font-weight:bold">Rule Title:</label>
                                                <textarea class="form-control dynamic-textarea auto-expand"
                                                        name="title" rows="1"
                                                        style="resize: none; overflow: hidden;"><?= htmlspecialchars($sectionResult['title'] ?? '') ?></textarea>

                                                <div class="content-container" style="margin-top: 15px">
                                                    <?php foreach ($contents as $index => $content): ?>
                                                        <div class="content-block mb-3 border p-2">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <textarea class="form-control dynamic-textarea auto-expand"
                                                                        name="content[<?= $index ?>]" id="content"
                                                                        rows="1" style="resize: none; overflow: hidden;"><?= htmlspecialchars($content['content_text']) ?></textarea>
                                                                <button type="button" class="btn btn-danger btn-sm delete-content-block mt-1">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                            </div>

                                                            <div class="subcontent-container mt-2">
                                                                <?php foreach ($content['subcontents'] as $sub): ?>
                                                                    <div class="d-flex align-items-center mb-2 subcontent-row">
                                                                        <textarea class="form-control dynamic-textarea auto-expand"
                                                                            name="subcontent[<?= $index ?>][]" 
                                                                            rows="1" style="resize: none; overflow: hidden;"><?= htmlspecialchars($sub['subcontent_text']) ?></textarea>
                                                                        <button type="button" class="btn btn-danger btn-sm ms-2 delete-subcontent">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                <?php endforeach; ?>
                                                            </div>

                                                            <div class="text-end">
                                                                <button type="button" class="btn btn-sm btn-secondary add-subcontent-btn mt-2">
                                                                    <i class="fa fa-plus"></i> Add Subcontent
                                                                </button>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>

                                                <div class="text-center mt-2">
                                                    <button type="button" class="btn btn-primary add-content-btn">
                                                        <i class="fa fa-plus"></i> Add Content
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row d-flex justify-content-center mt-5">
                                            <button type="submit" class="btn btn-primary" name="save" style="background-color: #098209; border: none; width: 100px;">Save</button>
                                            <a href="rules-list.php" class="btn btn-danger ml-2" style="width: 100px;">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function () {
            let contentIndex = document.querySelectorAll('.content-block').length;

            // Add new content block
            document.querySelector('.add-content-btn').addEventListener('click', function () {
                const contentBlock = `
                    <div class="content-block mb-3 border p-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <textarea class="form-control dynamic-textarea auto-expand" name="content[${contentIndex}]" rows="1" style="resize: none; overflow: hidden;" placeholder="Enter section content"></textarea>
                            <button type="button" class="btn btn-danger btn-sm delete-content-block mt-1">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="subcontent-container mt-2"></div>
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-secondary add-subcontent-btn mt-2">
                                <i class="fa fa-plus"></i> Add Subcontent
                            </button>
                        </div>
                    </div>
                `;

                document.querySelector('.content-container').insertAdjacentHTML('beforeend', contentBlock);

                // Auto-expand any textarea added
                const newTextarea = document.querySelector('.content-block:last-child .dynamic-textarea');
                if (newTextarea) autoExpand({ target: newTextarea });

                contentIndex++;
            });

            // Delegate clicks for delete/add buttons
            document.querySelector('.content-container').addEventListener('click', function (e) {
                // Delete content block
                if (e.target.closest('.delete-content-block')) {
                    e.target.closest('.content-block').remove();
                }

                // Add subcontent
                if (e.target.closest('.add-subcontent-btn')) {
                    const contentBlock = e.target.closest('.content-block');
                    const index = Array.from(document.querySelectorAll('.content-block')).indexOf(contentBlock);

                    const subcontentHTML = `
                        <div class="d-flex align-items-center mb-2 subcontent-row">
                            <textarea class="form-control dynamic-textarea auto-expand" style="resize: none; overflow: hidden;" rows="1" placeholder="Enter subcontent" name="subcontent[${index}][]"></textarea>
                            <button type="button" class="btn btn-danger btn-sm ms-2 delete-subcontent">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    `;

                    contentBlock.querySelector('.subcontent-container').insertAdjacentHTML('beforeend', subcontentHTML);

                    // Auto-expand the newly added subcontent textarea
                    const newSubTextarea = contentBlock.querySelector('.subcontent-container .subcontent-row:last-child .dynamic-textarea');
                    if (newSubTextarea) autoExpand({ target: newSubTextarea });
                }

                // Delete subcontent
                if (e.target.closest('.delete-subcontent')) {
                    const row = e.target.closest('.subcontent-row');
                    if (row) row.remove();
                }
            });
        });

        // Auto-expand function for any textarea
        function autoExpand(event) {
            const textarea = event.target;
            textarea.style.height = "auto";
            textarea.style.height = textarea.scrollHeight + "px";
        }

        // Bind autoExpand to manually typed content
        document.addEventListener('input', function (e) {
            if (e.target.classList.contains('dynamic-textarea')) {
                autoExpand(e);
            }
        });
    </script>


    <script>
        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height
            textarea.style.height = textarea.scrollHeight + 'px'; // Adjust to content
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Apply auto-resize to all current .dynamic-textarea elements
            document.querySelectorAll('.dynamic-textarea').forEach(textarea => {
                textarea.addEventListener("input", function () {
                    autoResizeTextarea(this);
                });

                autoResizeTextarea(textarea); // Initial resize
            });

            // Delegate auto-resize for future dynamically added textareas
            document.addEventListener("input", function (e) {
                if (e.target.classList.contains("dynamic-textarea")) {
                    autoResizeTextarea(e.target);
                }
            });
        });

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