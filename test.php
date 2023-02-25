<?php 
require_once 'config/config.php';
require_once 'classes/Login.php';
require_once 'translations/en.php';

$Login= new Login();

echo $Login->timeStop($_SESSION['matrix_user_id']);



// echo $date;

?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="js/jQuery.countdownTimer.js"></script>
<!-- Good alternative is to include minified file jQuery.countdownTimer.min.js -->
<link rel="stylesheet" type="text/css" href="css/jQuery.countdownTimer.css" />
<!-- For regional language support, include below file -->
<!--<script type="text/javascript" src="js/localisation/jQuery.countdownTimer-[region-code].js"></script>-->
<div id="countdowntimer"><span id="future_date">;</span>
<P>
	this div is meant to display a timer.
</P>

</div>

<script type="text/javascript">
	  $(function(){
 		$("#future_date").countdowntimer({
 		startDate : "2017/10/10 12:00:00",
 		dateAndTime : "2020/10/10 12:00:00",
 		size : "lg"
 	});
 });
</script>