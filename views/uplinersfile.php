
<?php
switch($_SESSION['package']){//in production update; an upliners and downliners table needs to be created based on the merge class.
  case 1:
    $amount="10,000";
    break;

    case 2:
      $amount="25,000";
      break;

      case 3:
        $amount="50,000";
        break;

        case 4:
          $amount="75,000";
          break;

          case 5:
            $amount="100,000";
            break;
    
  }

?>

<div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-th"></i> </span>
            <h5> <?php echo $_SESSION['matrix_user_name'];?>,These are your current downliners</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone Number</th>
                  <th>Amount</th>
                  <th>Action</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>

              <?php
              //to display the username and phone number of the downliners, we use a for loop and loop through the downliner_details[] array.
              for($index=1; $index<=$Login->downliner_count; $index++): 
              ?>
              <?php 
              //variables to check if a user is past the time_end
              $start = new DateTime($Login->timeStop($Login->downliner_details[$index]['user_id']));
              $date = new DateTime();
              ?>
                <tr class="odd gradeX">
				
				
				<form method="post" action="transaction.php"><!--the input field below is triggered by the confirm button;(during buildup: take note that the report button too can trigger the confirm class, we make it unclickable ) -->
                <input type="hidden" name="user_upgrade_<?php echo $index; ?>" value="<?php echo $Login->downliner_details[$index]['user_id'] ?>">
                  <td><?php echo $Login->downliner_details[$index]['full_name'] ?></td>
                  <td><?php echo $Login->downliner_details[$index]['phone_number'] ?></td>
                  <td>Shs.<?php echo $amount ?> </td>
                  <td class="center"><button class="btn btn-success btn-block" type="submit">Confirm</button></td>
                  <!--Purge and Report button in next form tag-->
				  
                  
				  
				  </form>
          <!--a second form tag is necessary for the purge functionality ie; in the buildup, it was left in the first form tag but there it wouldnt work because of the .-->
          <form method="post" action="transaction.php">
            <input type="hidden" name="user_upgrade_<?php echo $index; ?>" value="<?php echo $Login->downliner_details[$index]['user_id'] ?>">
            <input type="hidden" name="user_to_<?php echo $index; ?>" value="<?php echo $_SESSION['matrix_user_id'] //NOTE: this is the upliners file therefore this will always return the upliner user_id ?>">
            <input type="hidden" name="package" value="<?php echo $_SESSION['package'] ?>">
              <!--the input fields above -->
            <td class="center">
            <?php if($start >= $date) : ?>
         <button class="btn btn-danger btn-block" >Report</button>
          <?php else : ?>
        <!--this is shown to the user if the time alloted for a cycle is complete-->
            <button class="btn btn-danger btn-block">Purge</button>
            <?php endif ; ?>
          </td>

            </form>
                </tr>
                <?php endfor ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="widget-box">
          
         
        </div>
       
       
       
      </div>
    </div>