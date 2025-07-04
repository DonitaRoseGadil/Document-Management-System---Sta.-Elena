<?php 
    error_reporting(E_ALL); // Enable error reporting for development
    ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<?php include "header.php"; ?>

<body>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>
    
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
                    <?php
                        include("connect.php");
                        $id = intval($_GET['id']); // Ensure ID is an integer
                        $sql = "SELECT * FROM `committee` WHERE id = $id LIMIT 1";
                        $result = mysqli_query($conn, $sql);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                        } else {
                            echo "<script>alert('Invalid Record ID!'); window.location.href='listOfCommittee.php';</script>";
                            exit;
                        }
                    ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">COMMITTEE ON <?php echo strtoupper(htmlspecialchars($row['name'])); ?></h1>
                            </div>
                            <div class="card-body">
                                <div class="chart-container" style="height: 1200px; background-color: #fffeff"></div>
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

    <script>
    var chart;

    fetch('get-OfficialsData.php')
        .then((response) => response.json())
        .then((dataFlattened) => {
            chart = new d3.OrgChart()
            .container('.chart-container')
            .data(dataFlattened)
            .rootMargin(100)
            .nodeWidth((d) => 210)
            .nodeHeight((d) => 140)
            .childrenMargin((d) => 130)
            .compactMarginBetween((d) => 75)
            .compactMarginPair((d) => 80)
            .linkUpdate(function (d, i, arr) {
                d3.select(this)
                .attr('stroke', (d) =>
                    d.data._upToTheRootHighlighted ? '#152785' : 'lightgray'
                )
                .attr('stroke-width', (d) =>
                    d.data._upToTheRootHighlighted ? 5 : 1.5
                )
                .attr('stroke-dasharray', '4,4');

                if (d.data._upToTheRootHighlighted) {
                d3.select(this).raise();
                }
            })
            .nodeContent(function (d, i, arr, state) {
                const colors = ['#6E6B6F', '#18A8B6', '#F45754', '#96C62C', '#BD7E16', '#802F74'];
                const color = colors[d.depth % colors.length];
                const imageDim = 80;
                const lightCircleDim = 95;
                const outsideCircleDim = 110;

                return `
                <div style="background-color:white; position:absolute;width:${d.width}px;height:${d.height}px;">
                    <div style="background-color:${color};position:absolute;margin-top:-${outsideCircleDim / 2}px;margin-left:${d.width / 2 - outsideCircleDim / 2}px;border-radius:100px;width:${outsideCircleDim}px;height:${outsideCircleDim}px;"></div>
                    <div style="background-color:#ffffff;position:absolute;margin-top:-${lightCircleDim / 2}px;margin-left:${d.width / 2 - lightCircleDim / 2}px;border-radius:100px;width:${lightCircleDim}px;height:${lightCircleDim}px;"></div>
                    <img src="${d.data.imageUrl}" style="position:absolute;margin-top:-${imageDim / 2}px;margin-left:${d.width / 2 - imageDim / 2}px;border-radius:100px;width:${imageDim}px;height:${imageDim}px;" />
                    <div class="card" style="top:${outsideCircleDim / 2 + 10}px;position:absolute;height:30px;width:${d.width}px;background-color:#3AB6E3;">
                    <div style="background-color:${color};height:28px;text-align:center;padding-top:10px;color:#ffffff;font-weight:bold;font-size:16px">
                        ${d.data.name}
                    </div>
                    <div style="background-color:#F0EDEF;height:28px;text-align:center;padding-top:10px;color:#424142;font-size:16px">
                        ${d.data.positionName}
                    </div>
                    </div>
                </div>`;
            })
            .render();
        });
    </script>

</body>

</html>