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
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">ORGANIZARIONAL CHART</h1>
                            </div>
                            <div class="card-body">
                                
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