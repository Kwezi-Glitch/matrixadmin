<!DOCTYPE html>
<html lang="en">

<head>
    <title>Ponzi Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/matrix-login.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.min.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>

</head>

<body>
    <div id="loginbox">
        <form id="loginform" class="form-vertical registerform" action="" method="post"><!--in this form, we shall be using the jquery validate plugin to verify the input fields. In the scripting.js file, we define a function that captures this form and validates the input fields.-->
            <div class="control-group normal_text">
                <h3><img src="img/logo.png" alt="Logo" /></h3>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_lg"><i class="icon-user"> </i></span><input type="text" name="user_name" placeholder="Username" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_lg"><i class="icon-envelope"> </i></span><input type="email" name="user_email" placeholder="Enter your email" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" id="password" name="user_password" placeholder="Your Password" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_ly"><i class="icon-exclamation-sign"></i></span><input type="password" name="user_password_repeat" placeholder="Password again" />
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <span class="pull-left"><a href="index.php" class=" btn btn-info" id="to-recover">Login</a></span>
                <span class="pull-right"><button type="submit" name="submit" class="btn btn-success">Register</button></span>
            </div>
        </form>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/additional-methods.min.js"></script>
    <script src="js/scripting.js"></script>
    <script src="js/matrix.login.js"></script>

    <!-- setting up sweet alert messages display -->
    
</body>

</html>