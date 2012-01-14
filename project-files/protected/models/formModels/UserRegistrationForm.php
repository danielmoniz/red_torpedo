<?php

/**
 * UserRegistrationForm class.
 * UserRegistrationForm is the data structure for keeping
 * signup form data.
 */
class UserRegistrationForm extends CFormModel {

    public $username;
    public $password;
    public $passwordConfirm;
    public $email;
    public $loginCode;
    public $h = 0; // the email invite hash
    // other attributes
    public $firstName;
    public $lastName;

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            // uswename, password, passwordConfirm, and email are required
            array('username, password, passwordConfirm, email', 'required'),
            array('email', 'email'),
            array('email', 'duplicateEmail'),
            array('password', 'compare', 'compareAttribute' => 'passwordConfirm'),
            array('password', 'length', 'min' => 8, 'max' => 64),
            array('password', 'checkPassword'),
            array('username', 'length', 'min' => 3, 'max' => 32),
            array('username', 'limitUsernameCharacters'),
            array('username', 'duplicateUsername'),
            array('loginCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
            array('website', 'url'),
            array('h', 'validInviteHash'),
        );
    }

    /**
     * Sets the attributes for the User model.
     * @param Array $attributes An array containing a key for every attribute,
     * even if their values are null.
     */
    public function setAttributes($attributes) {
        foreach ($attributes as $attribute => $value) {
            if (property_exists('UserRegistrationForm', $attribute))
                $this->$attribute = $value;
        }
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels() {
        return array(
            'username' => 'Username',
            'password' => 'Password',
            'passwordConfirm' => 'Confirm Password',
            'email' => 'Email Address',
            'loginCode' => 'Verification Code',
            'email' => 'Email',
            'website' => 'Website',
            'h' => 'Email Invite Hash',
        );
    }
    
    /**
     * Returns an error if the input is a username has disallowed characters.
     * @param $attribute The desired attribute to search by.
     * @param $params Not used!
     */
    public function limitUsernameCharacters($attribute, $params) {
        // full regular expression
        if (!preg_match("/^[a-zA-Z]+[a-zA-Z0-9_-]*$/", $this->$attribute)) {
            if (preg_match("/^[0-9_-]+/", $this->$attribute)) {
                $this->addError('username', 
                        'Username must start with a letter.');
            }
            if (!preg_match("/^[a-zA-Z0-9_-]+$/", $this->$attribute)) {
                $this->addError('username', 
                        'Usernames must contain only numbers, letters, 
                            underscores and hyphons (alphanumeric with _ and -).');
            }
        }
    }
    
    /**
     * Returns an error if the input is a password has does not meet 
     * the criteria.
     * @param $attribute The desired attribute to search by.
     * @param $params Not used!
     */
    public function checkPassword($attribute, $params) {
        if (!preg_match("/[a-zA-Z]+/", $this->$attribute)) {
            $this->addError('password', 
                    'Password must include a letter.');
        }
        if (!preg_match("/[0-9]+/", $this->$attribute)) {
            $this->addError('password', 
                    'Password must include a number.');
        }
    }

    /**
     * Returns an error if the given username is already taken.
     * @param $attribute The desired attribute to search by.
     * @param $params Not used!
     */
    public function duplicateUsername($attribute, $params) {
        if (UserHelper::isUsernameTaken($this->$attribute))
            $this->addError('username', 'Username is already taken.');
    }

    /**
     * Returns an error if the given email is already taken.
     * @param $attribute The desired attribute to search by.
     * @param $params Not used!
     */
    public function duplicateEmail($attribute, $params) {
        if (UserHelper::isEmailTaken($this->$attribute))
            $this->addError('email', 'Email is already taken.');
    }

    /**
     * Returns an error if the given email is already taken.
     * @param $attribute The desired attribute to search by.
     * @param $params Not used!
     */
    public function validInviteHash($attribute, $params) {
        if (!Email::isInviteHashValid($this->$attribute))
            $this->addError('h', 'Must have a valid invite.');
    }

}

?>