<!DOCTYPE html>
<html lang="en">
    
    <?php include('header.php'); ?>

<body>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('sidebar.php'); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="background-color: #f1f9f1">
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">INTERNAL RULES OF PROCEDURE <br> OF THE SANGGUNIANG BAYAN OF STA ELENA, <br> CAMARINES NORTE</h1>
                                <!-- Button trigger modal -->
                                <div class="button-container d-flex justify-content-end">
                                    <a href="addrules.php">
                                        <button type="button" class="btn btn-primary" style="background-color: #098209; color:#FFFFFF; border: none;"><i class="fa fa-plus"></i>&nbsp;Add Rules</button>
                                    </a>  
                                </div>
                            </div>
                            <?php
                                include 'connect.php';

                                $query = "
                                    SELECT 
                                        s.id AS section_id, 
                                        s.title AS section_title, 
                                        c.id AS content_id, 
                                        c.content_text, 
                                        sc.subcontent_text
                                    FROM sections s
                                    LEFT JOIN contents c ON c.section_id = s.id
                                    LEFT JOIN subcontents sc ON sc.content_id = c.id
                                    ORDER BY s.id ASC, c.id ASC, sc.id ASC;
                                ";

                                $result = $conn->query($query);

                                // Group results by section > content
                                $rules = [];

                                while ($row = $result->fetch_assoc()) {
                                    $sectionId = $row['section_id'];
                                    $contentId = $row['content_id'];

                                    if (!isset($rules[$sectionId])) {
                                        $rules[$sectionId] = [
                                            'title' => $row['section_title'],
                                            'contents' => [],
                                        ];
                                    }

                                    if ($contentId && !isset($rules[$sectionId]['contents'][$contentId])) {
                                        $rules[$sectionId]['contents'][$contentId] = [
                                            'text' => $row['content_text'],
                                            'subcontents' => [],
                                        ];
                                    }

                                    if (!empty($row['subcontent_text'])) {
                                        $rules[$sectionId]['contents'][$contentId]['subcontents'][] = $row['subcontent_text'];
                                    }
                                }
                                ?>

                            <div class="card-body">
                                <div id="accordion-nine" class="accordion accordion-active-header accordion-bordered">
                                    <?php if (empty($rules)): ?>
                                        <p class="text-muted text-center">No Rules added yet</p>
                                    <?php else: ?>
                                        <?php
                                        $sectionCount = 0;
                                        foreach ($rules as $sectionId => $section):
                                            $sectionCount++;
                                            $collapseId = "collapse" . $sectionCount;
                                        ?>
                                        <div class="accordion__item">
                                            <div class="accordion__header <?= $sectionCount === 1 ? '' : 'collapsed' ?>"
                                                data-toggle="collapse"
                                                data-target="#<?= $collapseId ?>">
                                                <span class="accordion__header--icon"></span>
                                                <?php
                                                    $title = $section['title'];
                                                    if (!preg_match('/^RULE\s+\w+\s+-/i', $title)) {
                                                        $title = 'RULE ' . $sectionCount . ' - ' . $title;
                                                    }
                                                ?>
                                                <span class="accordion__header--text"><?= htmlspecialchars($title) ?></span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>

                                            <div id="<?= $collapseId ?>"
                                                class="collapse accordion__body <?= $sectionCount === 1 ? 'show' : '' ?>"
                                                data-parent="#accordion-nine">
                                                <?php foreach ($section['contents'] as $content): ?>
                                                    <div class="accordion__body--text" style="color: #000000;">
                                                        <?= htmlspecialchars($content['text']) ?>
                                                    </div>
                                                    <?php foreach ($content['subcontents'] as $sub): ?>
                                                        <div class="accordion__body--text ms-4" style="text-indent: 25px; color: #000000;">
                                                            <?= htmlspecialchars($sub) ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                <?php endforeach; ?>
                                                <div class="content-footer d-flex justify-content-between align-items-center mt-3" style="margin: 0px 20px 0px 20px;">
                                                    <p class="card-text text-gray d-inline" style="margin-bottom: 10px">Last updated on May 27, 2025 at 11:19 AM</p>
                                                    <div style="margin-bottom: 10px">
                                                        <a href="javascript:void(0)" onclick="confirmEdit(<?= $sectionId ?>)" class="card-link text-primary me-3">EDIT</a>
                                                        <p class="card-text text-gray d-inline">|</p>
                                                        <a href="javascript:void(0)" class="card-link text-danger delete-btn" data-id="<?= $sectionId ?>">DELETE</a>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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

    <!-- Datatable -->
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="./js/plugins-init/datatables.init.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toggle -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Attach delete click handler
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    confirmDelete(id);
                });
            });
        });

        // SweetAlert Password Prompt + Confirm Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Enter Password",
                input: "password",
                inputAttributes: {
                    autocapitalize: "off",
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: "Submit",
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    return fetch("validate_password.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "password=" + encodeURIComponent(password)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || "Incorrect password.");
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(error.message);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You won't be able to revert this!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Confirm!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "The rule has been deleted.",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 2000
                            });

                            // Redirect to delete PHP
                            setTimeout(() => {
                                window.location.href = 'deleterules.php?id=' + id;
                            }, 2000); // delay to show success message
                        }
                    });
                }
            });
        }

        function confirmEdit(id) {
            Swal.fire({
                title: "Enter Password",
                input: "password",
                inputAttributes: {
                    autocapitalize: "off",
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: "Submit",
                showLoaderOnConfirm: true,
                preConfirm: (password) => {
                    return fetch("validate_password.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "password=" + encodeURIComponent(password)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || "Incorrect password.");
                        }
                    })
                    .catch(error => {
                        Swal.showValidationMessage(error.message);
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Redirect to editrules page if confirmed and password is valid
                    window.location.href = 'editrules.php?id=' + id;
                }
            });
        }

    </script>


</body>

</html>