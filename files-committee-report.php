<?php 
include "header.php"; 
error_reporting(E_ALL); // Enable error reporting for development
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang="en">

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
                        <h5 class="modal-title text-white"><i class="fa fa-file"></i> Generate Resolution Report</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form id="reportForm" action="generatecomreport.php" enctype="multipart/form-data" method="POST">
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

                            <!-- <div class="row mb-3">
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
                            </div> -->

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

        <div class="content-body">
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

                                <a href="addCommitteeReports.php" class="ml-2">
                                    <button type="button" class="btn btn-primary" 
                                        style="background-color: #098209; color:#FFFFFF; border: none;">
                                        <i class="fa fa-plus"></i>&nbsp;New Committee Report
                                    </button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">LIST OF COMMITTEE REPORT</h1>

                                   
                               
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px">
                                        <colgroup>
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                            <col style="width: 25%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                        </colgroup>
                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                            <tr>
                                                <th style="color: #FFFFFF;" data-orderable="false">TITLE</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">COMMITTEE CATEGORY</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">COMMITTEE SECTION</th>
                                                <th style="color: #FFFFFF;" data-orderable="false">COUNCILOR</th>
                                                <th style="color: #FFFFFF;">DATE REPORTED</th>
                                                <th style="color: #FFFFFF;" >ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody style="color: #000000; border:#000000;">
                                            <?php
                                                include "connect.php";

                                                $sql = "SELECT id, title, committee_category, committee_section, councilor, date_report FROM committee_reports ORDER BY date_report DESC";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    die("SQL Error: " . $conn->error);
                                                }

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td style="border-bottom: 1px solid #098209; border-left: 1px solid #098209;"><?php echo $row["title"] ?></td>
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["committee_category"] ?></td>
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["committee_section"] ?></td>
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["councilor"] ?></td>     
                                                        <td style="border-bottom: 1px solid #098209;"><?php echo $row["date_report"] ?></td>
                                                        
                                                        </td>

                                                        <td style="border-bottom: 1px solid #098209; border-right: 1px solid #098209; text-align: center; vertical-align: middle;">
                                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="viewCommitteeReports.php?id=<?php echo $row["id"] ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center p-2">
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


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            <div class="copyright">
                <!-- <p>Copyright © Designed &amp; Developed by <a href="#" target="_blank">Quixkit</a> 2019</p> -->
            </div>
        </div>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
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
                            window.location.href = 'deleteCommitteeReport.php?id=' + id;
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
                    window.location.href = 'editCommitteReports.php?id=' + id;
                }
            });
        }

        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable("#example")) {
                $("#example").DataTable().destroy(); // Destroy existing DataTable instance
            }

            $("#example").DataTable({
                "order": [[3, "desc"]], // Sort by the 4th column (index 3, zero-based) in descending order
                "destroy": true // Ensure previous instance is removed
            });
        });


    </script>

    <script src="js/generatereport.js"></script>
</body>

</html>