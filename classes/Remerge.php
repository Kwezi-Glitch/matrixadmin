<?php
class Remerge{
    private $db_connection=null;

    public function __construct(){
    $this->pairUser(1);
    $this->pairUser(2);
    $this->pairUser(3);
    $this->pairUser(4);
    $this->pairUser(5);
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


    public function pairUser($package){
        //this method is user to handle remerging for a user with a deficit in payment
        if($this->databaseConnection()){
            $rmg=$this->db_connection->prepare("SELECT * from temp where user_package=:user_package");
            $rmg->bindValue(":user_package",$package,PDO::PARAM_INT);
            $rmg->execute();


            if($rmg->rowCount()){

                switch($package){
                    case 1:
                        $user_pack="package_one";
                        break;
                        case 2:
                            $user_pack="package_two";
                            break;
                            case 3:
                                $user_pack="package_three";
                                break;
                                case 4:
                                    $user_pack="package_four";
                                    break;
                                    case 5:
                                        $user_pack="package_five";
                                        break;
                                        default:
                                        return false;
                }
                //if there exists a user in the temp table, this will initiate this while loop
                while($user=$rmg->fetch(PDO::FETCH_ASSOC)){
                    $new_dwn=$this->db_connection->prepare("SELECT user_id from ".$user_pack." where merge_status=0 and pay_status=0 ORDER BY update_time LIMIT 0,1");
                    $new_dwn->execute();
                    //incase an un merged user exists...
                    if($new_dwn->rowCount()){
                        //capturing the new downliner 
                        $new_d=$new_dwn->fetch(PDO::FETCH_ASSOC);

                        //capturing the upliner user_id with a deficit in payment
                        $upl=$user['user_id'];
                        //capturing the downliner user_id 
                        $dwn_n=$new_d['user_id'];
                        //update time for upliner with a deficit in payment
                        $upl_time=$user['update_time'];

                        //we then update the merge status of this new downliner
                        $update_dwn=$this->db_connection->prepare("UPDATE ".$user_pack." set merge_status=1 where user_id=:user_id");
                        $update_dwn->bindValue(":user_id",$dwn_n,PDO::PARAM_INT);
                        $update_dwn->execute();

                        if($update_dwn->rowCount()){
                            //we then select the empty cols(from_user1 and from_user2) from the transactions table that were both or either one was set to null after executing the purgeUser() from the confirm class.
                            $upl_def=$this->db_connection->prepare("SELECT from_user_id_one,from_user_id_two from transactions where user_id=:user_id");
                            $upl_def->bindValue(":user_id",$upl,PDO::PARAM_INT);
                            $upl_def->execute();

                            if($upl_def->rowCount()){
                                $dwns=$upl_def->fetch(PDO::FETCH_ASSOC);

                                //we then fill the empty(NULL) downliner cols with the newly captured downliner
                                if($dwns['from_user_id_one']==NULL){
                                    $update_old_dwn=$this->db_connection->prepare("UPDATE transactions set from_user_id_one=:user_id_dwn where user_id_up=:user_id");
                                    $update_old_dwn->bindValue(":user_id_dwn",$dwn_n,PDO::PARAM_INT);
                                    $update_old_dwn->bindValue(":user_id",$upl,PDO::PARAM_INT);
                                    $update_old_dwn->execute();

                                    //we then insert this new downliner into the transactions table
                                    $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                                    $transac_dwn->bindValue(":user_id",$dwn_n,PDO::PARAM_INT);
                                    $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                                    $transac_dwn->execute();

                                    echo "done did";

                                    //we then delete the upliner from the temp table
                                    $del_dwn=$this->db_connection->prepare("DELETE from temp where user_id=:user_id and update_time=:upl_time");
                                    $del_dwn->bindValue(":user_id",$upl,PDO::PARAM_INT);
                                    $del_dwn->bindValue(":upl_time",$upl_time,PDO::PARAM_STR);//we use PDO::PARAM_STR because time is captured as a string in SQL
                                    //take note: the upl_time variable comes in handy here because it is the most unique variable that can determine which payment wasnt received in a given cycle.
                                    $del_dwn->execute();
                                    echo "done did";
                                }elseif($dwns['from_user_id_two']==NULL){
                                    $update_old_dwn=$this->db_connection->prepare("UPDATE transactions set from_user_id_two=:user_id_dwn where user_id_up=:user_id");
                                    $update_old_dwn->bindValue(":user_id_dwn",$dwn_n,PDO::PARAM_INT);
                                    $update_old_dwn->bindValue(":user_id",$upl,PDO::PARAM_INT);
                                    $update_old_dwn->execute();

                                    $transac_dwn=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );
                                    $transac_dwn->bindValue(":user_id",$dwn_n,PDO::PARAM_INT);
                                    $transac_dwn->bindValue(":to_user_id",$upl,PDO::PARAM_INT);
                                    $transac_dwn->execute();

                                    $del_dwn=$this->db_connection->prepare("DELETE from temp where user_id=:user_id and update_time=:upl_time");
                                    $del_dwn->bindValue(":user_id",$upl,PDO::PARAM_INT);
                                    $del_dwn->bindValue(":upl_time",$upl_time,PDO::PARAM_STR);
                                    $del_dwn->execute();
                            }
                        }
                    }
                }

            }
        }

    }


  }
}


?>