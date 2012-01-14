<?php
class UserHelper extends CComponent {

    public function isUsernameTaken($username){
        $connection = Yii::app()->db;
        $command = $connection->createCommand("SELECT * FROM user_main where username = :username");
        $command->bindParam(":username",$username,PDO::PARAM_STR);
        return (!($command->queryRow() === false));
    }

    public function isEmailTaken($email){
        $sql = "SELECT * FROM user_main where email = :email";
        $paramArray = array(':email'=>$email);
        return (!Utilities::query($sql, $paramArray, 'row') === false);
    }

    public function hashPassword($password){
        return hash("sha256", $password);
    }

}


?>
