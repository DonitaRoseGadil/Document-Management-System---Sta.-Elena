<?php
    if(isset($_POST['save_account'])){
        include "connect.php";
        error_reporting(0);

        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_pasword = $_POST['confirm_password'];
        $role = $_POST['role'];
        $account_status = 'active';

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert into database
        $sql = "INSERT INTO accounts (email, password, role, account_status) 
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $email, $hashed_password, $role, $account_status);
        $stmt->execute();

        if ($stmt) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Account Created',
                            text: 'The account have been successfully created.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'manageAccounts.php';
                            }
                        });
                    });
                  </script>";    
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error creating the account.',
                            confirmButtonText: 'OK'
                        });
                    });
                  </script>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;    
        }

        $stmt->close();
        $conn->close();
    }
?>

<?php
    if (isset($_POST['update_account'])) {
        include "connect.php";

        // Sanitize and get inputs
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $account_status = isset($_POST['account_status']) && $_POST['account_status'] === 'active' ? 'active' : 'inactive';
        $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : '';

        
        // Update the record
        $sql = "UPDATE accounts SET account_status = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $account_status, $role, $id);
        $stmt->execute();

        if ($stmt) {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Account Updated',
                            text: 'The account have been successfully updated.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'manageAccounts.php';
                            }
                        });
                    });
                  </script>";
            
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'There was an error updating the account.',
                            confirmButtonText: 'OK'
                        });
                    });
                </script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
        }

        $stmt->close();
        $conn->close();
    }
?>


<!DOCTYPE html>
<html lang="en">
    
    <?php include('header.php'); ?>

