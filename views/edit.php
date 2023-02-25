<?php include "header.php"; ?>
<?php include "navigation.php"; ?>
<?php include "sidebar.php"; ?>

<?php 
$Login->getPaymentDetails($_SESSION['matrix_user_id']);//note: method variable names not table column names are used to display the table's column data in the value input fields.

?>

<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  <!--End-breadcrumbs-->

  <!--Action boxes-->
  <div class="container-fluid">

    <!--End-Action boxes-->

    <!--Chart-box-->

    <!--End-Chart-box-->
    <hr />
    <div class="span8">
<div class="widget-box">
        <div class="widget-title"> <span class="icon"> <i class="icon-user"></i> </span>
          <h5 class="" >Profile</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="edit.php" method="post" class="form-horizontal">
            <div class="control-group">
              <label class="control-label">Name :</label>
              <div class="controls">
                <input type="text" name="full_name" value="<?php echo $Login->users_full_name; ?>" >
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Phone Number :</label>
              <div class="controls">
                <input type="text" name="phone_number" value="<?php echo $Login->phone_number; ?>" >
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Number</label>
              <div class="controls">
                <input type="text"  name="account_number" value="<?php echo $Login->account_number; ?>" >
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Bank Name :</label>
              <div class="controls">
                <input type="text"  name="bank_name" value="<?php echo $Login->bank_name; ?>" >
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Name:</label>
              <div class="controls">
              <input type="text" name="account_name" value="<?php echo $Login->account_name; ?>" >
                 </div>
            </div>
			
			<div class="control-group">
              
              <div class="controls">
              <input   class="btn btn-success" type="submit" name="update" value="Update Profile" >
              
                 </div>
            </div>
           
            
          </form>
        </div>
      </div>
    </div>

    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
            <h5>NOTE: <code>class=Span12</code></h5>
          </div>
          <div class="widget-content">
            <ul class="s">
              <li>Do not click the <b>CONFIRM</b> button unless the user has paid.</li>
              <li>Click the <b>REPORT</b> button if the user is a beggar</li>
              <li>Confirm the user <b>ONLY</b> after payment to avoid suspension of your account.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">


        </div>
      </div>
    </div>
  </div>

  <?php include "footer.php"; ?>