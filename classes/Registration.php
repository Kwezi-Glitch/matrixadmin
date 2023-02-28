<!--During development, all errors or caught exceptions are displayed by use or modification of the $errors[] pdo. After buildup, we will use sweet alert js for better error message display.-->


<?php

class Registration
{
    //database connection object
    private $db_connection = null;

    //success state of the registration process
    public $registration_successful = false;

    //success state of the verification process
    public $verification_successful = false;

    //array collection of error messages
    public $errors = array();

    //array collection of success/neutral messages
    public $messages = array();

    public function __construct()
    {
        session_start();
        if (isset($_POST['submit'])){
            $this->registerNewUser($_POST['user_name'], $_POST['user_email'], $_POST['user_password'], $_POST['user_password_repeat']);
        }
    }

    //method to make connection to database.
    private function databaseConnection()
    {
        if ($this->db_connection != null) {
            return true;
        } else {
            try {
                $this->db_connection = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
                return true;
            } catch (PDOException $e) {
                // $e->getMessage(); is a complex error message working with codes or numbers for errors.
                //we use the $errors array for a more customized message, more accurate as per the en.php file
                $this->errors[] = MESSAGE_DATABASE_ERROR;
                echo MESSAGE_DATABASE_ERROR;
                return false;
            }
        }
    }
    //method to register new user 
    private function registerNewUser($user_name, $user_email, $user_password, $user_password_repeat)
    {
        //removing white spaces
        $user_name = trim($user_name);
        $user_email = trim($user_email);
        //casting values to lower case;
        $user_name = strtolower($user_name);
        $user_email = strtolower($user_email);

        //validation procedures;
        //1.username and email.
        //checking to see if username or email is empty
        if (empty($user_name)) {
            $this->errors[] = MESSAGE_USERNAME_EMPTY;
        } elseif (empty($user_email)) {
            $this->errors[] = MESSAGE_EMAIL_EMPTY;
        } elseif (empty($user_password) || empty($user_password_repeat)) {
            $this->errors[] = MESSAGE_PASSWORD_EMPTY;
        } elseif ($user_password !== $user_password_repeat) {
            $this->errors[] = MESSAGE_CONFIRM_PASSWORD;
        } elseif (strlen($user_password) < 4) {
            $this->errors[] = MESSAGE_PASSWORD_TOO_SHORT;
        } elseif (strlen($user_name) > 30 || strlen($user_name) < 2) {
            $this->errors[] = MESSAGE_USERNAME_BAD_LENGTH;
        } elseif (!preg_match('/^[a-z\d]{2,30}$/i', $user_name)) {
            $this->errors[] = MESSAGE_USERNAME_INVALID;
        } elseif (strlen($user_email) > 64) {
            $this->errors[] = MESSAGE_EMAIL_TOO_LONG;
        }
        //checking for correct email format.
        elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = MESSAGE_EMAIL_INVALID;
        }
        //if all checks are true or correct 
        //we check if user exists
        elseif ($this->databaseConnection()) {
            $query_check_username = $this->db_connection->prepare("SELECT * from users WHERE user_name= :user_name OR user_email= :user_email");
            $query_check_username->bindValue(":user_name", $user_name, PDO::PARAM_STR);
            $query_check_username->bindValue(":user_email", $user_email, PDO::PARAM_STR);
            $query_check_username->execute();
            $result = $query_check_username->fetchAll();

            if (count($result) > 0) {
                for ($i = 0; $i < count($result); $i++) {
                    // $this->errors[] = ($result[$i]['user_name'] == $user_name) ? MESSAGE_USERNAME_EXISTS : MESSAGE_EMAIL_EXISTS;
                    if($result[$i]['user_name'] == $user_name){
                        $this->errors[]="Username already exists";
                    }elseif($result[$i]['user_email'] == $user_email){
                        $this->errors[]="Sorry, Entered email exists.";
                    }
                }
            } 
            else {
                //password hashing. we use the big crypt hashing algortihm to hash whatever forced parameter we have in the password hash method.
                $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
                //user's activation hash is part of the link we send to the user to verify their email address on sign up.
                $user_activation_hash = sha1(uniqid(mt_rand(), true)); //uniqid(), sha1() and mtrand() simply return a random character string representing the parameter passed to it. Using them both creates a far more unique hash key.

                //below is the query to finally insert the data into the users table
                $query_new_user_insert = $this->db_connection->prepare("INSERT INTO users (user_name,user_password_hash, 	user_email,user_activation_hash,user_registration_date,	user_registration_ip) values(:user_name,:user_password_hash,:user_email,:user_activation_hash,NOW(),:user_registration_ip)");

                $query_new_user_insert->bindValue(":user_name", $user_name, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(":user_password_hash", $user_password_hash, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(":user_email", $user_email, PDO::PARAM_STR);
                $query_new_user_insert->bindValue(":user_activation_hash", $user_activation_hash, PDO::PARAM_STR);
                //for registration ip, we use a global variable called $_server and REMOTE_ADDR which is a key returns an ip address of the location from where the user is registering from.
                $query_new_user_insert->bindValue(":user_registration_ip", $_SERVER['REMOTE_ADDR'], PDO::PARAM_STR);
                $query_new_user_insert->execute();

                //NB: remember for the status table, we didnt auto increment the user id or make it a primary key, we are to use a script. First we get the last inserted user id from the users table(normalisation as user_id in status table is a foreign key and primary key for the users table.)
                $user_id = $this->db_connection->lastInsertId(); //lastInsertId() is a method returns the id of the last user in the table of reference(users).

                //to fill the users table, we use the query below.
                $query_new_user_status = $this->db_connection->prepare("INSERT INTO status (user_id,last_update,active) values(:user_id,NOW(),0)"); // the NOW() returns the current date and time.
                $query_new_user_status->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                $query_new_user_status->execute();

                if ($query_new_user_insert) {
                    $this->registration_successful = true;
                    $this->messages[] = MESSAGE_REGISTRATION_ACTIVATION_SUCCESSFUL;
                    // $this->messages[]=2;
                } else {
                    $this->errors[] = MESSAGE_REGISTRATION_FAILED;
                }
            }
        }
    }

    public function sendVerificationEmail($user_id,$user_email,$user_activation_hash){
        $mail=new PHPMailer\PHPMailer\PHPMailer();

        $mail->IsMail();

        $mail->From=EMAIL_VERIFICATION_FROM;
        $mail->FromName=EMAIL_VERIFICATION_FROM_NAME;
        $mail->Subject=EMAIL_VERIFICATION_SUBJECT;
        $mail->AddAddress($user_email);
        // $linkEMAIL_VERIFICATION_URL .'?id='.$user_id,'&verification_code='.$user_activation_hash;

        // $mail->Body=EMAIL_VERIFICATION_CONTENT .' '$link;

        if(!$mail->Send()){
            $this->errors[]=MESSAGE_VERIFICATION_EMAIL_NOT_SENT;
            return false;
        }else{
            return true;
        }

    }

    public function verifyNewUser($user_id,$user_activation_hash){
        if($this->databaseConnection()){
            $update_users=$this->db_connection->prepare("UPDATE users set user_active=1,user_activation_hash=NULL where user_id=:user_id and user_activation_hash=:user_activation");
            $update_users->bindValue(":user_id",intval(trim($user_id)),PDO::PARAM_INT);
            $update_users->bindValue(":user_activation",$user_activation_hash,PDO::PARAM_STR);
            $update_users->execute();

            if($update_users->rowCount()>0){
                $this->verification_successful=true;
                $this->messages[]=MESSAGES_REGISTRATION_ACTIVATION_SUCCESSFUL;

            }else{
                $this->messages[]=MESSAGES_REGISTRATION_ACTIVATION_NOT_SUCCESSFUL;

            }
        }
    }
}
