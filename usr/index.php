<?php
session_start();
include('vendor/inc/config.php'); // Include the configuration file

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if the user is an admin
    $stmt_admin = $mysqli->prepare("SELECT a_email, a_pwd, a_id FROM tms_admin WHERE a_email=? AND a_pwd=?");
    $stmt_admin->bind_param('ss', $email, $password);
    $stmt_admin->execute();
    $stmt_admin->bind_result($a_email, $a_pwd, $a_id);
    $admin_rs = $stmt_admin->fetch();
    
    if($admin_rs) {
        $_SESSION['a_id'] = $a_id;
        $_SESSION['login'] = $email;
        header("Location: admin/admin-dashboard.php");
        exit();
    }

    // Query to check if the user is a regular user
    $stmt_user = $mysqli->prepare("SELECT u_email, u_pwd, u_id FROM tms_user WHERE u_email=? AND u_pwd=?");
    $stmt_user->bind_param('ss', $email, $password);
    $stmt_user->execute();
    $stmt_user->bind_result($u_email, $u_pwd, $u_id);
    $user_rs = $stmt_user->fetch();
    
    if($user_rs) {
        $_SESSION['u_id'] = $u_id;
        $_SESSION['login'] = $email;
        header("Location: user-dashboard.php");
        exit();
    }

    $error = "Access Denied. Please check your credentials.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Booking System - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/css/sb-admin.css" rel="stylesheet">
    <style>
        .card-login {
            max-width: 400px;
            margin: 0 auto;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #78cee3;
            color: white;
            font-size: 1.5rem;
            text-align: center;
        }
        .card-body {
            padding: 2rem;
        }
        .form-control {
            border: 1px solid #78cee3;
            border-radius: 5px;
        }
        .btn-custom-success {
            background-color: #78cee3;
            border-color: #78cee3;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-custom-success:hover {
            background-color: #F8E1CC;
            border-color: #F8E1CC;
        }
        .btn-home {
            background-color: #F8E1CC;
            border-color: #F8E1CC;
            color: #4889cf;
            text-align: center;
            display: block;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-home:hover {
            background-color: #78cee3;
            border-color: #78cee3;
        }
        .text-center a {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-login mx-auto mt-5">
            <div class="card-header">Login</div>
            <div class="card-body">
                <?php if(isset($error)) {?>
                    <script>
                        setTimeout(function() { 
                            swal("Failed!", "<?php echo $error;?>!", "error");
                        }, 100);
                    </script>
                <?php } ?>
                <form method="POST">
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
                            <label for="inputEmail">Email address</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-label-group">
                            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required="required">
                            <label for="inputPassword">Password</label>
                        </div>
                    </div>
                    <input type="submit" name="login" class="btn btn-custom-success btn-block" value="Login">
                </form>
                <div class="text-center">
                    <a href="../index.php" class="btn btn-home">Home</a>
                </div>
            </div>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="vendor/js/swal.js"></script>
</body>
</html>
