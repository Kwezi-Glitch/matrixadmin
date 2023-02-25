<?php

class Login
{
    //database connection property
    private $db_connection = null;

    //user id
    private $user_id = null;

    //username
    private $user_name = "";

    //users email
    private $user_email = "";

    //login status
    private $user_login_status = false;

    //the valid state of the password reset link
    private $password_reset_link_valid = false;

    //state of password reset process
    private $password_reset_result = false;

    //array collection for error messages
    public $errors = array();

    //collection of neutral/success messages
    public $messages = array();

    //downliner's details
    public $downliner_details = array();

    //upliner's details
    public $upliner_details = array();

    //downliner count (for every downlinerliner, there is one upliner)
    public $downliner_count = 0;

    public $bank_name;
    public $users_full_name;
    public $account_number;
    public $account_name;
    public $phone_number;
    public $earnings;
    public $donations;
    public $package;

    public $paidCount=0;
    public $paid=array();

    public function __construct()
    {
        session_start();
        if (isset($_POST['logon'])) {
            $this->loginWithPostData($_POST['user_name'], $_POST['user_password']);
        } elseif (!empty($_SESSION['matrix_user_name']) && $_SESSION['matrix_user_logged_in'] == 1) {
            $this->loginWithSessiondata();
        }

        if (isset($_GET['logout'])) {
            $this->logOut();
            //header("location: index.php");
        } elseif (isset($_POST['update'])) {
            $this->editProfileDetails($_POST['full_name'], $_POST['phone_number'], $_POST['bank_name'], $_POST['account_name'], $_POST['account_number'], $_SESSION['matrix_user_id']);
        }elseif (isset($_POST['select_pack_btn'])) {
            $this->selectPackage($_SESSION['matrix_user_id'], $_POST['select_package']);
        }
        
    }


    private function databaseConnection()
    {
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                echo MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }


