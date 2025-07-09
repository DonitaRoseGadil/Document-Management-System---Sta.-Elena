<?php 
    include 'header.php'; 

    // Fetch role from session
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
?>

<!--**********************************
    Nav header start
***********************************-->
<div class="nav-header">
    <a href="./dashboard.php" class="brand-logo">
        <img class="logo-abbr" src="./images/logo.png" alt="">
        <img class="logo-compact" src="./images/logo-text.png" alt="">
        <img class="brand-title" src="./images/logo-text.png" alt="">
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </div>
</div>
<!--**********************************
    Nav header end
***********************************-->

<!--**********************************
    Header start
***********************************-->
<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                </div>

                <ul class="navbar-nav header-right">
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="#" role="button" data-toggle="dropdown">
                            <i class="mdi mdi-account"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <?php if ($role === 'master') { ?>
                                <a href="./fullHistory.php" class="dropdown-item">
                                    <i class="mdi mdi-history"></i>
                                    <span class="ml-2">See all History </span>
                                </a>
                            <?php } ?>
                            <a href="#" class="dropdown-item" id="logoutBtn">
                                <i class="icon-key"></i>
                                <span class="ml-2">Logout </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
<!--**********************************
    Header end ti-comment-alt
***********************************-->

<!--**********************************
    Sidebar start
***********************************-->
<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                <a href="./dashboard.php" aria-expanded="false"><i class="icon icon-app-store"></i><span class="nav-text">Dashboard</span></a>
            </li>
            <li>
                <a href="./files-rules.php" aria-expanded="false"><i class="icon icon-book-open-2"></i><span class="nav-text">Rules & Regulations</span></a>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false"><i class="icon icon-folder-15"></i><span class="nav-text">Files</span></a>
                <ul aria-expanded="false">
                    <li><a href="./files-resolution.php">Resolution</a></li>
                    <li><a href="./files-ordinances.php">Ordinance</a></li>
                    <li><a href="./files-meetingminutes.php">Order of Business</a></li>
                    <li><a href="./files-committee-report.php">Committee Report</a></li>
                </ul>
            </li>
            <li>
                <a href="./accountSettings.php" aria-expanded="false"><i class="icon icon-settings-gear-64"></i><span class="nav-text">Account Settings</span></a>
            </li>
            <?php if ($role === 'master') { ?>
                <li>
                    <a href="./manageAccounts.php" aria-expanded="false"><i class="icon icon-single-04"></i><span class="nav-text">Manage Accounts</span></a>
                </li>
            <?php } ?>
        </ul>
    </div>


</div>
<!--**********************************
    Sidebar end
***********************************-->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById("logoutBtn").addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default logout action

        Swal.fire({
            title: "Sign out",
            text: "Are you sure you would like to sign out of your account?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "logout.php"; // Redirect to logout page
            }
        });
    });
</script>