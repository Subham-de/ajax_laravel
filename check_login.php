<?php
session_start();

$response = array();

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $response['status'] = 'success';
    $response['email'] = $_SESSION['email'];
} else {
    $response['status'] = 'failure';
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