    //method to fetch users data from users table
    //this is meant to be private, we are keeping it public for testing purposes and to be accessed directly from other files or classes
    public function getUserData($user_name)
    {
        //check if database connection exists
        if ($this->databaseConnection()) {
            //query the database
            $query_user = $this->db_connection->prepare("SELECT * from users WHERE user_name= :user_name");
            $query_user->bindValue(":user_name", $user_name, PDO::PARAM_STR);
            $query_user->execute();
            return $query_user->fetchObject(); //returning this result set as an object...acccessing the cols of the db using the -> symbol. 
        }
    }
    public function getStatus($user_id)
    {
        if ($this->databaseConnection()) {
            //query to get user status
            $query_status = $this->db_connection->prepare("SELECT * from status where user_id = :user_id");

            $query_status->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $query_status->execute();

            if($query_status->rowCount()){
                //result set is fetched as an associative array,
            $qs=$query_status->fetch(PDO::FETCH_ASSOC); //associative array, and the properties are assigned accordingly or matched with columns of the database.
            $this->package = $qs['package'];
            $this->earnings = $qs['earnings'];
            $this->donations = $qs['donations'];
            $_SESSION['package']=$qs['package'];
            }else{
                return false;
            }
            
        }
    }
    private function loginWithPostData($user_name, $user_password)
    {
        if (empty($user_name)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } elseif (empty($user_password)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } else {
            //if neither the password nor username is empty,we want the user to be able to login with just username & email;
            if (!filter_var($user_name, FILTER_VALIDATE_EMAIL)) { //if this isnt an email, that is.
                $result_row = $this->getUserData(trim($user_name)); //the method will execute if the username exists.
            } elseif ($this->databaseConnection()) { //incase a user enters a valid email in the username feild...
                $query_user = $this->db_connection->prepare("SELECT *  FROM users WHERE user_email= :user_name"); ////////
                $query_user->bindValue(":user_name", trim($user_name), PDO::PARAM_STR); //binding user name values in query if email matches.
                $query_user->execute();
                $result_row = $query_user->fetchObject(); //finally result row has valid user details.
            }
            ////////////////////////////////////
            else {
                $this->errors[] = MESSAGE_LOGIN_FAILED;
            }
            /////////////////////////////////
            //check if user exists
            if (!isset($result_row->user_id)) {
                $this->errors[] = MESSAGE_LOGIN_FAILED;
                //this only occurs if wrong details were entered in the POST data thus, a user id is non existent therefore a first confirmed login failure.
            }
            //another wrong password login attempt increments login fills column. Notification at 3 failed logins within 30 seconds.
            elseif (($result_row->user_filled_logins >= 3) && ($result_row->user_last_filled_login > (time() - 30))) {
                $this->errors[] = MESSAGE_PASSWORD_WRONG_3_TIMES;
            }
            //checking user password. (second verification check). login fills column gets incremented by 1 for wrong password.
            elseif (!password_verify($user_password, $result_row->user_password_hash)) {
                $query_pass = $this->db_connection->prepare("UPDATE users set user_filled_logins = user_filled_logins + 1,user_last_filled_login= :user_last_filled_login where user_name = :user_name OR user_email = :user_name");
                $query_pass->execute(array(":user_name" => $user_name, ":user_last_filled_login" => time()));
                $this->errors[] = MESSAGE_PASSWORD_WRONG;
            }
            //check if account was activated...
            elseif ($result_row->user_active != 1) {
                $this->errors[] = MESSAGE_ACCOUNT_NOT_ACTIVATED;
            } elseif (strlen($user_password) < 5) {
                $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
            }
            //if all checks are complete, a successful login is captured.
            else {
                //writing users data into session variables.
                $_SESSION['matrix_user_id'] = $result_row->user_id;
                $_SESSION['matrix_user_name'] = $result_row->user_name;
                $_SESSION['matrix_user_email'] = $result_row->user_email;
                $_SESSION['matrix_user_logged_in'] = 1;

                //assigning the user properties values. In design of this system, we eradicate handling of passwords directly and concurrently.
                $this->user_id = $result_row->user_id;
                $this->user_name = $result_row->user_name;
                $this->user_email = $result_row->user_email;
                $this->user_login_status = true;

                //we reset the failed login counter earlier incremented, incase a user enters correct details within the specified time frame.
                $query_set = $this->db_connection->prepare("UPDATE users set user_filled_logins=0, user_last_filled_login=null where user_id = :user_id and user_filled_logins!=0 ");
                $query_set->execute(array(":user_id" => $result_row->user_id));
            }
        }
    }

    public function userIsLoggedin()
    {
        return $this->user_login_status;
    }
    //can be used when user is logged in even on page refresh
    private function loginWithSessiondata()
    {
        $this->user_name = $_SESSION['matrix_user_name'];
        $this->user_email = $_SESSION['matrix_user_email'];
        $this->user_login_status = true;
    }
    //logout method
    private function logOut()
    {
        $_SESSION = array();
        session_destroy();
        $this->user_login_status = false;
        $this->messages[] = MESSAGE_LOGGED_OUT;
    }

