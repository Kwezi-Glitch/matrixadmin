<?php include "header.php"; ?>
<?php include "navigation.php"; ?>
<?php include "sidebar.php"; ?>

<div id="content">
  <!--breadcrumbs-->
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a></div>
  </div>
  <!--End-breadcrumbs-->

  <!--Action boxes--> 
  <?php $details=$Login->getPaymentDetails($_SESSION['matrix_user_id']); ?>
  <div class="container-fluid">
    <div class="quick-actions_homepage">
      <ul class="quick-actions">



        <?php 
        $pack=$Login->package;
        switch($pack){
          case 0:
            $package="You haven't enrolled for a package yet.";
            break;
            case 1:
              $package="You are currently enrolled in the UGX.10,000 package";
              break;
              case 2:
                $package="You are currently enrolled in the UGX.25,000 package";
                break;
                case 3:
                  $package="You are currently enrolled in the UGX.50,000 package";
                  break;
                  case 4:
                    $package="You are currently enrolled in the UGX.75,000 package";
                    break;
                    case 5:
                      $package="You are currently enrolled in the UGX.100,000 package";
                      break;
                      default:
                      return false;

        }
        
        ?>

<?php  if($details==1): ?>
  <li class="bg_lb span6"> <a> <i class="icon-thumbs-up"></i> <span class="label label-important"></span> Perfect </a> <h6>Your account is active and ready for merging</h6></li>
  <?php elseif($details==-1): ?>
    <li class="bg_lb span6"> <a> <i class="icon-lock"></i> <span class="label label-important"></span> Caution! </a> <h6>Your account is active but not ready for merging. Please click on the edit Profile tab to provide bank details</h6></li>
    <?php endif ?>
        <li class="bg_lo span6"> <a> <i class="icon-briefcase"></i> Package</a> <h6> <?php echo $package; ?></h6></li>
        <li class="bg_lg span6"> <a> <i class="icon-money"></i> Earning</a> <h6>You have earned a total of UGX <?php echo $Login->earnings; ?>.00 </h6> </li>
        <li class="bg_ly span6"> <a> <i class="icon-shopping-cart"></i><span class="label label-success"></span> Donation </a> <h6>You have donated a total of UGX <?php echo $Login->donations; ?>.00 </h6></li>
        


      </ul>
    </div>
    <!--End-Action boxes-->

    <!--Chart-box-->
    <div class="row-fluid">
      <div class="widget-box">
        <div class="widget-title bg_lg"><span class="icon"><i class="icon-signal"></i></span>
          <h5>Site Analytics</h5>
        </div>
        <div class="widget-content">
          <div class="row-fluid">
            <div class="span12">
              <a href="select_package.php" class="btn btn-success btn-large btn-block  ">Choose Package</a>

              <br><br>

              <button class="btn  btn-info btn-large btn-block">Packages Available</button>

              <br><br>

              <div class="span12">
                <button onClick="popup(1);" class="btn  btn-info btn-large">UGX10,000</button>
                <button onClick="popup(2);" class="btn  btn-info btn-large">UGX25,000</button>
                <button onClick="popup(3);" class="btn  btn-info btn-large">UGX50,000</button>
                <button onClick="popup(4);" class="btn  btn-info btn-large">UGX75,000</button>
                <button onClick="popup(5);" class="btn  btn-info btn-large">UGX100,000</button>
              </div>
              <!-- script to display information about a package using sweet alert.-->
          <script>
            function popup(n){//we define a function 'popup'
              var amount;
              var receive;
              var plan;

              switch(n){
                case 1:
                  amount="UGX10,000";
                  receive="UGX20,000";
                  plan="Basic";
                    break;
                    case 2:
                  amount="UGX25,000";
                  receive="UGX50,000";
                  plan="Pro-Basic";
                    break;
                    case 3:
                  amount="UGX50,000";
                  receive="UGX100,000";
                  plan="Master";
                    break;
                    case 4:
                  amount="UGX75,000";
                  receive="UGX150,000";
                  plan="Ultimate";
                    break;
                    case 5:
                  amount="UGX100,000";
                  receive="UGX200,000";
                  plan="Platinum";
                    break;
              }

              Swal.fire({
                icon: 'info',
                title: 'Matrix Admin '+plan+' package',
                text: 'You will be merged to pay '+amount+'. Upon completion of payment, You will be merged to receive '+receive
                })

            }


          </script>
            </div>
          
          </div>
        </div>
      </div>
    </div>
    <!--End-Chart-box-->
    <hr />
    <div class="row-fluid">
      <div class="span6">
        <div class="widget-box">


        </div>
      </div>
    </div>
  </div>

  <?php include "footer.php"; ?>