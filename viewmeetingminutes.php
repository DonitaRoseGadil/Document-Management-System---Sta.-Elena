<!DOCTYPE html>
<html lang="en">

<?php 
    include "header.php"; 
    include "connect.php"; 


    date_default_timezone_set('Asia/Manila');

    $lastUpdatedText = "No updates yet";


    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $resolution_id = intval($_GET['id']);

        if ($resolution_id > 0) {
            // Fetch the last updated timestamp for the specific file
            $sql = "SELECT timestamp FROM history_log WHERE file_id = ? ORDER BY timestamp DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $resolution_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $lastUpdated = strtotime($row["timestamp"]); // Convert to Unix timestamp
                $currentDate = date("Y-m-d"); // Get today's date
                $updatedDate = date("Y-m-d", $lastUpdated); // Get last updated date

                if ($currentDate === $updatedDate) {
                    // If updated today, show "Today at [time]"
                    $lastUpdatedText = "Last updated today at " . date("g:i A", $lastUpdated);
                } else {
                    // Show full date and time if not today
                    $lastUpdatedText = "Last updated on " . date("F j, Y \\a\\t g:i A", $lastUpdated);
                }
            }

            $stmt->close();
        }

        $optRadio = isset($_POST['optradio']) ? $_POST['optradio'] : '';

        // Initialize fields safely
        $returnNo = isset($_POST['returnNo']) ? mysqli_real_escape_string($conn, $_POST['returnNo']) : '';
        $returnDate = isset($_POST['returnDate']) ? mysqli_real_escape_string($conn, $_POST['returnDate']) : '';
        $resolutionNo = isset($_POST['resolutionNo']) ? mysqli_real_escape_string($conn, $_POST['resolutionNo']) : '';
        $resolutionDate = isset($_POST['resolutionDate']) ? mysqli_real_escape_string($conn, $_POST['resolutionDate']) : '';

        // Use switch case to handle conditions
        switch ($optRadio) {
            case 'Approved':
                $returnNo = "";
                $returnDate = "";
                break;
            case 'Returned':
                $resolutionNo = "";
                $resolutionDate = "";
                break;
            default:
                $returnNo = "";
                $returnDate = "";
                $resolutionNo = "";
                $resolutionDate = "";
                break;
        }

    }

    $conn->close();
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
                <!-- row -->
                 
                <div class="row d-flex justify-content-center">
                    <div class="col-xl-8 col-xxl-12 items-center">                        
                        <div class="card" style="align-self: center;">
                            <div class="card-header d-flex justify-content-center">
                                <h4 class="card-title text-center" style="color: #098209; ">VIEW ORDER OF BUSINESS</h4>
                            </div>
                            <?php
                                include("connect.php");
                                $id = $_GET['id'];
                                $sql = "SELECT * FROM `minutes` WHERE id = $id LIMIT 1";
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                // Determine the value for optRadio based on conditions
                                $optRadio = "";
                                if ($row['resolutionNo'] != "" && $row['resolutionDate'] != "") {
                                    $optRadio = "Approved";
                                } elseif ($row['returnNo'] != "" &&  $row['returnDate'] != "") {
                                    $optRadio = "Returned";
                                } 
                            ?>
                            <div class="card-body">
                                <div class="basic-form">
                                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">No. of Regular Session</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" placeholder="Please type here..." value="<?php echo $row['no_regSession']?>" id="no_regSession" name="no_regSession" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color:#000000">Date:</label>
                                            <div class="col-sm-9">
                                                <input type="date" class="form-control" placeholder="Please type here..." value="<?php echo $row['date']?>" id="date" name="date" disabled>
                                            </div>
                                        </div>
                                        <label style="color: #000000">Attachment for Order of Business:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="<?php echo $row['genAttachment']; ?>" id="genAttachment" name="genAttachment" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" style="background-color: #098209; border: none; outline: none;" type="button" onclick="viewFile('<?php echo $row['id']; ?>', 'genAttachment')">View File</button>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Resolution No.:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['resNo']?>" name="resNo" disabled>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label" style="color: #000000">Title:</label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="title" name="title" rows="1" style="resize: none; overflow: hidden;" disabled><?php echo htmlspecialchars_decode($row['title']); ?></textarea>
                                            </div>
                                        </div>
                                        <!-- Status Field -->
                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Status:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" value="<?php echo $row['status']?>" name="status" required>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="status" class="col-sm-3 col-form-label" style="color: #000000">Select option if applicable:</label>
                                            <div class="col-sm-9 mt-2">
                                                <label class="radio-inline mr-5" style="color: #000000">
                                                    <input type="radio" name="optradio" class="deselectable-radio" value="Returned" <?php echo ($optRadio === "Returned") ? 'checked' : ''; ?> disabled > Returned
                                                </label>
                                                <label class="radio-inline" style="color: #000000">
                                                    <input type="radio" name="optradio" class="deselectable-radio" value="Approved" <?php echo ($optRadio === "Approved") ? 'checked' : ''; ?> disabled > Approved
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Hidden fields section -->
                                        <div class="form-group row extra-fields mt-3" style="display: none;">
                                            <div class="col-sm-12" id="extraFields">
                                                <!-- Dynamic fields go here -->
                                            </div>
                                        </div>

                                        <label style="color: #000000">Upload Attachment as Supporting Documents:</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="<?php echo $row['attachment']; ?>" id="attachment" name="attachment" disabled>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" style="background-color: #098209; border: none; outline: none;" type="button" onclick="viewFile('<?php echo $row['id']; ?>', 'attachment')">View File</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- View History Button -->
                            <div class="card-footer d-sm-flex justify-content-between">
                                <div class="card-footer-link mb-4 mb-sm-0">
                                    <p class="card-text text-dark d-inline"><?php echo $lastUpdatedText; ?></p>
                                </div>
                                <button type="button" class="btn text-white" style="background-color: #098209;" data-toggle="modal" data-target="#historyModal">View History</button>
                            </div>

                            <!-- Modal for Viewing History -->
                            <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true d-flex justify-content center">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="historyModalLabel">File History</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body d-flex justify-content-center">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="color: #000000; font-weight:bold; text-align: center;">Title</th>
                                                        <th style="color: #000000; font-weight:bold; text-align: center;">Action</th>
                                                        <th style="color: #000000; font-weight:bold; text-align: center;">Status</th>
                                                        <th style="color: #000000; font-weight:bold; text-align: center;">Timestamp</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="historyTableBody">
                                                    <tr><td colspan="4">Loading history...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- <div class="modal-footer">
                                            <button type="button" class="btn btn-danger text-white" data-dismiss="modal">Close</button>
                                        </div> -->
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


    <script>
        function viewFile(id, field) {
        let filePath = document.getElementById(field).value;
        
        if (!filePath) {
            alert("No file available to view.");
            return;
        }

        // Check if filePath is a direct URL or stored in the database
        if (filePath.startsWith("http") || filePath.endsWith(".pdf")) {
            window.open(filePath, '_blank');  // Open direct URL
        } else {
            window.open(`fetch_pdf.php?id=${id}&field=${field}`, '_blank'); // Fetch from database
        }
    }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // Global function to load resolution history
            window.loadHistory = function () {
                let resolutionId = "<?php echo $resolution_id; ?>";

                if (!resolutionId) {
                    $('#historyTableBody').html("<tr><td colspan='4'>No history available.</td></tr>");
                    return;
                }

                fetch(`fetch_history.php?id=${resolutionId}&file_type=minutes`)
                    .then(response => response.json())
                    .then(data => {
                        let historyHtml = "";
                        if (Array.isArray(data) && data.length > 0) {
                            data.forEach(log => {
                                historyHtml += `<tr id="history-row-${log.id}">
                                                    <td style="color: #000000;">${log.title}</td>
                                                    <td style="color: #000000;">${log.action}</td>
                                                    <td style="color: #000000;">${log.status}</td>
                                                    <td style="color: #000000;">${log.timestamp}</td>
                                                    <?php if ($role === 'master') { ?>
                                                        <td style="text-align: center;">
                                                            <a onclick="confirmDelete(${log.id})" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-2" title="Delete">
                                                                <i class="fa fa-trash" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                            </a>
                                                        </td>
                                                    <?php } ?>
                                                </tr>`;
                            });
                        } else {
                            historyHtml = "<tr><td colspan='4'>No history found.</td></tr>";
                        }
                        document.getElementById("historyTableBody").innerHTML = historyHtml;
                    })
                    .catch(error => {
                        console.error("Error fetching history:", error);
                        document.getElementById("historyTableBody").innerHTML = "<tr><td colspan='4'>Error loading history.</td></tr>";
                    });
            }

            // When the modal is shown, load the history table
            $('#historyModal').on('show.bs.modal', loadHistory);

            // Global delete function
            window.confirmDelete = function (id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this action!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('deleteHistory.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: 'id=' + encodeURIComponent(id)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'The history item has been deleted.',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                setTimeout(() => {
                                    location.reload();
                                }, 1600); // Wait after SweetAlert

                                // Check if the modal is currently open before refreshing
                                if ($('#historyModal').hasClass('show')) {
                                    loadHistory(); // Refresh the modal content
                                }

                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error deleting: ' + (data.error || "Unknown error"),
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Delete error:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Server error while deleting.',
                            });
                        });
                    }
                });
            };
        });
    </script>

    <script>
        function autoResizeTextarea(textarea) {
            textarea.style.height = 'auto'; // Reset height to recalculate
            textarea.style.height = textarea.scrollHeight + 'px'; // Set to scrollHeight
        }

        document.addEventListener("DOMContentLoaded", function() {
            const textarea = document.getElementById("title");

            // Resize on input
            textarea.addEventListener("input", function() {
                autoResizeTextarea(this);
            });

            // Resize initially in case there's preloaded content
            autoResizeTextarea(textarea);
        });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll("input[name='optradio']");
            const extraFieldsContainer = document.querySelector("#extraFields");
            const extraFieldsRow = document.querySelector(".extra-fields");

            function showExtraFields(selectedValue) {
                extraFieldsRow.style.display = "block";

                if (selectedValue === "Returned") {
                    extraFieldsContainer.innerHTML = `
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Return No.:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo isset($row['returnNo']) ? $row['returnNo'] : ''; ?>" name="returnNo" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Return Date:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" value="<?php echo isset($row['returnDate']) ? $row['returnDate'] : ''; ?>" name="returnDate" readonly>
                            </div>
                        </div>
                    `;
                } else if (selectedValue === "Approved") {
                    extraFieldsContainer.innerHTML = `
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Resolution No.:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="<?php echo isset($row['resolutionNo']) ? $row['resolutionNo'] : ''; ?>" name="resolutionNo" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" style="color:#000000">Resolution Date Approved:</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" value="<?php echo isset($row['resolutionDate']) ? $row['resolutionDate'] : ''; ?>" name="resolutionDate" readonly>
                            </div>
                        </div>
                    `;
                }
            }

            // Check if a radio button is already selected on page load
            const selectedRadio = document.querySelector("input[name='optradio']:checked");
            if (selectedRadio) {
                showExtraFields(selectedRadio.value);
            } else {
                extraFieldsRow.style.display = "none";
            }

            // Add event listeners to radio buttons
            radios.forEach(radio => {
                radio.addEventListener('click', function () {
                    if (this.checked) {
                        if (this.previousChecked) {
                            this.checked = false;
                            this.previousChecked = false;
                            extraFieldsRow.style.display = "none";
                            extraFieldsContainer.innerHTML = "";
                        } else {
                            radios.forEach(r => r.previousChecked = false);
                            this.previousChecked = true;
                            showExtraFields(this.value);
                        }
                    }
                });
            });
        });
    </script>


        
</body>

</html>