<?php
require_once 'config/config.php';
require_once 'classes/Login.php';
require_once 'translations/en.php';

$Login= new Login();

if($Login->userIsLoggedin()==true){
    include 'views/select_package.php';
}
else{
    include 'views/not_logged_in.php';
}


?>