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
                                <a href="addCommittee.php" class="ml-2">
                                    <button type="button" class="btn btn-primary" 
                                        style="background-color: #098209; color:#FFFFFF; border: none;">
                                        <i class="fa fa-plus"></i>&nbsp;New Committee
                                    </button>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">LIST OF ALL COMMITTEE</h1>
                            </div>
                            <div class="card-body">
                                <?php 
                                    include "connect.php"; 
                                    $sql = "SELECT id, name, cmteDescription, cmteImage FROM committee";
                                    $result = $conn->query($sql);
                                
                                ?>
                                <div class="row" >
                                    <?php while ($row = $result->fetch_assoc()) { ?>
                                    <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 mx-auto">
                                        <div class="card mb-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.75);">
                                            <div class="card-header">
                                                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text" style="color:#000000; white-space: pre-wrap;"><?php 
                                                    $desc = htmlspecialchars($row['cmteDescription']);
                                                    $desc = strlen($desc) > 150 ? substr($desc, 0, 150) . '...' : $desc;
                                                    echo nl2br($desc);
                                                ?></p>
                                            </div>
                                            <img class="card-img-bottom img-fluid" src="<?php echo htmlspecialchars($row['cmteImage']) ?>" alt="Card image" style="max-height: 200px; object-fit: cover;">
                                            <div class="card-footer">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex gap-2">
                                                        <a href="editCommittee.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm p-2 mr-2">
                                                            <i class="fa fa-edit" style="color: #ffffff;"></i>
                                                        </a>
                                                        <a href="deleteCommittee.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm p-2">
                                                            <i class="fa fa-trash" style="color: #ffffff;"></i> 
                                                        </a>
                                                    </div>
                                                    <a href="viewCommittee.php?id=<?php echo $row['id']; ?>"> View</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
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

</body>

</html>