    public function getPaymentDetails($user_id)
    {
        if ($this->databaseConnection()) {
            $query_users = $this->db_connection->prepare("SELECT * FROM paydetails WHERE user_id = :user_id");
            $query_users->bindValue(":user_id", $user_id, PDO::PARAM_INT);
            $query_users->execute();

            if($query_users->rowCount()){
                $result_row = $query_users->fetch(PDO::FETCH_ASSOC);
                $this->bank_name = $result_row['bank_name'];
                $this->users_full_name = $result_row['full_name'];
                $this->account_name = $result_row['account_name'];
                $this->phone_number = $result_row['phone_number'];
                $this->account_number = $result_row['account_number']; 

                if (empty($this->bank_name) || empty($this->users_full_name) || empty($this->account_name) || empty($this->phone_number) || empty($this->account_number)) {
                    return -1;
                } else {
                    return 1;
                }
            }else{
                return -1;
            }
        }
    }
    public function editProfileDetails($users_full_name, $phone_number, $bank_name, $account_name, $account_number, $session_id)
    {
        //removing white spaces
        $users_full_name = trim($users_full_name);
        $phone_number = trim($phone_number);
        $bank_name = trim($bank_name);
        $account_name = trim($account_name);
        $account_number = trim($account_number);

        //checking if user profile details exist( ie from paydetails table)
        if ($this->databaseConnection()) {
            $query_set = $this->db_connection->prepare("SELECT * from paydetails where user_id=:user_id");
            $query_set->bindValue(":user_id", $session_id, PDO::PARAM_INT);
            $query_set->execute();
            $result_row = $query_set->rowCount();

            if ($result_row == 0) {
                $query_user = $this->db_connection->prepare("INSERT into paydetails (user_id,full_name,phone_number,bank_name,account_name,account_number) values (" . $session_id . ", :full_name, :phone_number, :bank_name, :account_name, :account_number)");
                $query_user->bindValue(":full_name", $users_full_name, PDO::PARAM_STR);
                $query_user->bindValue(":phone_number", $phone_number, PDO::PARAM_INT);
                $query_user->bindValue(":bank_name", $bank_name, PDO::PARAM_STR);
                $query_user->bindValue(":account_name", $account_name, PDO::PARAM_STR);
                $query_user->bindValue(":account_number", $account_number, PDO::PARAM_INT);
                $query_user->execute();

                $this->messages[] = "Payment details successfully saved.";
            } else {
                $query_user = $this->db_connection->prepare("UPDATE paydetails SET full_name=:full_name,phone_number=:phone_number,bank_name=:bank_name,account_name=:account_name,account_number=:account_number where user_id= " . $session_id . " ");
                $query_user->bindValue(":full_name", $users_full_name, PDO::PARAM_STR);
                $query_user->bindValue(":phone_number", $phone_number, PDO::PARAM_INT);
                $query_user->bindValue(":bank_name", $bank_name, PDO::PARAM_STR);
                $query_user->bindValue(":account_name", $account_name, PDO::PARAM_STR);
                $query_user->bindValue(":account_number", $account_number, PDO::PARAM_INT);
                $query_user->execute();

                $this->messages[] = "Payment details successfully updated.";
            }
        }
    }
    public function selectPackage($user_id, $select_pack){
        if($this->databaseConnection()){
            //switch statement to compare packages...
            switch($select_pack){
                case 1:
                    $selected_package="package_one";
                    break;
                    case 2:
                        $selected_package="package_two";
                        break;
                        case 3:
                            $selected_package="package_three";
                            break;
                            case 4:
                                $selected_package="package_four";
                                break;
                                case 5:
                                    $selected_package="package_five";
                                    break;
                                    default:
                                    $this->errors[]="Please select a valid package.";
                                    return false;
            }

            $check_package=$this->db_connection->prepare("select merge_status,pay_status from ".$selected_package." where user_id=:user_id ");
            $check_package->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $check_package->execute();

            $result=$check_package->fetch(PDO::FETCH_ASSOC);

            if($result['merge_status']==1 && $result['pay_status']==1){
                //the user is merged to be paid since pay and merge status are true. if either is not true
                //check the users activeness from status table
                $check_active=$this->db_connection->prepare("select active from status where user_id =:user_id");
                $check_active->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $check_active->execute();

                $result_2=$check_active->fetch(PDO::FETCH_ASSOC);

                if($result_2['active']==1){
                    //this implies user is qualified to be merged
                    $this->messages[]="You have been merged already. Please select the transaction tab for more information.";
                }elseif($result_2['active']==0){
                    

                    $pack_update=$this->db_connection->prepare("update ".$selected_package." set update_time=NOW(), merge_status= 0, pay_status=0 where user_id = :user_id");
                    $pack_update->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                    $pack_update->execute();

                    if($pack_update->rowCount()){
                        //update status table
                        $update_status=$this->db_connection->prepare("UPDATE status set package = :package, last_update = NOW(), active=1 where user_id = :user_id");
                        $update_status->bindValue(":package",$select_pack,PDO::PARAM_INT);
                        $update_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                        $update_status->execute();

                        if($update_status->rowCount()){
                            //if query is successful reassign session variable for package.
                            $_SESSION['package']=$select_pack;
                            //delete data from transaction table
                            $delete_query=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
                            $delete_query->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                            $delete_query->execute();

                            if($delete_query->rowCount()){
                                $this->messages[]="You have successfully enrolled for the currently selected package.";
                            }
                        }else{
                            echo "problems";
                        }
                    }
                }
                //this implies active==1;
            }else{
                //if user has already selected package ie active==1, for this else block to be triggered it means that either they are trying to again select a package yet they are already enrolled in another package for the precurrent or ongoing transaction. it is after a complete transaction that a user may recycle or select a package.
                //or, they arent merged yet or havent completed a transaction depending on whether they are an upliner or downliner. ie merge_status==0 and pay status==0
                $check_active=$this->db_connection->prepare("SELECT active from status where user_id=:user_id");
                $check_active->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $check_active->execute();
                //note: query result sets are fetched as associative arrays so as to explicitly select columns as indices in array format.
                $check_active_res=$check_active->fetch(PDO::FETCH_ASSOC);
                if($check_active_res['active']==1){
                    $this->errors[]="Sorry, You are already enrolled in a package. Please complete transaction cycle to then be able to recycle or select a different package.";

                    return false;
                }
                elseif($check_active_res['active']==0){
                    //here we want the user to select another package since they arent activated. Firstly, we check the current session package.
                    //remember in the registration class, after a successful registration, the status table captures package as 0, active as 0...
                    //therefore we check to see if its not zero or the user is not selected a similar package to the one captured in the status table.
                    if($_SESSION['package']!=0 && ($select_pack)!=$_SESSION['package']){
                        switch($_SESSION['package']){
                            case 1:
                                $old_package="package_one";
                                break;
                                case 2:
                                    $old_package="package_two";
                                    break;
                                    case 3:
                                        $old_package="package_three";
                                        break;
                                        case 4:
                                            $old_package="package_four";
                                            break;
                                            case 5:
                                                $old_package="package_five";
                                                break;

                                                default:
                                                $this->errors[]="Please select a valid or existing package.";
                                                return false;
                        }
                        //using the current session package, we have the old or priorly selected package and its table so we delete this entry or user from this package using the user id.
                        $delete_oldpack=$this->db_connection->prepare("DELETE from ".$old_package." where user_id = :user_id");
                        $delete_oldpack->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                        $delete_oldpack->execute();

                        if($delete_oldpack->rowCount()){
                            //we then capture the current package ands its package table to the user.
                            $package_insert=$this->db_connection->prepare("INSERT into ".$selected_package." (user_id,update_time,merge_status,pay_status) values(:user_id, NOW(), 0 , 0)");
                            $package_insert->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                            $package_insert->execute();

                            if($package_insert->rowCount()){
                                //we update the status table for this user
                                $update_status=$this->db_connection->prepare("UPDATE status set package=:package, last_update=NOW(), active=1 where user_id=:user_id");
                                $update_status->bindValue(":package",$select_pack,PDO::PARAM_INT);
                                $update_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                                $update_status->execute();

                                if($update_status->rowCount()){
                                    $_SESSION['package']=$select_pack;

                                    $delete_trans=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
                                    $delete_trans->bindValue("user_id",$user_id,PDO::PARAM_INT);
                                    $delete_trans->execute();

                                    if($delete_trans->rowCount()){
                                        $this->messages[]="You have successfully enrolled in the selected package.";
                                    }
                                }
                            }
                        }
                    }
                    else{
                        //incase a user has no earlier enrolled package or is a first timer ie successfully registered and has package as 0 in status stable...we then assign them to the currently selected package in the current session.
                        $package_insert=$this->db_connection->prepare("INSERT INTO ".$selected_package." (user_id,update_time,merge_status,pay_status) values(:user_id, NOW(), 0, 0)");
                        $package_insert->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                        $package_insert->execute();

                        //if the above query is succssful, we update the status table with package details and active too, 
                        if($package_insert->rowCount()){
                            $update_status=$this->db_connection->prepare("UPDATE status set package= :package, last_update=NOW(), active=1 where user_id=:user_id");
                            $update_status->bindValue(":package",$select_pack,PDO::PARAM_INT);
                            $update_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                            $update_status->execute();


                            if($update_status->rowCount()){
                                $_SESSION['package']=$select_pack;

                                $this->messages[]="You have successfully enrolled in the selected package.";
                            }
                        }
                        

                    }
                }
            }
        }
    }
    //the function below checks the transactions table to identify an upliner and downliners. 
    public function getMergeStatus($user_id){
        if($this->databaseConnection()){
            $merge_status=$this->db_connection->prepare("SELECT user_id,user_id_up,to_user_id,from_user_id_one,from_user_id_two from transactions where user_id_up = :user_id or to_user_id=:user_id or from_user_id_one=:user_id or from_user_id_two=:user_id ");
            $merge_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $merge_status->execute();

            //checking if a user is not yet merged;
            if($merge_status->rowCount()==0){
                if($_SESSION['package']==0){
                    return 6;
                }else{//user is enrolled in a package but hasnt been merged yet
                    switch($_SESSION['package']){
                        case 1:
                            $current_package="package_one";
                            break;
                            case 2:
                                $current_package="package_two";
                                break;
                                case 3:
                                    $current_package="package_three";
                                    break;
                                    case 4:
                                        $current_package="package_four";
                                        break;
                                        case 5:
                                            $current_package="package_five";
                                            break;
                                            default:
                                            return false;
                    }

                    $pay_status=$this->db_connection->prepare("SELECT pay_status from ".$current_package." where user_id=:user_id");
                    $pay_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                    $pay_status->execute();

                    $ps=$pay_status->fetch(PDO::FETCH_ASSOC);
                    if($ps['pay_status']==0){
                        return 0;
                    }elseif($ps['pay_status']==1){
                        return 1;
                    }
                }
            }elseif($merge_status->rowCount()==1){//implies user is already merged
                //we check to see if user is making or receiving payment
                $ms=$merge_status->fetch(PDO::FETCH_ASSOC);

                //if logged in user is downliner ie to_user_id is not null
                if($ms['to_user_id']==$_SESSION['matrix_user_id']){//ie is not null thus;
                    //flow of code is changed to downliner target point
                    goto d;
                }//for upliner, either one of the from_user_ids is not null
                elseif($ms['from_user_id_one']==$_SESSION['matrix_user_id'] || $ms['from_user_id_two']==$_SESSION['matrix_user_id']){
                    //we fetch the upliner
                    $query_up=$this->db_connection->prepare("SELECT user_id from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id");
                    $query_up->bindValue(":user_id",$_SESSION['matrix_user_id'],PDO::PARAM_INT);
                    $query_up->execute();
                    $index=1;

                    $z=$query_up->rowCount();

                    for($index=1; $index<=$z; $index++){
                        $a=$query_up->fetch(PDO::FETCH_ASSOC);

                        $upid=$a['user_id'];

                        //query to display upliner to downliner
                        $query_disp=$this->db_connection->prepare("SELECT * from paydetails where user_id=:_user_id");
                        $query_disp->bindValue(":_user_id",$upid,PDO::PARAM_INT);
                        $query_disp->execute();

                        $this->upliner_details[$index]=$query_disp->fetch(PDO::FETCH_ASSOC);//this array contains only one result therefore the loop runs once. Reason being in the design of this system there is only one upliner per downliner, but an upliner has 2 downliners thus the name a 2 by 1 matrix system. This is why in the downlinersfile.php, a for loop is used to fetch the downliners and in the upliners file, we manually input 1 as the index of the array.
                    }
                    return 2;



                }//checking to see if an upliner has a pending payment ie one of the merged users failed to pay so the purge\report button was clicked and they were added to the temp(defaulters) table, there we load the pendingpayment.php file instead.
                elseif($ms['user_id_up']==$_SESSION['matrix_user_id']){
                    $temp_status=$this->db_connection->prepare("SELECT user_id from temp where user_id=:user_id");
                    $temp_status->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                    $temp_status->execute();

                    if($temp_status->rowCount()>0){//confirms user didnt get full payment for this particular cycle
                        return 4;
                    }else{
                        //user got payment and we reset user activeness to zero, plus the details of the transactions table are cleared. the merge class and remerge class will handle this functionality in depth
                        $reset_active=$this->db_connection->prepare("UPDATE status set last_update=NOW(), active=0 where user_id=:user_id");
                        $reset_active->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                        $reset_active->execute();

                        return 5;
                    }


                }
            }elseif($merge_status->rowCount()>0){
                d:
                $query_down=$this->db_connection->prepare("SELECT user_id from transactions where to_user_id=:user_id");
                $query_down->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $query_down->execute();
                //we store the result of downliners in an array
                $index=1;

                $x=$query_down->rowCount();
                $this->downliner_count=$x;//this represents the number of downliners from the query above. It is used in the front end for loop to display the downliners in the upliners.php file

                //getting downliner user_ids
                for($index=1; $index<=$x; $index++){
                    $y=$query_down->fetch(PDO::FETCH_ASSOC);
                    $downid=$y['user_id'];//downliner user_id is captured

                    //based on the looped user_ids of each downliner, we capture their paydetails

                    $query_pay=$this->db_connection->prepare("SELECT user_id,full_name,phone_number from paydetails where user_id=:_user_id");
                    $query_pay->bindValue(":_user_id",$downid,PDO::PARAM_INT);
                    $query_pay->execute();

                    $this->downliner_details[$index]=$query_pay->fetch(PDO::FETCH_ASSOC);//here the 2D array (thus a table) captures each downliner based on their ids as gotten from the $downid variable and hence all downliner row details from $querypay are stored in the downliner_details property.

                }
                return 3;
            }
        }

    }