<body>

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('sidebar.php'); ?>

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body" style="background-color: #f1f9f1">
            <!-- row -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center p-3 mt-4">
                                <h1 class="card-title flex-grow-1 fs-4 fw-bold text-dark text-center" style="color: #000000">LIST OF ACCOUNTS</h1>
                                <!-- Button trigger modal -->
                                <div class="button-container d-flex justify-content-end">
                                    <a href="#">
                                        <button type="button" class="btn btn-primary" style="background-color: #098209; color:#FFFFFF; border: none;" data-toggle="modal" data-target="#addAccountModal"><i class="fa fa-plus"></i>&nbsp;Add New Account</button>
                                    </a>  
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="example" class="display" style="min-width: 845px; width: 100%;">
                                        <colgroup>
                                            <col style="width: 35%;">
                                            <col style="width: 25%;">
                                            <col style="width: 15%;">
                                            <col style="width: 15%;">
                                        </colgroup>
                                        <thead class="text-center" style="background-color: #098209; color: #FFFFFF;">
                                            <tr>
                                                <th style="color: #FFFFFF;">Username</th>
                                                <th style="color: #FFFFFF;">Role</th>
                                                <th style="color: #FFFFFF;">Account Status</th>
                                                <th style="color: #FFFFFF;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-left" style="color: #000000;" >
                                            <?php
                                                $sql = "SELECT id, email, role, account_status FROM accounts";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if (!$result) {
                                                    die("SQL Error: " . $conn->error);
                                                }

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    ?>
                                                    <tr>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209; border-left: 1px solid #098209; text-transform: uppercase;"><?php echo $row["email"] ?></td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209; text-align: center; text-transform: uppercase;">
                                                            <?php 
                                                                switch ($row["role"]) {
                                                                    case "master":
                                                                        echo "Sangguniang Bayan Secretary";
                                                                        break;
                                                                    case "admin":
                                                                        echo "Sangguniang Bayan Staff";
                                                                        break;
                                                                    case "user":
                                                                        echo "Sangguniang Bayan Council";
                                                                        break;
                                                                    default:
                                                                        echo strtoupper($row["role"]); // fallback display
                                                                }
                                                            ?>
                                                        </td>
                                                        <td style="pointer-events: none; border-bottom: 1px solid #098209;" class="text-center fs-4">
                                                            <?php 
                                                                switch ($row['account_status']) {
                                                                    case 'active':
                                                                        echo '<span class="badge badge-success">Active</span>';
                                                                        break;
                                                                    case 'inactive':
                                                                        echo '<span class="badge badge-danger">Inactive</span>';
                                                                        break;
                                                                    default:
                                                                        echo $row['account_status']; // fallback if action is not recognized
                                                                }
                                                            ?>
                                                        </td>
                                                        <td style="border-bottom: 1px solid #098209; border-right: 1px solid #098209; text-align: center; vertical-align: middle;">
                                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                                <!--Button trigger modal-->
                                                                <?php if ($role === 'admin' || $role === 'master') { ?>
                                                                    <?php if ($row['role'] === 'master') { ?>
                                                                        <!-- Disabled actions with SweetAlert -->
                                                                        <a onclick="showProtectedAlert()" class="btn btn-success btn-sm d-flex align-items-center justify-content-center p-2 ml-1 mr-1">
                                                                            <i class="fa fa-edit" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                        </a>
                                                                        <a onclick="showProtectedAlert()" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-2">
                                                                            <i class="fa fa-trash" aria-hidden="true" style="color: #FFFFFF"></i>
                                                                        </a>
                                                                    <?php } else { ?>
                                                                        <a onclick="confirmEdit(<?php echo $row['id']; ?>)" class="btn btn-success btn-sm d-flex align-items-center justify-content-center p-2 ml-1 mr-1" data-toggle="modal">
                                                                            <i class="fa fa-edit" aria-hidden="true" style="color: #FFFFFF;"></i>
                                                                        </a>
                                                                        <a onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-sm d-flex align-items-center justify-content-center p-2">
                                                                            <i class="fa fa-trash" aria-hidden="true" style="color: #FFFFFF"></i>
                                                                        </a>
                                                                    <?php } ?>
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
                <!--**********************************
                    MODALS
                ***********************************-->
                <!-- Add Modal -->  
                <div class="modal fade" id="addAccountModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="color: #000000">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color:#000000">ADD ACOUNT</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="basic-form">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" onsubmit="return validatePasswords();"  method="POST">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Username</label>
                                            <div class="col-sm-8">
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Please type here..." required>
                                                <small id="emailWarning" style="color: red; display: none;">Username already exists!</small>
                                            </div>
                                        </div>
                                        <!-- Password Field -->
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Password</label>
                                            <div class="col-sm-8">
                                                <div style="position: relative;">
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Please type here..." required style="padding-right: 35px;">
                                                    <i class="fa fa-eye-slash" id="togglePassword" onclick="toggleVisibility('password', 'togglePassword')" 
                                                        style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); font-size: 18px; cursor: pointer; color: #098209;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Confirm Password Field -->
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Confirm Password</label>
                                            <div class="col-sm-8">
                                                <div style="position: relative;">
                                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Please type here..." required style="padding-right: 35px;">
                                                    <i class="fa fa-eye-slash" id="toggleConfirmPassword" onclick="toggleVisibility('confirm_password', 'toggleConfirmPassword')" 
                                                        style="position: absolute; top: 50%; right: 10px; transform: translateY(-50%); font-size: 18px; cursor: pointer; color: #098209;"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-form-label col-sm-4 pt-0">Role</label>
                                            <div class="col-sm-8 d-flex align-items-center">
                                                <label class="mr-3 mb-0">
                                                    <input type="radio" name="role" value="admin" required> Admin
                                                </label>
                                                <label class="mb-0">
                                                    <input type="radio" name="role" value="user" class="ml-2"> User
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row justify-content-center mt-4">
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn btn-primary mr-2" name="save_account" style="background-color: #098209; color:#FFFFFF; border: none;">Add Account</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edit Modal -->
                <div class="modal fade" id="editAccountModal">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="color: #000000">
                            <div class="modal-header">
                                <h5 class="modal-title" style="color:#000000">EDIT ACCOUNT</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="basic-form">
                                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                                        <input type="hidden" name="id">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Username</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="email" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Account Status</label>
                                            <div class="col-sm-8">
                                                <input type="checkbox" id="account_status_toggle" value="active" data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger" data-size="sm" data-width="30%" style="border-radius: 50px; text-align: center;">
                                                <input type="hidden" name="account_status" id="account_status_value">
                                            </div>
                                        </div>
                                        <div class="form-group row mb-0">
                                            <label class="col-form-label col-sm-4 pt-0">Role</label>
                                            <div class="col-sm-8 d-flex align-items-center">
                                                <label class="mr-3 mb-0">
                                                    <input type="radio" name="role" value="admin" required> Admin
                                                </label>
                                                <label class="mb-0">
                                                    <input type="radio" name="role" value="user" class="ml-2"> User
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group row justify-content-center mt-4">
                                            <div class="col-sm-12 text-center">
                                                <button type="submit" class="btn btn-primary mr-2" name="update_account" style="background-color: #098209; color:#FFFFFF; border: none;">Update Account</button>
                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Toggle -->
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


    <script>
        $(document).ready(function () {
            $('#email').on('input', function () {
                var email = $(this).val();

                if (email.length > 0) {
                    $.ajax({
                        url: 'check_username.php', // Create this PHP file
                        type: 'POST',
                        data: { email: email },
                        success: function (response) {
                            if (response === 'exists') {
                                $('#emailWarning').show();
                                $('button[name="save_account"]').prop('disabled', true);
                            } else {
                                $('#emailWarning').hide();
                                $('button[name="save_account"]').prop('disabled', false);
                            }
                        }
                    });
                } else {
                    $('#emailWarning').hide();
                    $('button[name="save_account"]').prop('disabled', false);
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Function to reset the add account form
            function resetAddAccountForm() {
                $('#addAccountModal form')[0].reset();
            }
            
            // Reset forms when modals are closed (X button or backdrop click)
            $('#addAccountModal').on('hidden.bs.modal', resetAddAccountForm);
            
            // Reset forms when Cancel buttons are clicked
            $('#addAccountModal .btn-danger').click(resetAddAccountForm);
            
        });
    </script>
    
    <script>
        function validatePasswords() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch',
                    text: 'Password and Confirm Password do not match.',
                    confirmButtonText: 'OK'
                });
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
        </script>                                            
    
    
    <!--Eye Toggle Script-->
    <script>
        
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>

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
                                text: "Acount has been deleted.",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 2000
                            });
                            window.location.href = 'deleteaccount.php?id=' + id;
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
                    // AJAX fetch data here instead of redirecting
                    $.ajax({
                        url: "fetch_account.php",
                        type: "GET",
                        data: { id: id },
                        dataType: "json",
                        success: function(data) {
                            if (data.success) {
                                // Populate modal fields
                                $('input[name="id"]').val(data.id);
                                $('input[name="email"]').val(data.email);
                                // Set toggle and hidden field
                                let isActive = data.account_status === 'active';
                                $('#account_status_toggle')
                                    .prop('checked', isActive)
                                    .bootstrapToggle(isActive ? 'on' : 'off');

                                $('#account_status_value').val(isActive ? 'active' : 'inactive');
                                $('input[name="role"][value="' + data.role + '"]').prop('checked', true);

                                // Show modal
                                $('#editAccountModal').modal('show');
                            } else {
                                Swal.fire("Error", data.message, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Error", "Failed to fetch account data.", "error");
                        }
                    });
                }
            });
        }

        $('#account_status_toggle').change(function () {
            $('#account_status_value').val(this.checked ? 'active' : 'inactive');
        });

        function showProtectedAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Action Not Allowed',
                text: 'This master account cannot be EDITED or DELETED.',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

</body>

</html>