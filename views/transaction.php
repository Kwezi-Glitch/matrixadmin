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
    <div class="container-fluid">
      
  <!--End-Action boxes-->    

  <!--Chart-box-->    
      
  <!--End-Chart-box--> 
      <hr/>
    <!--we implement the getMergeStatus() from here. Depending on the results from the $paystatus query, we get some dynamic content-->

      <?php 
    $c=$Login->getMergeStatus($_SESSION['matrix_user_id']);
  ?>
  <?php
    switch($c){
      case 0:
        include "not_merged.php";
        break;
        case 1:
          include "waiting_to_be_paid.php";
          break;

          case 2:
            include "downlinersfile.php";
            break;

            case 3:
              include "uplinersfile.php";
              break;

              case 4:
                include "pendingpayment.php";
                break;

                case 5:
                  include "upgrade.php";
                  break;

                  case 6:
                    include "uptin.php";
                    break;
    }
  ?>

  <?php $p=$Login->uplinerPaid($_SESSION['matrix_user_id']);  ?> 

  <?php if($p==true ): ?>

    <?php for($index=1; $index<=$Login->paidCount; $index++): ?>
  <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
              <h5>Notification</h5>
            </div>
            <div class="widget-content"> 
  <ul>


  <div class="alert alert-info alert-block">

  <a class="close" data-dismiss="alert" href="#">X</a>

  <h4 class="alert-heading"> Notification</h4>
  <?php echo $Login->paid[$index]['full_name']. " claims to have made payment. Please ensure this is true before confirming this user."; ?>
  </div>

  </ul>



        </div>
          </div>
        </div>
      </div>
  <?php endfor ?>
  <?php endif ?>



      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
              <h5>NOTE:</h5>
            </div>
            <div class="widget-content">
              <ul class="s">
                <li>Do not click the <b>CONFIRM</b> button unless the user has made payment.</li>
                <li>Click the <b>REPORT</b> button if the user is a beggar ie failed or refused to make payment</li>
                <li>Confirm the user <b>ONLY</b> after payment to avoid suspension of your account.</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      
  </div>

  <?php include "footer.php"; ?>