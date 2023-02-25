<?php

class Merge{

//database connection property
private $db_connection = null;

public function __construct(){
//we input the packages here and each package is checked against the $status variable until the right package is selected.
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

        switch($package){

            
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
                                return false;

        }

        //based on the package , we fetch 2 new users ready to be downliners(provide help).
        if($this->databaseConnection()){
            //we set up a flag to be used in a while loop.
            $status=true;

            while($status){
                //query to fetch 2 downliners 
                $downliners=$this->db_connection->prepare("SELECT user_id from ".$selected_package." where merge_status=0 AND pay_status=0 ORDER BY update_time ASC LIMIT 0,2");//we use a first come first serve basis thus order by update_time(time of joining package). Limit 0,2: return only 2 results beginning with 0 index result ie first result
                $downliners->execute();
                $dwn=$downliners->rowCount();

                if($dwn==2){//
                    //we store the 2 users in an array
                    for($index=1; $index<=2; $index++){
                        $user_d[$index] = $downliners->fetch(PDO::FETCH_ASSOC);

                    }

                    //we then get the upliner
                    $get=$this->db_connection->prepare("SELECT user_id from ".$selected_package." where merge_status=0 AND pay_status=1 ORDER BY update_time ASC LIMIT 0,1");//only one result is fetched.
                    $get->execute();
                    $upl=$get->rowCount();

                    if($upl==1){//we then fetch the upliner and store him in an array.
                        $upli=$get->fetch(PDO::FETCH_ASSOC);
                        $upliner=$upli['user_id'];

                        //we then individually fetch the downliners
                        $dwn_one=$user_d[1]['user_id'];
                        $dwn_two=$user_d[2]['user_id'];

                        //we insert the upliners data into the transactions table
                        $transac_up=$this->db_connection->prepare("INSERT into transactions (user_id,user_id_up,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,:user_id,now(),null,null,:done,:dtwo)");
                        $transac_up->bindValue(":user_id",$upliner,PDO::PARAM_INT);
                        $transac_up->bindValue(":done",$dwn_one,PDO::PARAM_INT);
                        $transac_up->bindValue(":dtwo",$dwn_two,PDO::PARAM_INT);
                        $transac_up->execute();


                        //we then update the package table;
                        $update_pack_u=$this->db_connection->prepare("UPDATE ".$selected_package." set merge_status=1 where user_id=:user_id");
                        $update_pack_u->bindValue(":user_id",$upliner,PDO::PARAM_INT);
                        $update_pack_u->execute();
                        //upliner details and merging complete


                        //we insert the downliners details into the transactions if the upliners merge status is updated
                        //for downliner one
                        if($update_pack_u->rowCount()){
                            
                            $transac_dwn_one=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + INTERVAL 36 HOUR),:to_user_id,null,null)" );//we set the time limit for which a transaction must be completed using the interval key word and adding the time period
                            $transac_dwn_one->bindValue(":user_id",$dwn_one,PDO::PARAM_INT);
                            $transac_dwn_one->bindValue(":to_user_id",$upliner,PDO::PARAM_INT);
                            $transac_dwn_one->execute();
                            
                            //we then update the package table
                            $update_pack_d1=$this->db_connection->prepare("UPDATE ".$selected_package." set merge_status=1 where user_id=:user_id");
                            $update_pack_d1->bindValue(":user_id",$dwn_one,PDO::PARAM_INT);
                            $update_pack_d1->execute();

                            //then for downliner two;
                            
                            $transac_dwn_two=$this->db_connection->prepare("INSERT into transactions (user_id,time_start,time_end,to_user_id,from_user_id_one,from_user_id_two) values (:user_id,now(),(now() + interval 36 HOUR),:to_user_id,null,null)" );//we set the time limit for which a transaction must be completed using the interval key word and adding the time period
                            $transac_dwn_two->bindValue(":user_id",$dwn_two,PDO::PARAM_INT);
                            $transac_dwn_two->bindValue(":to_user_id",$upliner,PDO::PARAM_INT);
                            $transac_dwn_two->execute();
                            
                            //we then update the package table
                            $update_pack_d2=$this->db_connection->prepare("UPDATE ".$selected_package." set merge_status=1 where user_id=:user_id");
                            $update_pack_d2->bindValue(":user_id",$dwn_two,PDO::PARAM_INT);
                            $update_pack_d2->execute();

                        }else{
                            //we delete the upliner from the transactions table if the $update_pack_u query didnt affect any row ie merge_status wasnt set to one because it will be assumed that a merging process will have taken place (getmergestatus() will return 3 which isn't the case)
                            $delete_trans=$this->db_connection->prepare("DELETE from transactions where user_id=:user_id");
                            $delete_trans->bindValue(":user_id",$upliner,PDO::PARAM_INT);
                            $delete_trans->execute();

                            $status=false;
                        }
                    }else{
                        $status=false;
                    }



                }else{
                    $status=false;
                }
            }

        }





    }





}

?>