    //the stop time method
    public function timeStop($user_id){
        if($this->databaseConnection()){

            $stop=$this->db_connection->prepare("SELECT time_end from transactions where user_id=:user_id");
            $stop->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $stop->execute();

            $st=$stop->fetch(PDO::FETCH_ASSOC);

            return $st['time_end'];

        }else{
            return false;
        }
    }

    //method to check downliners who have paid. 
    public function uplinerPaid($user_id){
        if($this->databaseConnection()){
            $check_trans=$this->db_connection->prepare("SELECT user_id from transactions where to_user_id=:user_id and button_paid=1");
            $check_trans->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $check_trans->execute();

            if($check_trans->rowCount()>0){//this only runs if all downliners have paid
                $pay=$check_trans->rowCount();
                $this->paidCount=$pay;

                //we loop through the downliners that have paid
                for($index=1;$index<=$pay;$index++){
                    $pay_id=$check_trans->fetch(PDO::FETCH_ASSOC);
                    $pay_user=$pay_id['user_id'];

                    $pay_name=$this->db_connection->prepare("SELECT full_name from paydetails where user_id=:user_id");
                    $pay_name->bindValue(":user_id",$pay_user,PDO::PARAM_INT);
                    $pay_name->execute();
                    //we assign this name to the paid property
                    $this->paid[$index]=$pay_name->fetch(PDO::FETCH_ASSOC);


                }
                return true;
            }else{
                return false;
            }

        }

    }
}
