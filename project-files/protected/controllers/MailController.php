<?php

class MailController extends Controller
{
    /**
     * Used for inviting a single person via email to sign up.
     * @param type $params
     * @return type 
     */
    public function invitePersonToSignup($params = array()) {
        if (empty($params) || empty($params['email'])) {
            return Utilities::getFullSuccessReturn(false, 
                "No email selected.");
        }
        
        // @TODO Perform email validation on the email
            
        $currentUser = new User(Yii::app()->user->id);
        
        // check if current user has any email invites left
        if ($currentUser->emailInvites <= 0) {
            return Utilities::getFullSuccessReturn(false, 
                "No invites remaining! If you want more, just ask. :)");
        }
        
        list($inviteHash, $inviteUrl) = self::getUrlWithInviteHash();
        
        $inviteEmail = Email::buildInviteEmail($inviteUrl, $params['email']);
        
        // should be in a separate function ----------------
        // temporarily (randomly) alternate between John and Dan for invites
        //$fromArray = array("john", "dan");
        $fromArray = array("inviter");
        $username = $fromArray[array_rand($fromArray)];
        // -------------------------------------------------
        
        $emailSuccess = EmailHelper::sendEmail($username, $inviteEmail);
        
        if ($emailSuccess) {
            // enter email invite hash into DB
            $insertSuccess = Email::addNewInvite($currentUser, $inviteHash, 
                    $params['email']);
            return Utilities::getFullSuccessReturn($insertSuccess, 
                    "Failed to insert invite into database.", "Invitation sent!");
        }
        
        // if email fails
        return Utilities::getFullSuccessReturn($emailSuccess, 
                "Email failed to send.", "Invitation sent!");
    }
    
    /**
     * A simple (crappy) function which calls invitePersonToSignup() in a loop.
     * @param type $params 
     */
    public function massInvitePeopleToSignup($params = array()) {
        $emails = array();
        if (!empty($params['emails']))
            $emails = $params['emails'];
        $emails = explode(",", $emails);
        
        $currentUser = new User(Yii::app()->user->id);
        
        // Create an email object in advance and edit only necessary information
        $inviteEmail = Email::buildInviteEmail();
        
        // should be in a separate function ----------------
        // temporarily (randomly) alternate between John and Dan for invites
        //$fromArray = array("john", "dan");
        $fromArray = array("inviter");
        $username = $fromArray[array_rand($fromArray)];
        // -------------------------------------------------
        $emailSuccess = array();
        foreach ($emails as $key=>$email) {
            $email = trim($email);
            list($inviteHash, $inviteUrl) = self::getUrlWithInviteHash();
            $inviteEmail->to = $email;
            $inviteEmail->body = $this->renderPartial(
                    '/email/welcome', 
                    array('url'=>$inviteUrl), true);
            $emailSuccess[$key] = EmailHelper::sendEmail($username, $inviteEmail);
            if ($emailSuccess[$key]) {
                $emailSuccess[$key] = Email::addNewInvite($currentUser, 
                        $inviteHash, $email);
            }
        }
        $this->redirect('/devTools/massEmailInviter');
    }
    
    private function getUrlWithInviteHash() {
        $ultraRandomNumber = rand() . time() . rand();
        $inviteHash = substr(hash("sha1", $ultraRandomNumber), 0, 16);
        $inviteUrl = Yii::app()->params['basePath'] . $inviteHash;
        
        return array($inviteHash, $inviteUrl);
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

    // PERMISSIONS CODE---------------------------------------
    /**
     * Returns a list of access control functions (??) or something.
     * @return array An array containing a list of access control functions (??)
     */
    public function filters() {
        return array('accessControl');
    }

    /**
     * Returns an array of arrays that contains controller-wide access controls.
     * @return Array An array of arrays containing permissions.
     */
    public function accessRules() {
        return array(
            array('allow',
                'roles'=>array('Admin'),
            ),
            array(
                'deny',
            ),
        );
    }
    // --------------------------------------------------------
}

?>