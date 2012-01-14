<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    private $_id;

    public function authenticate() {
        // NOTE: Could easily use custom code here.
        // search for user with given username
        // if they do not exist, return self::ERROR_USERNAME_INVALID
        // if the password is invalid, return self::ERROR_PASSWORD_INVALID
        // otherwise, set any needed information (title?, $this->_id?)
        // then return self::ERROR_NONE
        // First attempt.  NOTE: No database usage!!
        // ALSO, not testing for passwords

        /*
          $user = new User($this->username);
          if ($this->username != 'Paragon' && $this->username != 'csjohn')
          {
          $this->errorCode = self::ERROR_USERNAME_INVALID;
          }
          // find their password hash and compare it.  Return error if invalid.
          else
          {
          //$this->_id=$record->id;
          //$this->setState('title', $record->title);
          $this->errorCode = self::ERROR_NONE;
          }

          return !$this->errorCode;
         */


        $user = new User($this->username);

        
        if ($user->userId === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if ($user->password != UserHelper::hashPassword($this->password))
        {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        }
        else {
            $this->_id = $user->userId;
            
            $this->setState('userId', $user->userId);
            $this->setState('username', $user->username);
            $this->setState('postMatcherId', $user->postMatcherId);

            $this->errorCode = self::ERROR_NONE;
        }
        return!$this->errorCode;
    }

    public function getId() {
        return $this->_id;
    }
   
}
