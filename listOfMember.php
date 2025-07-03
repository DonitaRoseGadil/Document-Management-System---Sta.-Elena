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
                        <?php if ($role === 'admin' || $role === 'master') { ?>
                            <div class="d-flex justify-content-end mb-3">
                                <a href="addOfficials.php" class="ml-2">
                                    <button type="button" class="btn btn-primary" 
                                        style="background-color: #098209; color:#FFFFFF; border: none;">
                                        <i class="fa fa-plus"></i>&nbsp;New Official
                                    </button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">LIST OF ALL OFFICIALS</h1>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px; width: 100%;">
                                        <colgroup>
                                            <col style="width: 10%;">
                                            <col style="width: 40%;">
                                            <col style="width: 20%;">
                                            <col style="width: 20%;">
                                            <col style="width: 10%;">
                                        </colgroup>
                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                            <tr>
                                                <th style="color: #FFFFFF;"></th>
                                                <th style="color: #FFFFFF;">NAME</th>
                                                <th style="color: #FFFFFF;">POSITION</th>
                                                <th style="color: #FFFFFF;">TERM</th>
                                                <th style="color: #FFFFFF;">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-left" style="color: #000000;" >
                                            <?php
                                                include "connect.php";
                                                $sql = "SELECT id, photo_path, firstname, middlename, surname, position, term FROM officials";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    die("SQL Error: " . $conn->error);
                                                }

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: center; pointer-events: none; border-bottom: 1px solid #098209; border-left: 1px solid #098209;">
                                                            <div class="round-img">
                                                                <a href="#">
                                                                    
                                                                    <img src="<?php echo htmlspecialchars($row['photo_path']) ?>" alt="User Photo" style="max-width: 30%; height: auto; border-radius: 50%; object-fit: cover;">
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;"><?php echo $row["firstname"] . " " . $row["middlename"] . " " . $row["surname"] ?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;"><?php echo $row["position"]?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;"><?php echo $row["term"]?></td>
                                                        <td style="border-bottom: 1px solid #098209; border-right: 1px solid #098209; text-align: center; vertical-align: middle;">
                                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                                <a href="viewOfficials.php?id=<?php echo $row["id"] ?>" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center p-2">
                                                                    <i class="fa fa-eye" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                </a>
                                                                <a onclick="confirmEdit(<?php echo $row['id']; ?>)" class="btn btn-success btn-sm d-flex align-items-center justify-content-center p-2 ml-1 mr-1">
                                                                    <i class="fa fa-edit" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                </a>
                                                                <a onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-2">
                                                                    <i class="fa fa-trash" aria-hidden="true" style="color: #FFFFFF"></i>
                                                                </a>
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

</body>

</html>