<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gym";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
           
            $_SESSION['user'] = $user['email'];  // Start a session for the user
            header("Location: dashboard.html");  // Redirect to dashboard page
            exit(); // Prevent further script execution
        } else {
            $error = "Incorrect password. Please try again.";
        }
    } else {
        $error = "Email not registered.";
    }

    $stmt->close();
}
$conn->close();
?>