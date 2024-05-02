<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <div>
            <img src="./Images/quiz-logo.jpg" alt="logo" height="200" width="200">
        </div>
        <div class="row">
            <div class="col-6">
                <img src="./Images/new-main.png" alt="main" class="img-fluid">
            </div>
            <div class="col-6">
                <div class="border p-3 w-75" style="margin: -10px;">
                    <?php
                    if (isset($_GET['error']) && $_GET['error'] == 'incorrect_password') {
                        echo '<p class="text-danger">Incorrect Password try again !</p>';
                    }

                    if (isset($_GET['error']) && $_GET['error'] == 'user_not_found') {
                        echo '<p class="text-danger">User not found try another !</p>';
                    }
                    ?>
                    <h2>Login</h2>
                    <form id="loginForm" action="login_process.php" method="post">
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <small id="emailError" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small id="passwordError" class="form-text text-danger"></small>
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                    </form>
                    <div class="mt-3">
                        <p>I'm new here ? <a href="signup.php">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#email').keyup(function() {
                $('#emailError').text('');
            });

            $('#password').keyup(function() {
                $('#passwordError').text('');
            });

            $('#loginForm').submit(function(event) {
                var email = $('#email').val().trim();
                var password = $('#password').val().trim();

                if (email === '') {
                    $('#emailError').text('Email cannot be empty');
                    event.preventDefault();
                }

                if (password === '') {
                    $('#passwordError').text('Password cannot be empty');
                    event.preventDefault();
                }
            });
        });
    </script>
</body>

</html>