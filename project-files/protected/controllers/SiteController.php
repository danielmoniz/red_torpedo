<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
    
        public function actionLanding() {
            $this->layout = "/";
            $this->render('/site/landing');
        }

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex($hash = '')
	{
        $registrationForm = self::registerUserIfRequired();
        
        if (!Yii::app()->user->isGuest)
            $this->redirect ($this->createUrl('/feed/home'));
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
        $loginForm = new LoginForm;
        //$loginWidget = $this->widget('application.widgets.LoginWidget', array('model'=>$loginForm));
        $loginBox = $this->renderPartial('/site/login', 
                array('model'=>$loginForm), true);

        // if $hash is non-empty, find the relevant email associated with it
        $email = '';
        if (!empty($hash)) {
            $registrationForm->h = $hash; // set hash attribute if hash exists
            // note: email can be false. This will be echoed as '' (blank).
            $registrationForm->email = Email::getEmailFromInviteHash($hash);
        }
        $signupBox = $this->renderPartial('/site/register', 
                array('model'=>$registrationForm), true);

		$this->render('/pages/landing', array('loginBox'=>$loginBox, 'signupBox'=>$signupBox));
	}
    
    /**
     * This function registers a user if the relevant form has been 
     * submitted via POST. If it has but it does not validate, 
     * it returns the UserRegistrationForm with errors. If it has 
     * and it contains no errors, it redirects to the appropriate 
     * page.
     * @return UserRegistrationForm Return the form that failed to validate. 
     * This means it should contain errors. OR, return nothing and redirect 
     * to the default logged-in page.
     */
    private function registerUserIfRequired() {
        $model = new UserRegistrationForm;
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
        
        return $model;
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
        // @TODO Check if this is doing more than validating an empty LoginForm
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
            // set 'active' attribute in model
            $user = new User($model->username);
            $model->active = $user->active;

			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
            {
                $user->postLogin();
                if (Yii::app()->user->returnUrl == '/index.php')
                {
                    $this->redirect($this->createUrl('/feed/home'));
                }
                else
                {
                    $this->redirect(Yii::app()->user->returnUrl);
                }
            }
		}
		// display the login form
        //echo "failed to log in!"; exit;
		$this->render('login', array('model'=>$model));
	}

/*
    public function actionAjaxLogin()
    {
        $model=new LoginForm;

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
//            var_dump("test", $model->attributes); exit;
			// validate user input and redirect to the previous page if valid
            if($model->validate())
            {
                echo "validated";
            }
			if($model->validate() && $model->login())
            {
//                echo "test"; exit;
				$this->redirect(Yii::app()->user->returnUrl);
            }
		}
    }
*/
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}