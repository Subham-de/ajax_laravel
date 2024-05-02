<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email already exists in the database
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Email already exists,
        header("Location: signup.php?error=user_exists");
        exit();
    }


    // email doesn't exist proceessed with signup process.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert_sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ss", $email, $hashed_password);

    if ($insert_stmt->execute()) {
        header("Location: login.php?signup=success");
        exit();
    } else {
        header("Location: signup.php?error=signup_failed");
        exit();
    }

    $insert_stmt->close();
    $check_stmt->close();
} else {
    header("Location: signup.php");
    exit();
}
?>
