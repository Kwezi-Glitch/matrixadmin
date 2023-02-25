<?php

class Confirm{
    //this class generally handles the upliner's functionality for confirming or purging users
    private $db_connection=null;

    public $messages=array();

    public function __construct(){
        if(isset($_POST['user_upgrade_1']) && !isset($_POST['user_to_1'])){//we check to see which variables are set in the hidden fields. These determine which method to be triggered as follows.
            $this->confirmUser($_POST['user_upgrade_1']);
        }elseif(isset($_POST['user_upgrade_2']) && !isset($_POST['user_to_2'])){
            $this->confirmUser($_POST['user_upgrade_2']);
        }//if an upliner confirms a user, one of the above will be triggered. For purging a user, the user_to_ input field will be set. This is what we check to call the purgeUser method
        elseif(isset($_POST['user_upgrade_1']) && isset($_POST['user_to_1'])){
            $this->purgeUser($_POST['user_upgrade_1'],$_POST['package'],$_POST['user_to_1']);
        }elseif(isset($_POST['user_upgrade_2']) && isset($_POST['user_to_2'])){
            $this->purgeUser($_POST['user_upgrade_2'],$_POST['package'],$_POST['user_to_2']);
        }//the checks below are to determine if the paid or i can't pay btn has been clicked.
        //for paid;
        elseif(isset($_POST['dwn_id']) && !isset($_POST['upl_id'])){
            $this->paid($_POST['dwn_id']);
        }//for i can't pay
        elseif(isset($_POST['dwn_id']) && isset($_POST['upl_id'])){
            $this->blockUser($_POST['dwn_id'],$_POST['package'],$_POST['upl_id']);
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
    public function paid($user_id){
        if($this->databaseConnection()){
            $update_paid=$this->db_connection->prepare("UPDATE transactions set button_paid=1 where user_id=:user_id");
            $update_paid->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $update_paid->execute();

            if($update_paid->rowCount()){
                echo "ok";
            }else{
                echo "not okay";
            }


        }

    }

    public function confirmUser($user_id){
        if($this->databaseConnection()){
            $package=$this->db_connection->prepare("SELECT package from status where user_id=:user_id");
            $package->bindValue(":user_id",$_SESSION['matrix_user_id'],PDO::PARAM_INT);
            $package->execute();

            $cur_pack=$package->fetchObject();

            //capturing the package variable for downliner
            switch($cur_pack->package){
                case 1:
                    $user_package="package_one";
                    break;
                    case 2:
                        $user_package="package_two";
                        break;
                        case 3:
                            $user_package="package_three";
                            break;
                            case 4:
                                $user_package="package_four";
                                break;
                                case 5:
                                    $user_package="package_five";
                                    break;
                                    default:
                                    return false;

            }
            //query to delete downliner from transactions table
            $delete_down=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
            $delete_down->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $delete_down->execute();

            //updating the package table for the downliner if the above query was successful.
            if($delete_down->rowCount()){
                $confirm=$this->db_connection->prepare("UPDATE ".$user_package." set update_time=now(), merge_status=0,pay_status=1 WHERE user_id=:user_id");
                $confirm->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $confirm->execute();

                //we update the transactions table too
                if($confirm->rowCount()){
                    $query_trans=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id");
                    $query_trans->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                    $query_trans->execute();

                    $result=$query_trans->fetch(PDO::FETCH_ASSOC);

                    if($result['from_user_id_one']==$user_id){
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=null where user_id=:user_id_d");
                        $update_trans->bindValue(":user_id_d",$_SESSION['matrix_user_id'],PDO::PARAM_INT);
                        $update_trans->execute();

                        //update status
                        $this->updateStatus($_SESSION['matrix_user_id']);
                        $this->updateStatus($user_id);

                        $this->messages[]="Action Successful";

                    }elseif($result['from_user_id_two']==$user_id){
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=null where user_id=:user_id_d");
                        $update_trans->bindValue(":user_id_d",$_SESSION['matrix_user_id'],PDO::PARAM_INT);
                        $update_trans->execute();

                        $this->updateStatus($_SESSION['matrix_user_id']);
                        $this->updateStatus($user_id);

                        $this->messages[]="Action Successful";
                    }
                }
            }

        }


    }

    private function updateStatus($user_id){
        if($this->databaseConnection()){
            $get_stats=$this->db_connection->prepare("SELECT earnings,donations,package from status where user_id=:user_id");
            $get_stats->bindValue(":user_id",$user_id,PDO::PARAM_INT);
            $get_stats->execute();

            $gts=$get_stats->fetch(PDO::FETCH_ASSOC);

            $old_earnings=$gts['earnings'];
            $old_donations=$gts['donations'];
            $package=$gts['package'];

            switch($package){
                case 1:
                    $user_package=10000;
                    break;
                    case 2:
                        $user_package=25000;
                        break;
                        case 3:
                            $user_package=50000;
                            break;
                            case 4:
                                $user_package=75000;
                                break;
                                case 5:
                                    $user_package=100000;
                                    break;
                                    default:
                                    return false;

            }
            //take note; only the upliner can trigger the confirmUser method ie this class;
            if($_SESSION['matrix_user_id']==$user_id){
                $new_earnings=$old_earnings+$user_package;

                $upd_stats=$this->db_connection->prepare("UPDATE status set earnings=:new_earnings where user_id=:user_id");
                $upd_stats->bindValue(":new_earnings",$new_earnings,PDO::PARAM_INT);
                $upd_stats->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $upd_stats->execute();

            }else{//this will update the downliner
                $new_donations=$old_donations+$user_package;

                $dwnd_stats=$this->db_connection->prepare("UPDATE status set donations=:new_donations where user_id=:user_id");
                $dwnd_stats->bindValue(":new_donations",$new_donations,PDO::PARAM_INT);
                $dwnd_stats->bindValue(":user_id",$user_id,PDO::PARAM_INT);
                $dwnd_stats->execute();
            }
        }
    }

    public function purgeUser($user_purge,$package,$upl){
        //this method is triggered by the purge button. When a user fails to make payment within the stipulated time
        if($this->databaseConnection()){//first course of action is suspending their account.
            $suspend_user=$this->db_connection->prepare("UPDATE users set user_active=0 where user_id=:user_id");
            $suspend_user->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
            $suspend_user->execute();
            
            if($suspend_user->rowCount()){
                switch($package){
                    case 1:
                        $pack_susp="package_one";
                        break;
                        case 2:
                            $pack_susp="package_two";
                            break;
                            case 3:
                                $pack_susp="package_three";
                                break;
                                case 4:
                                    $pack_susp="package_four";
                                    break;
                                    case 5:
                                        $pack_susp="package_five";
                                        break;

                                        default:
                                        return false;


                }
                
                //we delete the user from the package they had enrolled in.
                $del_pack=$this->db_connection->prepare("DELETE from ".$pack_susp." where user_id=:user_id");
                $del_pack->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_pack->execute();

                //we also delete the user from the transactions table
                $del_trans=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
                $del_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_trans->execute();

                //we also delete the user from the status table. NOTE: status table is filled upon successful registration of a user
                $del_stat=$this->db_connection->prepare("DELETE from status where user_id=:user_id");
                $del_stat->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_stat->execute();

                //we check the package table to check if there is any subscriber who hasn't been merged yet'
                $rep_user=$this->db_connection->prepare("SELECT user_id from ".$pack_susp." where merge_status=0 and pay_status=0 order by update_time limit 0,1 ");
                $rep_user->execute();
                
                if($rep_user->rowCount()==1){//since merge_status and pay_status are equal to zero, a new downliner can be fetched and assigned to the upliner.
                    $rep_dwn=$rep_user->fetch(PDO::FETCH_ASSOC);//new dwn
                    
                    $query_trans=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id ");
                    $query_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                    $query_trans->execute();

                    $result_rep=$query_trans->fetch(PDO::FETCH_ASSOC);//this result set captures the purged user.
                    
                    if($result_rep['from_user_id_one']==$user_purge){
                        
                        //we then update the transaction table with a new downliner to replace the purged one
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=:new_user_id where user_id=:user_id");
                        $update_trans->bindValue(":new_user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                        $update_trans->bindValue(":user_id",$upl,PDO::PARAM_INT);
                        $update_trans->execute();
                        
                        //we set this new user merge_status to 1
                        if($update_trans->rowCount()){
                            $update_pack=$this->db_connection->prepare("UPDATE ".$pack_susp." set merge_status=1 where user_id=:user_id");
                            $update_pack->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $update_pack->execute();


                            //we then add this new downliner to the transactions table
                            $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                            $transac_dwn->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                            $transac_dwn->execute();
                        }
                        
                    }elseif($result_rep['from_user_id_two']==$user_purge){
                        //we then update the transaction table with a new downliner to replace the purged one
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=:new_user_id where user_id=:user_id");
                        $update_trans->bindValue(":new_user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                        $update_trans->bindValue(":user_id",$upl,PDO::PARAM_INT);
                        $update_trans->execute();
                        
                        //we set this new user merge_status to 1
                        if($update_trans->rowCount()){
                            $update_pack=$this->db_connection->prepare("UPDATE ".$pack_susp." set merge_status=1 where user_id=:user_id");
                            $update_pack->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $update_pack->execute();


                            //we then add this new downliner to the transactions table
                            $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                            $transac_dwn->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                            $transac_dwn->execute();
                        }
                    }
                }else{//this block runs incase no new user is found to replace a purged downliner
                        $query_trans=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id ");
                        $query_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                        $query_trans->execute();
        
                        $result_rep=$query_trans->fetch(PDO::FETCH_ASSOC);
                        //checking which downliner has been purged;
                        //if its dwn1, we set the from_user_id_one to null
                        if($result_rep['from_user_id_one']==$user_purge){
                            
                            $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=null where user_id=:user_id_up");
                            $update_trans->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                            $update_trans->execute();
                            
                        }//if its dwn2, we set the from_user_id_two to null
                        elseif($result_rep['from_user_id_two']==$user_purge){
                            
                            $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=null where user_id=:user_id_up");
                            $update_trans->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                            $update_trans->execute();
                        }

                        //after capturing the downliner, we insert the upliner into the temp table.
                        $upl_temp=$this->db_connection->prepare("INSERT into temp (user_id,user_package,update_time) values(:user_id_up,:user_package,now()) ");
                        $upl_temp->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                        $upl_temp->bindValue(":user_package",$package,PDO::PARAM_INT);
                        $upl_temp->execute();

                        //the above query comes in handy in the remerge class as thats where users in the temp table or users with a deficit in payment are managed
            }
        }

    }
    }

    public function blockUser($user_purge,$package,$upl){
        //this method is triggered by the purge button. When a user fails to make payment within the stipulated time
        if($this->databaseConnection()){//first course of action is suspending their account.
            $suspend_user=$this->db_connection->prepare("UPDATE users set user_active=0 where user_id=:user_id");
            $suspend_user->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
            $suspend_user->execute();
            
            if($suspend_user->rowCount()){
                switch($package){
                    case 1:
                        $pack_susp="package_one";
                        break;
                        case 2:
                            $pack_susp="package_two";
                            break;
                            case 3:
                                $pack_susp="package_three";
                                break;
                                case 4:
                                    $pack_susp="package_four";
                                    break;
                                    case 5:
                                        $pack_susp="package_five";
                                        break;

                                        default:
                                        return false;


                }
                
                //we delete the user from the package they had enrolled in.
                $del_pack=$this->db_connection->prepare("DELETE from ".$pack_susp." where user_id=:user_id");
                $del_pack->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_pack->execute();

                //we also delete the user from the transactions table
                $del_trans=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
                $del_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_trans->execute();

                //we also delete the user from the status table. NOTE: status table is filled upon successful registration of a user
                $del_stat=$this->db_connection->prepare("DELETE from status where user_id=:user_id");
                $del_stat->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                $del_stat->execute();

                //we check the status table to check if there is any subscriber who hasn't been merged yet'
                $rep_user=$this->db_connection->prepare("SELECT user_id from ".$pack_susp." where merge_status=0 and pay_status=0 order by update_time limit 0,1 ");
                $rep_user->execute();
                
                if($rep_user->rowCount()==1){//since merge_status and pay_status are equal to zero, a new downliner can be fetched and assigned to the upliner.
                    $rep_dwn=$rep_user->fetch(PDO::FETCH_ASSOC);//new dwn
                    
                    $query_trans=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id ");
                    $query_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                    $query_trans->execute();

                    $result_rep=$query_trans->fetch(PDO::FETCH_ASSOC);//this result set captures the purged user.
                    
                    if($result_rep['from_user_id_one']==$user_purge){
                        
                        //we then update the transaction table with a new downliner to replace the purged one
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=:new_user_id where user_id=:user_id");
                        $update_trans->bindValue(":new_user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                        $update_trans->bindValue(":user_id",$upl,PDO::PARAM_INT);
                        $update_trans->execute();
                        
                        //we set this new user merge_status to 1
                        if($update_trans->rowCount()){
                            $update_pack=$this->db_connection->prepare("UPDATE ".$pack_susp." set merge_status=1 where user_id=:user_id");
                            $update_pack->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $update_pack->execute();


                            //we then add this new downliner to the transactions table
                            $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                            $transac_dwn->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                            $transac_dwn->execute();
                        }
                        
                    }elseif($result_rep['from_user_id_two']==$user_purge){
                        //we then update the transaction table with a new downliner to replace the purged one
                        $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=:new_user_id where user_id=:user_id");
                        $update_trans->bindValue(":new_user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                        $update_trans->bindValue(":user_id",$upl,PDO::PARAM_INT);
                        $update_trans->execute();
                        
                        //we set this new user merge_status to 1
                        if($update_trans->rowCount()){
                            $update_pack=$this->db_connection->prepare("UPDATE ".$pack_susp." set merge_status=1 where user_id=:user_id");
                            $update_pack->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $update_pack->execute();


                            //we then add this new downliner to the transactions table
                            $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                            $transac_dwn->bindValue(":user_id",$rep_dwn['user_id'],PDO::PARAM_INT);
                            $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                            $transac_dwn->execute();
                        }
                    }
                }else{//this block runs incase no new user is found to replace a purged downliner
                        $query_trans=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where from_user_id_one=:user_id or from_user_id_two=:user_id ");
                        $query_trans->bindValue(":user_id",$user_purge,PDO::PARAM_INT);
                        $query_trans->execute();
        
                        $result_rep=$query_trans->fetch(PDO::FETCH_ASSOC);
                        //checking which downliner has been purged;
                        //if its dwn1, we set the from_user_id_one to null
                        if($result_rep['from_user_id_one']==$user_purge){
                            
                            $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=null where user_id=:user_id_up");
                            $update_trans->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                            $update_trans->execute();
                            
                        }//if its dwn2, we set the from_user_id_two to null
                        elseif($result_rep['from_user_id_two']==$user_purge){
                            
                            $update_trans=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=null where user_id=:user_id_up");
                            $update_trans->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                            $update_trans->execute();
                        }

                        //after capturing the downliner, we insert the upliner into the temp table.
                        $upl_temp=$this->db_connection->prepare("INSERT into temp (user_id,user_package,update_time) values(:user_id_up,:user_package,now()) ");
                        $upl_temp->bindValue(":user_id_up",$upl,PDO::PARAM_INT);
                        $upl_temp->bindValue(":user_package",$package,PDO::PARAM_INT);
                        $upl_temp->execute();

                        //the above query comes in handy in the remerge class as thats where users in the temp table or users with a deficit in payment are managed
            }
        }

    }
    }


}

?>