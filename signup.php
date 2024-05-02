<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        form {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <img src="./Images/quiz-logo.jpg" alt="logo" height="200" width="200">
        </header>
        <div class="d-flex justify-content-center align-items-center flex-column" style="margin-top: -50px;">
            <form action="signup_process.php" method="post" class="border p-3 w-50">
                <p style="font-size: 30px;" class="text-center"><b>Welcome</b> back ! 👋</p>

                <?php
                // check error msg 
                if (isset($_GET['error']) && $_GET['error'] == 'user_exists') {
                    echo '<p class="text-danger">User already exists.</p>';
                }
                ?>

                <label for="name" class="form-label">Create your account</label>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <!-- <div>
                    <select name="user_type" class="form-control mt-2 mb-3">
                        <option value="0">select user type</option>
                        <option value="user">user</option>
                        <option value="admin">admin</option>
                    </select>
                </div> -->
                <button type="submit" class="btn btn-primary">Sign Up</button>
                <p class="mt-2">already have an account? <a href="login.php">login now</a></p>
            </form>
        </div>
    </div>
</body>

</html>