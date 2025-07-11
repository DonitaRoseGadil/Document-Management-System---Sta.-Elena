<?php 
    error_reporting(E_ALL); // Enable error reporting for development
    ini_set('display_errors', 1);
?>

<!DOCTYPE html>
<html lang="en">

<?php include"header.php" ?>


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

          <!-- Report Modal -->
        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header" style="background-color: #098209; color:#FFFFFF; border: none;">
                        <h5 class="modal-title text-white"><i class="fa fa-file"></i> Generate Ordinance Report</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="reportForm" action="generateordreport.php" enctype="multipart/form-data" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label><i class="fas fa-calendar-alt"></i> Start Date:</label>
                                    <input type="date" id="reportStartDate" name="reportStartDate" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label><i class="fas fa-calendar-alt"></i> End Date:</label>
                                    <input type="date" id="reportEndDate" name="reportEndDate" class="form-control" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label><i class="fas fa-calendar-alt"></i> Barangay:</label>
                                    <select id="reportBarangay" name="reportBarangay" class="form-control" required>
                                        <option value="">-- Select Barangay --</option>
                                        <option value="Basiad">Basiad</option>
                                        <option value="Bulala">Bulala</option>
                                        <option value="Don Tomas">Don Tomas</option>
                                        <option value="Guitol">Guitol</option>
                                        <option value="Kabuluan">Kabuluan</option>
                                        <option value="Kagtalaba">Kagtalaba</option>
                                        <option value="Maulawin">Maulawin</option>
                                        <option value="Patag Ibaba">Patag Ibaba</option>
                                        <option value="Patag Ilaya">Patag Ilaya</option>
                                        <option value="Plaridel">Plaridel</option>
                                        <option value="Polangguitguit">Polangguitguit</option>
                                        <option value="Rizal">Rizal</option>
                                        <option value="Salvacion">Salvacion</option>
                                        <option value="San Lorenzo">San Lorenzo</option>
                                        <option value="San Pedro">San Pedro</option>
                                        <option value="San Vicente">San Vicente</option>
                                        <option value="Santa Elena (Pob.)">Santa Elena (Pob.)</option>
                                        <option value="Tabugon">Tabugon</option>
                                        <option value="Villa San Isidro">Villa San Isidro</option>
                                        <option value ="all">All Barangays</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Add the "All Reports" checkbox -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="generateAllReports" name="generateAllReports" value="on">
                                        <label class="form-check-label" for="generateAllReports">
                                            Generate All Reports (ignores date range)
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-times"></i> Close
                        </button>
                        <button type="button" id="generateReportBtn" class="btn btn-success text-white">
                            <i class="fa fa-file"></i> Generate Report
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <div class="content-body" style="background-color: #f1f9f1">
            <div class="container-fluid">
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <?php if ($role === 'admin' || $role === 'master') { ?>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary" 
                                    style="background-color: #098209; color:#FFFFFF; border: none;" 
                                    data-toggle="modal" data-target="#reportModal">
                                    <i class="fa fa-file"></i>&nbsp;Generate Report
                                </button>

                                <a href="addordinance.php" class="ml-2">
                                    <button type="button" class="btn btn-primary" 
                                        style="background-color: #098209; color:#FFFFFF; border: none;">
                                        <i class="fa fa-plus"></i>&nbsp;New Ordinance
                                    </button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">LIST OF ORDINANCES</h1>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <colgroup>
                                            <col style="width: 15%;">
                                            <col style="width: 25%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                        </colgroup>
                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                            <tr>
                                                <th style="color: #FFFFFF;" data-orderable="false">RES NO./MO NO.</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">TITLE</th>
                                                <th style="color: #FFFFFF;">DATE ADOPTED</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">AUTHOR/SPONSOR</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">REMARKS</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody style="color: #000000; border:#000000;">
                                            <?php
                                                include "connect.php";

                                                $sql = "SELECT id, mo_no, title, date_adopted, author_sponsor, remarks, date_fwd, date_signed, sp_approval FROM ordinance";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    die("SQL Error: " . $conn->error);
                                                }

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #098209; border-left: 1px solid #098209;"><?php echo $row["mo_no"] ?></td>
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["title"] ?></td>
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["date_adopted"] ?></td> 
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["author_sponsor"] ?></td>     
                                                        <td style="border-bottom: 1px solid #098209;">
                                                            <div class="text-center d-flex justify-content-center gap-2">
                                                                <a style="color: #000000" data-placement="bottom" data-toggle="tooltip" title="
                                                                    <?php
                                                                        $d_forward = !empty($row["date_fwd"]) ? $row["date_fwd"] : "N/A";
                                                                        $d_signed = !empty($row["date_signed"]) ? $row["date_signed"] : "N/A";
                                                                        $d_approved = !empty($row["sp_approval"]) ? $row["sp_approval"] : "N/A";

                                                                        // Display relevant dates based on remarks
                                                                        if ($row["remarks"] == "Forwarded to LCE") {
                                                                            echo "Forwarded to LCE: $d_forward";
                                                                        } elseif ($row["remarks"] == "Signed by LCE") {
                                                                            echo "Forwarded to LCE: $d_forward \nSigned by LCE: $d_signed";
                                                                        } elseif ($row["remarks"] == "SP Approval") {
                                                                            echo "Forwarded to LCE: $d_forward \nSigned by LCE: $d_signed \nSP Approval: $d_approved";
                                                                        }
                                                                    ?>
                                                                ">
                                                                    <?php echo $row["remarks"] ?>
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <!-- <td style="border-bottom: 1px solid #098209;">
                                                            <div class="container">
                                                                <a style="color: #000000" id="popoverData" class="btn" href="#" data-content="Forwarded to LCE: <?php echo $row["d_forward"] ?>" rel="popover" 
                                                                data-placement="bottom" data-trigger="hover"><?php echo $row["remarks"] ?></a>
                                                            </div>
                                                        </td> -->
                                                        <td style="border-bottom: 1px solid #098209; border-right: 1px solid #098209; text-align: center; vertical-align: middle;">
                                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="viewordinance.php?id=<?php echo $row["id"] ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center p-2">
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

    <script>
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip(); 
    });
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
                            window.location.href = 'deleteordinance.php?id=' + id;
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
                    window.location.href = 'editordinance.php?id=' + id;
                }
            });
        }

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable("#example")) {
                $("#example").DataTable().destroy(); // Destroy existing DataTable instance
            }

            $("#example").DataTable({
                "order": [[2, "desc"]], // Sort by the 4th column (index 3, zero-based) in descending order
                "destroy": true // Ensure previous instance is removed
            });
        });

    </script>
    <script src="js/generatereport.js"></script>

</body>

</html>