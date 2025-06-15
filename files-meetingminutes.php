<?php 
    error_reporting(E_ALL); // Enable error reporting for development
    ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>

<body>
    
    <!--*******************
        Preloader start
    ********************-->
    <?php include"loadingscreen.php" ?>
    <!--*******************
        Preloader end
    ********************-->
    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php 
            
            include "sidebar.php";
            
            // Fetch role from session
            $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
        ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="color: #098209; background-color: #f1f9f1;">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">ORDER OF BUSINESS</h1>
                                <div class="button-container d-flex justify-content-end">
                                    <?php if ($role === 'admin' || $role === 'master') { ?>
                                        <a href="addmeetingminutes.php">
                                            <button type="button" class="btn btn-primary" style="background-color: #098209; color:#FFFFFF; border: none;"><i class="fa fa-plus"></i>&nbsp;New Agenda</button>
                                        </a>
                                    <?php } ?>
                                    
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px; width: 100%;">
                                        <colgroup>
                                            <col style="width: 15%;">
                                            <col style="width: 10%;">
                                            <col style="width: 15%;">
                                            <col style="width: 35%;">
                                            <col style="width: 15%;">
                                            <col style="width: 10%;">
                                        </colgroup>
                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                            <tr>
                                                <th style="color: #FFFFFF;">NUMBER OF REGULAR SESSION</th>
                                                <th style="color: #FFFFFF;">DATE</th>
                                                <th style="color: #FFFFFF;">RESOLUTION NO. / MO NO.</th>
                                                <th style="color: #FFFFFF;">TITLE</th>
                                                <th style="color: #FFFFFF;">STATUS</th>
                                                <th style="color: #FFFFFF;">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-left" style="color: #000000;" >
                                            <?php
                                                include "connect.php";
                                                $sql = "SELECT id, no_regSession, date, resNo, title, status, returnNo, resolutionNo, returnDate, resolutionDate FROM minutes ORDER BY date DESC, CAST(SUBSTRING_INDEX(resNo, '.', 1) AS UNSIGNED) DESC, CAST(SUBSTRING_INDEX(resNo, '.', -1) AS UNSIGNED) DESC";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    die("SQL Error: " . $conn->error);
                                                }

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209; border-left: 1px solid #098209;"><?php echo $row["no_regSession"] ?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;"><?php echo $row["date"]?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;"><?php echo $row["resolutionNo"]?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209; white-space: pre-line;"><?php echo nl2br(htmlspecialchars($row["title"])); ?></td>
                                                        
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209; padding-top: 10px;">
                                                            <?php echo $row["status"]; ?>

                                                            <?php if (!empty($row["returnNo"])): ?>
                                                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                                                                <div>Return No: <?php echo htmlspecialchars($row["returnNo"]); ?></div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($row["resolutionNo"])): ?>
                                                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                                                                <div>Resolution No: <?php echo htmlspecialchars($row["resolutionNo"]); ?></div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($row["returnDate"]) && $row["returnDate"] !== "0000-00-00"): ?>
                                                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                                                                <div>Return Date: <?php echo htmlspecialchars($row["returnDate"]); ?></div>
                                                            <?php endif; ?>

                                                            <?php if (!empty($row["resolutionDate"]) && $row["resolutionDate"] !== "0000-00-00"): ?>
                                                                <hr style="border: 1px solid #ccc; margin: 5px 0;">
                                                                <div>Resolution Date: <?php echo htmlspecialchars($row["resolutionDate"]); ?></div>
                                                            <?php endif; ?>
                                                        </td>




                                                        <td style="border-bottom: 1px solid #098209; border-right: 1px solid #098209; text-align: center; vertical-align: middle;">
                                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="viewmeetingminutes.php?id=<?php echo $row["id"] ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center p-2">
                                                                    <i class="fa fa-eye" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                </a>
                                                                <?php if ($role === 'admin' || $role === 'master') { ?>
                                                                    <a onclick="confirmEdit(<?php echo $row['id']; ?>)" class="btn btn-success btn-sm d-flex align-items-center justify-content-center p-2 ml-1 mr-1">
                                                                        <i class="fa fa-edit" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                    </a>
                                                                    <a onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-2">
                                                                        <i class="fa fa-trash" aria-hidden="true" style="color: #FFFFFF"></i>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
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

    <!-- Sweetalert for deletion-->
    <script>
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
                                text: "Your file has been deleted.",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            window.location.href = 'deletemeetingminutes.php?id=' + id;
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
                    window.location.href = 'editmeetingminutes.php?id=' + id;
                }
            });
        }

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable("#example")) {
                $("#example").DataTable().destroy(); // Destroy existing DataTable instance
            }

            $("#example").DataTable({
                "order": [[1, "desc"]], // Sort by the 4th column (index 3, zero-based) in descending order
                "destroy": true // Ensure previous instance is removed
            });
        });

    </script>

</body>

</html>