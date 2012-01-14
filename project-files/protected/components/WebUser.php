<?php
class WebUser extends CWebUser {
 
  // Store model to not repeat query.
  private $_model;
 
  // Return first name.
  // access it by Yii::app()->user->first_name
  function getFirst_Name(){
    $user = $this->loadUser(Yii::app()->user->id);
    return $user->first_name;
  }
 
  // This is a function that checks the field 'role'
  // in the User model to be equal to 1, that means it's admin
  // access it by Yii::app()->user->isAdmin()
  function isAdmin(){
    $user = $this->loadUser(Yii::app()->user->id);
    return intval($user->role) == 1;
  }
 
  // Load user model.
  protected function loadUser($id=null)
    {
        if($this->_model===null)
        {
            if($id!==null) {
                $this->_model=User::model()->findByPk($id);
            }
        }
        return $this->_model;
    }
    
    /**
     * Override CWebUser's changeIdentity() function.
     * Update the yii_session table with the user's id.
     */
    protected function changeIdentity($id,$name,$states)
    {
        parent::changeIdentity($id,$name, $states);

        $sessionId = Yii::app()->session->sessionId;
//            var_dump($id, $sessionId); exit;
        $sql = "UPDATE yii_session SET user_id = $id WHERE id = '$sessionId'";
//            var_dump(Utilities::query($sql, array(), 'execute')); exit;
        return Utilities::query($sql, array(), 'execute');
    }
    
    function getString() {
        return 'test';
    }
}
?>