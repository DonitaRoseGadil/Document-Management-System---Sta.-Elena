<?php
    include("session.php");
    include("connect.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = htmlspecialchars(trim($_POST['email']));
        $password = htmlspecialchars(trim($_POST['password']));

        $query = $conn->prepare("SELECT * FROM accounts WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                if ($user['account_status'] === 'active') {
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];

                    // Generate Token
                    $token = bin2hex(random_bytes(32));
                    $_SESSION['token'] = $token;

                    // Save Token in Database
                    $updateToken = $conn->prepare("UPDATE accounts SET token=? WHERE id=?");
                    $updateToken->bind_param("si", $token, $user['id']);
                    $updateToken->execute();

                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Login Successful',
                                    showConfirmButton: false,
                                    text: 'You have been logged in successfully.',
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = 'dashboard.php';
                                });
                            });
                        </script>";
                } else {
                    echo "<script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Account Inactive',
                                    text: 'Your account is not active. Please contact the administrator.',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    window.location.href = 'login.php';
                                });
                            });
                        </script>";
                }
            } else {
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid Login',
                                text: 'Invalid credentials. Please try again.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.href = 'login.php';
                            });
                        });
                    </script>";
            }
        } else {
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Login',
                            text: 'User not found. Please try again.',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = 'login.php';
                        });
                    });
                </script>";
        }
    }
?>


<!DOCTYPE html>
<html lang="en" class="h-100">

<?php include "header.php"; ?>

<body class="h-100" style="background: url('./images/loginBG.png') no-repeat center center fixed; background-size: cover;">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <!--TEXT LEFT-->
                <div class="col-md-3 mr-5">
                    <div class="col-xl-12">
                        <img src="./images/logo.png" alt="Welcome Image" class="img-fluid d-block mx-auto" style="width: 50%; height: auto;">
                        <h1 class="text-center mt-4" style="color:#000000; font-weight: bold;">WELCOME<h1>
                        <h3 class="text-center mt-2" style="color:#000000;">Sangguniang Bayan Office</h3>
                        <h4 class="text-center mt-2" style="color:#000000;">Document Management System</h4>
                    </div>
                </div>
                <!--TEXT RIGHT-->
                <div class="col-md-3 ml-5">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h1 class="text-center" style="color:#098209">Log in</h1>
                                    <h4 class="text-center mb-4" style="color:#000000">Sign in your account</h4>
                                    <form id="loginForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                        <!--Email-->
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Username" name="email" id="email">
                                        </div>
                                        <!-- Password -->
                                        <div class="form-group" style="position: relative; display: flex; align-items: center;">
                                            <input type="password" class="form-control" placeholder="Password" name="password" id="password"
                                                style="padding-right: 35px; width: 100%;">
                                            <i class="fa fa-eye-slash" id="toggleEye" onclick="togglePassword()" 
                                                style="position: absolute; right: 10px; font-size: 18px; cursor: pointer; color: #098209;"></i>
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                                <div class="form-check ml-2">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block" style="background-color: #098209; border-color:#098209;">Sign me in</button>
                                        </div>
                                    </form> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("toggleEye");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye"); // Revert to normal eye icon
                
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash"); // Change icon to indicate visibility
            }
        }
    </script>

</body>

</html>