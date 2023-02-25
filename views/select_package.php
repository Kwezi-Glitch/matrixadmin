<?php include 'header.php'; ?>
<?php include 'navigation.php'; ?>
<?php include 'sidebar.php'; ?>


<!--main-container-part-->
<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  <!--End-breadcrumbs-->

  <div id="content-header">
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> <a href="#" class="current">Grid Layout</a> </div>
  </div>
  <div class="container-fluid">
    <hr>
    <!-- dynamic content logic-->
    <?php 
    $del=$Login->getPaymentDetails($_SESSION['matrix_user_id']);
    ?>
    
    <?php
    //dynamic content logic if{_} begins here
    if($del==1):
    ?>
    <!--this div is only displayed for a user with correct payment and filled in credentials(bank details and all)-->
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-money">Packages</i></span>
        </div>
        <div class="widget-content">
          <div class="row-fluid">

            <div class="span12">
              <button class="btn btn-info btn-large btn-block disabled">Packages</button>
              <br><br><br><br>
              <div class="control-group">
                <div class="controls">
                  <form action="" method="post" id="pack_select">
                    <select name="select_package" class="">
                      <option>Select Package</option>
                      <option value="1">Shs.10,000</option>
                      <option value="2">Shs.25,000</option>
                      <option value="3">Shs.50,000</option>
                      <option value="4">Shs.75,000</option>
                      <option value="5">Shs.100,000</option>
                    </select>
                </div>
              </div>

              <input type="submit" name="select_pack_btn" class="btn btn-success btn-large btn-block " value="Select Package">

              </form>



              <br><br>


            </div>



          </div>
        </div>
      </div>
    </div>
      <?php
      //completing else statement for first if. This block is displayed for a user without full credentials(bank details)
      else : 
      ?>


    <div class="row-fluid Alert Alert-success">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
            <h5>Notice</h5>
          </div>
          <div class="widget-content">
            <ul>



              <div class="alert alert-danger alert-block"> <a class="close" data-dismiss="alert" href="#">�</a>
                <h4 class="alert-heading">Warning!</h4>
                Please complete your profile inorder to select a package
              </div>

              <div class="alert alert-danger alert-block"> <a class="close" data-dismiss="alert" href="#">�</a>
                <h4 class="alert-heading">Warning!</h4>
                You have 2 hours to complete your profile or else your account will be deleted
              </div>

              <li>Build Your Referral Wallet</li>
              <li>Visit Our Social Media Page</li>


            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php
    //end if statement to close if{_}
    endif
    ?>

    <!--dynamic content logic ends here--->


    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
            <h5>Note</code></h5>
          </div>
          <div class="widget-content">
            <ul>
              <li>Do not click the confirm button unless the user has paid</li>
              <li>click the report button if the user is a beggar</li>
              <li>confirm user after payment to avoid suspension of your account</li>

            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>