<!DOCTYPE html>
<html lang="en">

<?php 
    include "header.php"; 
    include "connect.php";

?>

<body>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include "sidebar.php"; ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="background-color: #f1f9f1">
            <div class="container-fluid" >
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">HISTORY</h4>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <div class="custom-tab-1">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#resolution" style="color: #000000">Resolution</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#ordinance" style="color: #000000">Ordinance</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#minutes" style="color: #000000">Order of Business</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#rules" style="color: #000000">Rules and Regulations</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#committee_reports" style="color: #000000">Committee Reports</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <!--Resolution-->
                                        <div class="tab-pane fade show active" id="resolution" role="tabpanel">
                                            <div class="pt-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-responsive-sm">
                                                        <colgroup>
                                                            <col style="width: 70%;">
                                                            <col style="width: 15%;">
                                                            <col style="width: 15%;">
                                                        </colgroup>
                                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>TIMESTAMP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="color: #000000;">
                                                            <?php 
                                                                include "connect.php";
                                                                $sql = "SELECT title, action, timestamp FROM history_log WHERE file_type = 'Resolution' ORDER BY timestamp DESC LIMIT 100";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                if (!$result) {
                                                                    die("SQL Error: " . $conn->error);
                                                                }

                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class="text-center fs-4">
                                                                            <?php 
                                                                                switch ($row['action']) {
                                                                                    case 'Created':
                                                                                        echo '<span class="badge badge-success">Created</span>';
                                                                                        break;
                                                                                    case 'Edited':
                                                                                        echo '<span class="badge badge-primary">Edited</span>';
                                                                                        break;
                                                                                    case 'Deleted':
                                                                                        echo '<span class="badge badge-danger">Deleted</span>';
                                                                                        break;
                                                                                    default:
                                                                                        echo $row['action']; // fallback if action is not recognized
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center"><?php echo date('F j, Y \a\t h:i A', strtotime($row['timestamp'])); ?></td>
                                                                    </tr>   
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>               
                                            </div>
                                        </div>
                                        <!--Ordinance-->
                                        <div class="tab-pane fade" id="ordinance">
                                            <div class="pt-4">
                                            <div class="table-responsive">
                                                    <table class="table table-bordered table-responsive-sm">
                                                        <colgroup>
                                                            <col style="width: 70%;">
                                                            <col style="width: 15%;">
                                                            <col style="width: 15%;">
                                                        </colgroup>
                                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>TIMESTAMP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="color: #000000;">
                                                            <?php 
                                                                include "connect.php";
                                                                $sql = "SELECT title, action, timestamp FROM history_log WHERE file_type = 'Ordinance' ORDER BY timestamp DESC LIMIT 100";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                if (!$result) {
                                                                    die("SQL Error: " . $conn->error);
                                                                }

                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class="text-center fs-4">
                                                                            <?php 
                                                                                switch ($row['action']) {
                                                                                    case 'Created':
                                                                                        echo '<span class="badge badge-success">Created</span>';
                                                                                        break;
                                                                                    case 'Edited':
                                                                                        echo '<span class="badge badge-primary">Edited</span>';
                                                                                        break;
                                                                                    case 'Deleted':
                                                                                        echo '<span class="badge badge-danger">Deleted</span>';
                                                                                        break;
                                                                                    default:
                                                                                        echo $row['action']; // fallback if action is not recognized
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center"><?php echo date('F j, Y \a\t h:i A', strtotime($row['timestamp'])); ?></td>
                                                                    </tr>   
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div> 
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="minutes">
                                            <div class="pt-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-responsive-sm">
                                                        <colgroup>
                                                            <col style="width: 60%;">
                                                            <col style="width: 10%;">
                                                            <col style="width: 20%;">
                                                            <col style="width: 10%;">
                                                        </colgroup>
                                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>STATUS</th>
                                                                <th>TIMESTAMP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="color: #000000;">
                                                            <?php 
                                                                include "connect.php";
                                                                $sql = "SELECT title, action, status, timestamp FROM history_log WHERE file_type = 'Minutes' ORDER BY timestamp DESC LIMIT 100";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                if (!$result) {
                                                                    die("SQL Error: " . $conn->error);
                                                                }

                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class="text-center fs-4">
                                                                            <?php 
                                                                                switch ($row['action']) {
                                                                                    case 'Created':
                                                                                        echo '<span class="badge badge-success">Created</span>';
                                                                                        break;
                                                                                    case 'Edited':
                                                                                        echo '<span class="badge badge-primary">Edited</span>';
                                                                                        break;
                                                                                    case 'Deleted':
                                                                                        echo '<span class="badge badge-danger">Deleted</span>';
                                                                                        break;
                                                                                    default:
                                                                                        echo $row['action']; // fallback if action is not recognized
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center"><?php echo $row['status']; ?></td>
                                                                        <td class="text-center"><?php echo date('F j, Y \a\t h:i A', strtotime($row['timestamp'])); ?></td>
                                                                    </tr>   
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="rules">
                                            <div class="pt-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-responsive-sm">
                                                        <colgroup>
                                                            <col style="width: 70%;">
                                                            <col style="width: 15%;">
                                                            <col style="width: 15%;">
                                                        </colgroup>
                                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>TIMESTAMP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="color: #000000;">
                                                            <?php 
                                                                include "connect.php";
                                                                $sql = "SELECT title, action, timestamp FROM history_log WHERE file_type = 'Rules' ORDER BY timestamp DESC LIMIT 100";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                if (!$result) {
                                                                    die("SQL Error: " . $conn->error);
                                                                }

                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class="text-center fs-4">
                                                                            <?php 
                                                                                switch ($row['action']) {
                                                                                    case 'Created':
                                                                                        echo '<span class="badge badge-success">Created</span>';
                                                                                        break;
                                                                                    case 'Edited':
                                                                                        echo '<span class="badge badge-primary">Edited</span>';
                                                                                        break;
                                                                                    case 'Deleted':
                                                                                        echo '<span class="badge badge-danger">Deleted</span>';
                                                                                        break;
                                                                                    default:
                                                                                        echo $row['action']; // fallback if action is not recognized
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center"><?php echo date('F j, Y \a\t h:i A', strtotime($row['timestamp'])); ?></td>
                                                                    </tr>   
                                                                    <?php
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>  
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="committee_reports">
                                            <div class="pt-4">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-responsive-sm">
                                                        <colgroup>
                                                            <col style="width: 70%;">
                                                            <col style="width: 15%;">
                                                            <col style="width: 15%;">
                                                        </colgroup>
                                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                                            <tr>
                                                                <th>TITLE</th>
                                                                <th>ACTION TAKEN</th>
                                                                <th>TIMESTAMP</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody style="color: #000000;">
                                                            <?php 
                                                                include "connect.php";
                                                                $sql = "SELECT title, action, timestamp FROM history_log WHERE file_type = 'Committee Report' ORDER BY timestamp DESC LIMIT 100";
                                                                $stmt = $conn->prepare($sql);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                                if (!$result) {
                                                                    die("SQL Error: " . $conn->error);
                                                                }

                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $row['title']; ?></td>
                                                                        <td class="text-center fs-4">
                                                                            <?php 
                                                                                switch ($row['action']) {
                                                                                    case 'Created':
                                                                                        echo '<span class="badge badge-success">Created</span>';
                                                                                        break;
                                                                                    case 'Edited':
                                                                                        echo '<span class="badge badge-primary">Edited</span>';
                                                                                        break;
                                                                                    case 'Deleted':
                                                                                        echo '<span class="badge badge-danger">Deleted</span>';
                                                                                        break;
                                                                                    default:
                                                                                        echo $row['action']; // fallback if action is not recognized
                                                                                }
                                                                            ?>
                                                                        </td>
                                                                        <td class="text-center"><?php echo date('F j, Y \a\t h:i A', strtotime($row['timestamp'])); ?></td>
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