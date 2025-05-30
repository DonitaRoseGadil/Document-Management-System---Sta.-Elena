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
    
    <!-- Main Wrapper Start -->
    <div id="main-wrapper">
        
        <?php
            include "sidebar.php"; 

            // Fetch counts from tables
            $resolution_count = $conn->query("SELECT COUNT(*) as count FROM resolution")->fetch_assoc()['count'];
            $ordinance_count = $conn->query("SELECT COUNT(*) as count FROM ordinance")->fetch_assoc()['count'];
            $minutes_count = $conn->query("SELECT COUNT(*) as count FROM minutes")->fetch_assoc()['count'];

            // Fetch role from session
            $role = isset($_SESSION['role']) ? $_SESSION['role'] : 'user';
        ?>

        <!-- Content Body Start -->
        <div class="content-body" style="background-color: #f1f9f1">
            <div class="container-fluid">
                <div class="row page-titles mx-0">
                    <div class="col-sm-6 p-md-0">
                        <div class="welcome-text">
                            <h4 style="color: #098209" class="mb-0">Document Management System</h4>
                            <p style="color: #098209" class="mb-0">Sangguniang Bayan Office</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                <!-- Resolution -->
                <div class="col-lg-4 col-sm-6">
                    <div class="card d-flex flex-row align-items-center shadow-sm" style="border-radius: 10px; overflow: hidden; border: 1px solid #E0E0E0;">
                        <!-- Left section with icon -->
                        <div class="d-flex justify-content-center align-items-center" style="width: 100px; background-color: #A3D9A5; padding: 30px;">
                            <i class="ti-file" style="font-size: 2rem; color: #166534;"></i>
                        </div>
                        <!-- Right section with text -->
                        <div class="p-3 flex-grow-1 bg-white">
                            <div style="color: gray; font-size: 0.9rem;">Resolution</div>
                            <div style="color: black; font-size: 1.5rem; font-weight: bold;"><?php echo $resolution_count; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Ordinance -->
                <div class="col-lg-4 col-sm-6">
                    <div class="card d-flex flex-row align-items-center shadow-sm" style="border-radius: 10px; overflow: hidden; border: 1px solid #E0E0E0;">
                        <!-- Left Section: Icon -->
                        <div class="d-flex justify-content-center align-items-center" style="width: 100px; background-color: #66D2CE; padding: 30px;">
                            <i class="ti-folder" style="font-size: 2rem; color: #003092;"></i>
                        </div>
                        <!-- Right Section: Text and Number -->
                        <div class="p-3 flex-grow-1 bg-white">
                            <div style="color: gray; font-size: 0.9rem;">Ordinances</div>
                            <div style="color: black; font-size: 1.5rem; font-weight: bold;"><?php echo $ordinance_count; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Minutes -->
                <div class="col-lg-4 col-sm-6">
                    <div class="card d-flex flex-row align-items-center shadow-sm" style="border-radius: 10px; overflow: hidden; border: 1px solid #E0E0E0;">
                        <!-- Left Section: Icon -->
                        <div class="d-flex justify-content-center align-items-center" style="width: 100px; background-color: #F8D7DA; padding: 30px;">
                            <i class="ti-agenda" style="font-size: 2rem; color: #B71C1C;"></i>
                        </div>
                        <!-- Right Section: Text and Number -->
                        <div class="p-3 flex-grow-1 bg-white">
                            <div style="color: gray; font-size: 0.9rem;">Meeting Minutes</div>
                            <div style="color: black; font-size: 1.5rem; font-weight: bold;"><?php echo $minutes_count; ?></div>
                        </div>
                    </div>
                </div>

                </div>

                <!-- Activities and Shortcuts Section -->
                <div class="row">
                    <!-- Recent Activities Section -->
                    <div class="col-lg-6">
                        <h4 class="mt-4 mr-2" style="color: #098209;">RECENT ACTIVITIES</h4>
                        <div class="row flex-column mr-2" id="recentActivities">
                            <p id="lastUpdated" style="color: gray; margin-left: 10px;"></p>
                        </div>
                    </div>

                    <?php if ($role === 'master' || $role === 'admin') { ?>
                        <!-- Shortcuts Section -->
                        <div class="col-lg-6">
                            <h4 class="mt-4" style="color: #098209;">SHORTCUTS</h4>
                            <div class="row flex-column" style="gap: 2px;">
                                <div class="col-lg-12">
                                    <div class="card p-2 d-flex align-items-center"
                                        style="margin-bottom: 10px; border-radius: 6px; border: 1px solid #098209; 
                                                display: flex; flex-direction: row; justify-content: space-between;">
                                        <span style="color: black; font-weight: bold;">Add new file resolution</span>
                                        <button class="btn btn-success btn-sm" onclick="window.location.href='addresolution.php';">+</button>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card p-2 d-flex align-items-center"
                                        style="margin-bottom: 10px; border-radius: 6px; border: 1px solid #098209; 
                                                display: flex; flex-direction: row; justify-content: space-between;">
                                        <span style="color: black; font-weight: bold;">Add new file ordinances</span>
                                        <button class="btn btn-success btn-sm" onclick="window.location.href='addordinance.php';">+</button>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card p-2 d-flex align-items-center"
                                        style="margin-bottom: 10px; border-radius: 6px; border: 1px solid #098209; 
                                                display: flex; flex-direction: row; justify-content: space-between;">
                                        <span style="color: black; font-weight: bold;">Add new meeting minutes</span>
                                        <button class="btn btn-success btn-sm" onclick="window.location.href='addmeetingminutes.php';">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>

    </div>

    <!-- Scripts -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            fetchRecentActivities();
        });

        function fetchRecentActivities() {
            fetch("recentactivities.php?timestamp=" + new Date().getTime())
                .then(response => {
                    if (!response.ok) {
                        throw new Error("HTTP error! Status: " + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    let activitiesContainer = document.getElementById("recentActivities");
                    activitiesContainer.innerHTML = ""; 

                    if (data.activities.length > 0) {
                        data.activities.forEach(activity => {
                            let activityHTML = `
                                <div class="col-lg-12" style="overflow: visible;">
                                    <div class="card p-2" 
                                        style="margin-bottom: 10px; 
                                            border-left: 4px solid 
                                            ${activity.action === 'Edited' ? '#007BFF' : 
                                                activity.action === 'Created' ? '#098209' : 
                                                activity.action === 'Deleted' ? '#DC3545' : '#E0E0E0'}; 
                                            background: #f9f9f9; 
                                            padding: 10px;
                                            box-shadow: 5px 5px 10px rgba(0.1, 0, 0, 0.1);">
                                        <div style="color: #333; font-weight: bold; font-size: 1rem;">
                                            ${activity.file_type}: ${activity.title}
                                        </div>
                                        <div style="color: gray; font-size: 0.85rem;">
                                            ${activity.action} on ${activity.timestamp}
                                        </div>
                                    </div>
                                </div>
                            `;
                            activitiesContainer.insertAdjacentHTML("beforeend", activityHTML);
                        });
                    } else {
                        activitiesContainer.innerHTML = '<p style="color: gray; margin-left: 10px;">No recent activities.</p>';
                    }
                })
                .catch(error => console.error("Error fetching activities:", error));
        }

    </script>


</body>
</html>
