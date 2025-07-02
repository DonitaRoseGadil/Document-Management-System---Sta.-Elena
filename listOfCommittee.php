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
                                <!-- <div class="button-container d-flex justify-content-end">
                                    <?php if ($role === 'admin' || $role === 'master') { ?>
                                        <a href="#">
                                            <button type="button" class="btn btn-primary" style="background-color: #098209; color:#FFFFFF; border: none;"><i class="fa fa-plus"></i>&nbsp;New Committe</button>
                                        </a>
                                    <?php } ?>
                                </div> -->
                            </div>
                            <div class="card-body">
                                <div class="row" >
                                    <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 mx-auto">
                                        <div class="card mb-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.75);">
                                            <div class="card-header">
                                                <h5 class="card-title">COMMITTEE ON FINANCE, BUDGET, AND APPROPRIATION</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little</p>
                                            </div>
                                            <img class="card-img-bottom img-fluid" src="./images/loginBG.png" alt="Card image cap" style="max-height: 200px; object-fit: cover;">
                                            <div class="card-footer">
                                                <p class="card-text d-inline">EDIT | DELETE</p>
                                                <a href="javascript:void()" class="card-link float-right">VIEW</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3" >
                                    <div class="col-xl-6 col-xxl-6 col-lg-6 col-sm-6 mx-auto">
                                        <div class="card mb-3" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.75);">
                                            <div class="card-header">
                                                <h5 class="card-title">COMMITTEE ON WOMEN, FAMILY AND SOCIAL WELFARE</h5>
                                            </div>
                                            <div class="card-body">
                                                <p class="card-text">This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little</p>
                                            </div>
                                            <img class="card-img-bottom img-fluid" src="./images/loginBG.png" alt="Card image cap" style="max-height: 200px; object-fit: cover;">
                                            <div class="card-footer">
                                                <p class="card-text d-inline">EDIT | DELETE</p>
                                                <a href="javascript:void()" class="card-link float-right">VIEW</a>
                                            </div>
                                        </div>
                                    </div>
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