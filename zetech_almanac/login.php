<?php
include_once 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the user exists in the database
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password against the hashed password stored in DB
        if (password_verify($password, $user['password'])) {
            // Login successful - Set session
            $_SESSION['username'] = $user['username'];
            header("Location: admin.php"); // Redirect to admin panel
            exit();
        } else {
            $_SESSION['error_message'] = "Incorrect password.";
            header("Location: index.php"); // Redirect back to login
            exit();
        }
    } else {
        $_SESSION['error_message'] = "User not found.";
        header("Location: index.php"); // Redirect back to login
        exit();
    }
}
?>
