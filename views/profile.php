<?php include "header.php"; ?>
<?php include "navigation.php"; ?>
<?php include "sidebar.php"; ?>

<?php
$Login->getPaymentDetails($_SESSION['matrix_user_id']);
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
          <h5 class="">Profile</h5>
        </div>
        <div class="widget-content nopadding">
          <form action="#" method="get" class="form-horizontal">
            <div class="control-group">
              <label class="control-label">Full Name :</label>
              <div class="controls">
                <p class=" form-control-static"> <?php echo $Login->users_full_name; ?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Phone Number :</label>
              <div class="controls">
                <p class=" form-control-static"> <?php echo $Login->phone_number; ?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Number :</label>
              <div class="controls">
                <p class=" form-control-static"> <?php echo $Login->account_number; ?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Bank Name :</label>
              <div class="controls">
                <p class=" form-control-static"> <?php echo $Login->bank_name; ?></p>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Account Name:</label>
              <div class="controls">
                <p class=" form-control-static"> <?php echo $Login->account_name; ?></p>
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