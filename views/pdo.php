<?php

$dsn='mysql:dbname=trial1;host=localhost';

$db=new PDO($dsn,'root','');

if($db){
    echo "connected";
}
else{
    echo "not connected";
}
?>