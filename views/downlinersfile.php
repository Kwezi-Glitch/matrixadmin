<head>

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="./js/jquery.countdownTimer.min.js"></script>
</head>

<div class="row-fluid span12">

 <div class="span6">
<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
          <h5 class="" >Your Upliner's details</h5>
        </div>
        <div class="widget-content nopadding form-horizontal">
         
            <div class="control-group">
              <label class="control-label">Full Name : </label>
              <div class="controls">
                <p class=" form-control-static" > <?php echo $Login->upliner_details[1]['full_name'];//we pass a value of 1 for the first part of the array because there is only one upliner per downliner; therefore no loop is needed unlike in the downliner's file.?> </p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Phone Number : </label>
              <div class="controls">
                <p class=" form-control-static" ><?php echo $Login->upliner_details[1]['phone_number'];?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Bank Name : </label>
              <div class="controls">
                <p class=" form-control-static" ><?php echo $Login->upliner_details[1]['bank_name'];?> </p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Name : </label>
              <div class="controls">
                <p class=" form-control-static" ><?php echo $Login->upliner_details[1]['account_name'];?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Number : </label>
              <div class="controls">
               <p class=" form-control-static" ><?php echo $Login->upliner_details[1]['account_number'];?></p>
                 </div>
            </div>
           
            
        </div>
      </div>
	  </div>
	  
	  
	  
	  <div class="span6">
<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-calendar"></i> </span>
          <h5>Time Tab</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="transaction.php" method="post"  class="form-horizontal">
            
			
              <div class="alert alert-success alert-block"> <a class="close countdowntimer" data-dismiss="alert" href="#">�</a>
              <h4 class="alert-heading">&nbsp; You have until<span id="cdt" class="center"> <?php echo $Login->timeStop(3);?> </span> to make payment</h4>
               </div>
              <!--countdown timer-->
         <script>
          
               $(function(){
 	$("#cdt").countdowntimer({
 		startDate : "2017/10/10 12:00:00",
 		dateAndTime : "2020/10/10 12:00:00",
 		size : "lg"
 	});
 });
          </script>



            <div class="control-group text-center">
             <h1 class="icon-money">&nbsp; Payment Action</h1>
             <br><br>
            </div>

            <div class="control-group">
              <div class="alert alert-warning alert-block"> <a class="close" data-dismiss="alert" href="#">�</a>
              <h4 class="alert-heading">Notice!</h4>
             <p> Our system is fully automated and operates on a first come first serve basis. The earlier you complete your transaction the earlier you get remerged! A full payment cycle must be completed for you to also receive payment.
             </p>
             </div>
            </div>
			
            
            <div class="form-actions text-center">
              <input type="hidden" name="dwn_id" value=" <?php echo $_SESSION['matrix_user_id']; ?> " >
              <button type="submit" class="btn btn-large btn-success" >Paid</button>&nbsp; &nbsp;
			  
            </div>
            

          </form>  

          <form action="transaction.php" method="post">
          <div class="form-actions text-center">
              <input type="hidden" name="dwn_id" value=" <?php echo $_SESSION['matrix_user_id']; ?> " >
              <input type="hidden" name="package" value=" <?php echo $_SESSION['package']; ?> " >
              <input type="hidden" name="upl_id" value=" <?php echo $Login->upliner_details[1]['user_id']; ?> " >
			  <button type="submit" class="btn btn-large btn-danger">I can't Pay</button>
			  
            </div>


          </form>
        </div>
      </div>
	  </div>
	
	  </div>
	  


 