<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "registration"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $checkQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('This email is already registered. Please use a different email.'); window.location.href='registration.html';</script>";
    } else {
        $insertQuery = "INSERT INTO users (email, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        if ($stmt) {
            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();
            echo "<script>alert('Registration successful!'); window.location.href='registration.html';</script>";
            $stmt->close();
        } else {
            echo "<script>alert('Error during registration. Please try again.'); window.location.href='registration.html';</script>";
        }
    }
}

$conn->close();
?>
