<!DOCTYPE html>
<html lang="en">

<head>
    <title>Martix Admin</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="css/matrix-login.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.min.css" />
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/mycss.css" />

</head>

<body>
    <div id="loginbox">
        <form id="loginform" class="form-vertical" action="" method="post">
            <div class="control-group normal_text">
                <h3><img src="img/logo.png" alt="Logo" /></h3>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_lg"><i class="icon-user"> </i></span><input type="text" name=user_name placeholder="Username" />
                    </div>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <div class="main_input_box">
                        <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" name=user_password placeholder="Password" />
                    </div>
                </div>
            </div>
            <div class="form-actions">
                <span class="pull-left"><a href="register.php" class="flip-link btn btn-info" id="to-recover">Register</a></span>
                <span class="pull-right"><button type="submit" class="btn btn-success" name="logon"> Login</button></span>
            </div>
        </form>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="js/matrix.login.js"></script>
    <!-- setting up sweet alert messages display -->
    <?php if(isset($Login->messages)): ?>
        <?php foreach($Login->messages as $message) : ?>
            <script> $(document).ready(function(){
                Swal.fire({
                    title:'Success',
                    icon: 'success',
                    confirmButtonText:'<a style="color:#fff" href="index.php">OK</a>',
                    text: '<?php echo $message; ?>',
                      })
                    })
             </script>
                <?php endforeach ?>
                <?php endif ?>

                <?php if(isset($Login->errors)): ?>
        <?php foreach($Login->errors as $error) : ?>
            <script> $(document).ready(function(){
                Swal.fire({
                    title:'Opps, something wrong...',
                    icon: 'error',
                    text: '<?php echo $error; ?>',
                      })
                    })
             </script>
                <?php endforeach ?>
                <?php endif ?>

</body>

</html>