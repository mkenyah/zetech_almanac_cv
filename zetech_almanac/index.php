<?php
include_once "db.php";
session_start();

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: admin.php"); // Redirect to admin.php if already logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zetech University - Almanac</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            width: inherit;
            overflow-x: hidden;
        }
        html, body {
            overflow: hidden;
            height: 100%;
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: navy;
        }
        .logo {
            height: 60px;
        }
        #header_links {
            margin-top: 20px;
            gap: 20px;
        }
        #login_button {
            background-color: navy;
        }
        #loginbtn {
            background-color: navy;
        }
        #containerform {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; 
            margin-top: -70px;
        }

        .zetech{
            text-align:  center;
            margin-top: -50px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row text-white py-2" style="background-color: navy;">
        <div class="col-md-6">
            <h3 class="ml-3">
                <img src="./images/logo.png" class="logo" alt="">

               
            </h3>

            <h2 class="zetech">Almanac</h2>
        </div>
        <div id="header_links" class="col-md-6 text-right">
            <a href="#" class="text-white mr-3">Main website</a>
            <i class="fa fa-sign-in" aria-hidden="true"></i>
            <?php if (!isset($_SESSION['username'])): ?>
                <!-- <a href="./user_dashboard.php" class="text-white" id="login_button">Login</a> -->
            <?php else: ?>
                <a href="admin.php" class="text-white" id="login_button">Admin Panel</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="containerform" class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow" style="width: 350px;">
        <h4 class="text-center mb-4">Login</h4>
        
        <!-- Display error message if session contains an error -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <?php unset($_SESSION['error_message']); ?>
            </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-light border" onclick="togglePasswordVisibility()">👁️</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" id="loginbtn">Login</button>
            <a href="./forgot_password.php" class="d-block text-center mt-2">Forgot password?</a>
        </form>
    </div>
</div>

<footer class="text-center text-white py-2" style="background-color: navy;">
    &copy; 2025 Zetech University Almanac.
</footer>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
