<?php

class UserController extends Controller {

    /**
     * Register a new user.
     * @TODO Auto-log in the new user.
     */
    public function actionRegister() {
        $model = new UserRegistrationForm;

        // uncomment the following code to enable ajax-based validation
        /*
          if(isset($_POST['ajax']) && 
                  $_POST['ajax']==='member-registration-form-Register-form')
          {
          echo CActiveForm::validate($model);
          Yii::app()->end();
          }
         */

        if (isset($_POST['UserRegistrationForm'])) {
            $model->attributes = $_POST['UserRegistrationForm'];
            if ($model->validate()) {
                //Insert the member into the database
                $userId = User::registerUser($model);
                
                $user = new User($userId);
                
                //add user to topic core immediately.
                $user->addUserToSolrTopicCore();
                
                // invalidate hash invite in database
                Email::acceptEmailInvite($user, $model->h);
                
                //Assign the group in Access
                //Yii::app()->authManager->assign('Pending', $memberId);
                //Send them an email,
//                $registerLink = $this->createAbsoluteUrl(
//                        'member/authorize',
//                        array('authCode'=>$member->memberData['authcode']));
//                $emailbody = $this->renderPartial(
//                    '/email/registration', array('username'=>$model->username,
//                        'screen_name'=>$model->screenName,
//                        'registerlink'=>$registerLink), true);
                // auto-login - create LoginForm object, and use that to sign in
                $loginForm = new LoginForm();
                $loginForm->attributes = $model->attributes;
                $loginForm->login();
                $this->redirect(Yii::app()->params['defaultLoggedInPage']);
            }
        }
        
        // May want to render the standard login page, /site/index instead.
        $this->render('/site/register', array('model' => $model));
    }

    /**
     * @TODO Ensure this function is completely secure from javascript
     * modification attacks!
     * @param Array $params A new attribute and attribute value to set.
     * @return boolean The success or failure of setting the attribute.
     */
    private function setUserAttributes($params) {
        if (empty($params['attributes']))
            return json_encode(array('status' => 'failure',
                'error' => 'No attributes set.'));
        $attributeValues = $params['attributes'];
        $attributes = array_keys($params['attributes']);

        $userId = Yii::app()->user->id;
        $user = new User($userId);
        $model = new UserRegistrationForm;
        $model->attributes = $user->attributes; // temporarily set model
        foreach ($params['attributes'] as $attribute => $attributeValue) {
            $model->$attribute = $attributeValue;
            $user->$attribute = $attributeValue;
            if (property_exists('User', $attribute))
                $user->$attribute = $attributeValue;
        }
        
        $dbAttributeNames = User::getDatabaseNamesFromAttributes(
                array_keys($params['attributes']));
        $newAttributes = array();
        foreach ($dbAttributeNames as $dbAttributeName) {
            
            $newAttributes[$dbAttributeName] = array_shift($params['attributes']);
        }
        
        if ($model->validate(array($attributes))) {
            return $user->saveAttributes($newAttributes);
        } else {
            return json_encode(array('status' => 'failure'));
        }
    }

    /**
     * Change the password given a password and a passwordConfirm string.
     * @TODO Make custom password validation function!  validate()
     * doesn't seem to be doing anything.
     * @param Array $params The password strings.
     * @return Array Return true or a json_encoded failure array.
     */
    private function changePassword($params) {

        if ($params['password'] == $params['passwordConfirm']) {
            $attributes = array('password' => $params['password'],
                'passwordConfirm' => $params['passwordConfirm']);

            $userId = Yii::app()->user->id;
            $user = new User($userId);
            $model = new UserRegistrationForm;
            $model->attributes = $user->attributes; // temporarily set model

            foreach ($attributes as $attribute => $attributeValue) {
                $model->$attribute = $attributeValue;
                if (property_exists('User', $attribute))
                    $user->$attribute = $attributeValue;
            }
            if ($model->validate($attributes)) {
                // hash password after plain text is validated
                $user->password = UserHelper::hashPassword($user->password);
                if ($user->save())
                    return json_encode(array('status' => 'success'));
                else {
                    return json_encode(array('status' => 'failure',
                        'error' => 'Failed to save.'));
                }
            } else {
                return json_encode(array('status' => 'failure'));
            }
        }
        else
            return json_encode(array('status' => 'failure', 
                'error' => 'Passwords do not match.'));
    }
    
    public function actionSubmitFeedback()
    {
        if(!empty($_POST['feedback']))
        {
            $feedback = $_POST['feedback'];
            User::submitFeedback($feedback);
        }
    }

    /**
     * Set a user to being inactive.
     * @return boolean Success or failure.
     */
    private function setUserInactive() {
        $userId = Yii::app()->user->id;
        return User::setUserActive(0, $userId);
    }

    /**
     * Basic empty constructor; created in order to enable actionAjaxHandler
     */
    public function __construct() {
        
    }

    /**
     * A function to handle all incoming ajax requests. Calls private
     * functions as found in the $_POST['function'] variable, and sends
     * the $_POST['params'] to that function.  'params' stores all
     * data that needs to reach the private function.
     *
     */
    public function actionAjaxhandler() {
        $function = '';
        $params = array();
        if (isset($_POST['goToFunction'])) {
            $function = $_POST['goToFunction'];
        }
        if (isset($_POST['params'])) {
            $params = $_POST['params'];
        }

        $className = get_class();
        $controller = new $className;

        echo $controller->{$function}($params);
    }

}
?>