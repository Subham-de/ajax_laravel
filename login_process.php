<?php
include('conn.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check_sql = "SELECT id, email, password FROM users WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 1) {
        $row = $check_result->fetch_assoc();
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        } else {
            header("Location: login.php?error=incorrect_password");
            exit();
        }
    } else {
        header("Location: login.php?error=user_not_found");
        exit();
    }

    $check_stmt->close();
} else {
    header("Location: login.php");
    exit();
}
?>
