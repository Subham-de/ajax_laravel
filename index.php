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
                <a href="index.php"><img src="./Images/quiz-logo.jpg" alt="logo" height="200" width="200" ></a>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <div id="welcomeMessage"></div>
                    <div>
                        <button id="logoutBtn" class="btn-sm btn-danger ml-2">Logout</button>
                    </div>
                </div>
                <div>
                <button id="add_question_btn" class="btn btn-primary" style="margin-top: 100px; margin-left:350px">Add new + </button>
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
                        <b>Welcome</b> <br> to online examination system attain your exam Online !
                    
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
            $('#add_question_btn').click(function() {
                loadContent('add_question.php');
            });

            function loadContent(url) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function(response) {
                        $('#content_container').html(response);
                        $("#show_page").hide();
                        $("#add_question_btn").hide()

                    },
                    error: function() {
                        $('#content_container').html('<div class="alert alert-danger" role="alert">An error occurred while loading content.</div>');
                    }
                });
            }

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
                        window.location.href = 'login.php'; // Redirect to login page after logout
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


