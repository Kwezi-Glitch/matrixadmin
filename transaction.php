<?php
require_once 'config/config.php';
require_once 'classes/Login.php';
require_once 'classes/Confirm.php';
require_once 'translations/en.php';

$Login= new Login();
$Confirm= new Confirm();

if($Login->userIsLoggedin()==true){
    include 'views/transaction.php';
}
else{
    include 'views/not_logged_in.php';
}


?>