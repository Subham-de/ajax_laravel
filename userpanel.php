<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Quiz System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Customize styles */
        #welcomeMessage {
            font-size: 18px;
        }

        #add_question_btn {
            margin-top: 10px;
        }
        .msg{
            font-family:Arial, Helvetica, sans-serif;
            font-size: 42px;
            letter-spacing: 2px;
            font-style: oblique;
        }
        .image-main{
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-6">
                <img src="./Images/quiz-logo.jpg" alt="logo" height="200" width="200" >
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <div id="welcomeMessage"></div>
                    <div>
                        <button id="logoutBtn" class="btn-sm btn-danger ml-2">Logout</button>
                    </div>
                </div>
                <div>
                <button id="fetch_table" class="btn btn-primary" style="margin-top: 100px; margin-left:350px">Start exam</button>
                </div>
            </div>
        </div>
        <div id="show_page" class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <img src="./Images/online.png" alt="exaam" class="img-fluid image-main" >
                </div>
                <div class="col-md-6">
                    <p class="msg" style="color:blueviolet">
                        <b>Welcome user,</b> <br> to online examination system attain your exam Online !
                    
                    </p>
                </div>
            </div>
        </div>

        <div id="content_container" class="mt-4">
            <!-- Content will be loaded here -->
        </div> 
    </div>

    <!-- CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {

            function showData() {
                $.ajax({
                    type: 'GET',
                    url: 'fetch_subjects.php',
                    success: function(response) {
                        $("#show_page").html(response)
                    },
                    error: function() {
                        $('#show_page').html('<div class="alert alert-danger" role="alert">An error occurred while fetching data.</div>');
                    }
                });
            }

            // Click event for the Show Data button
            $(document).on('click', '#fetch_table', function() {
                showData();
            });

            // Check if the user is logged in
            $.ajax({
                type: 'POST',
                url: 'check_login.php',
                success: function(response) {
                    if (response.status === 'success') {
                        displayWelcomeMessage(response.email);
                    }
                },
                error: function() {
                    $('#welcomeMessage').html('<p class="alert alert-danger">An error occurred while checking login status.</p>');
                }
            });

            $('#logoutBtn').click(function() {
                $.ajax({
                    type: 'POST',
                    url: 'logout.php',
                    success: function(response) {
                        window.location.href = 'login.php'; 
                    },
                    error: function() {
                        alert('An error occurred while logging out.');
                    }
                });
            });

            function displayWelcomeMessage(email) {
                $('#welcomeMessage').html('<p class="badge  p-2"> <img src="./Images/perosn_1.jpg" alt="person" height="40" width="40"> ' + email + '</p>');
            }
        });
    </script>
</body>

</html>


