<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.


// set $protocol to either HTTP or HTTPS, depending on what is being used
$protocol = "http://";
if (!empty($_SERVER['HTTPS']))
    $protocol = "https://";
// ---------------------------------------------------------

return array(

    // Set yiiPath (relative to Environment.php)
    'yiiPath' => dirname(__FILE__) . '/../../../framework/yii.php',
    'yiicPath' => dirname(__FILE__) . '/../../../framework/yiic.php',
    'yiitPath' => dirname(__FILE__) . '/../../../framework/yiit.php',

    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => true,
    'yiiTraceLevel' => 0,
    
    // Static function Yii::setPathOfAlias()
    'yiiSetPathOfAlias' => array(
        // uncomment the following to define a path alias
        //'local' => 'path/to/local-folder'
    ),
    
    'configWeb'=>array(
        
        'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'Sourst',
//        'theme'=>'mobile',
        
        // preloading 'log' component
        'preload'=>array('log', 'solarium'),

        // autoloading model and component classes
        'import'=>array(
            'application.models.*',
            'application.models.formModels.*',
            'application.components.*',
            'application.extensions.*',
            'application.extensions.solr.*',
            'application.modules.rights.*', //Rights
            'application.modules.rights.components.*', //Rights
        ),

        'modules'=>array(
            // uncomment the following to enable the Gii tool
            /*
            'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'Enter Your Password Here',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'=>array('127.0.0.1','::1'),
            ),
            */
        ),

        // application components
        'components'=>array(
            'user'=>array(
                // enable cookie-based authentication
                'allowAutoLogin'=>true,
                'class'=>'WebUser',
            ),
            // uncomment the following to enable URLs in path-format
            'urlManager'=>array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'caseSensitive' => true,
                'rules'=>array(
                    
                ),
            ),

            /*
             * Connection to the primary database.
             */
            'db'=>array(
                'class'=>'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=DbName',
                'emulatePrepare' => true,
                'username' => 'master',
                'password' => 'master_password',
                'charset' => 'utf8',
            ),

            'authManager' => array(
                'class' => 'CDbAuthManager',
                'connectionID'=>'db',
            ),
            
            'session' => array(
                'class' => 'CDbHttpSession',
                'connectionID' => 'db',
                'autoCreateSessionTable' => false,
                'sessionTableName' => 'yiiSessionTable',
            ),

            'imagick'=>array(
                'class'=>'EImagick',
            ),
            'mailer' => array(
                'class' => 'application.extensions.mailer.EMailer',
                'pathViews' => 'application.views.email',
                'pathLayouts' => 'application.views.email.layouts'
            ),
            'errorHandler'=>array(
                // use 'site/error' action to display errors
                'errorAction'=>'site/error',
            ),
            'log'=>array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),

                ),
            ),

            'file' => array(
                'class' => 'application.extensions.cFile.CFile'
            ),
        ),

        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params'=>array(
            // this is used in contact page
            'release'=>'alpha',
            'version'=>'v0.1',
            'SolariumAutoloderLoaded'=> 0 ,
            'adminEmail'=>'webmaster@example.com',
            'baseFolder' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR,
            'protocol' => $protocol, 
            'basePath' => $protocol.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR,
            'baseCssPath' => $protocol.$_SERVER['HTTP_HOST'].DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR,
            'graphics' => 'images' . DIRECTORY_SEPARATOR. 'graphics' . DIRECTORY_SEPARATOR,
            'postImages' => 'images' . DIRECTORY_SEPARATOR. 'postimages' . DIRECTORY_SEPARATOR,
            'userAvatars' => 'images' . DIRECTORY_SEPARATOR . 'avatars' . DIRECTORY_SEPARATOR,
            'icons' => 'images' . DIRECTORY_SEPARATOR . 'icons' . DIRECTORY_SEPARATOR,
            'emailSettings' => array(
                'host' => 'smtp.gmail.com',
                'SMTPSecure' => 'tls',
                'port' => 587,
                'users'=>array(
                    'admin'=>array(
                        'username' => 'admin@test.com',
                        'password' => 'fake_password'),
                    ),
                ),

        ),
        'modules' => array(
            'rights' => array(
                'superuserName' => 'Admin', // Name of the role with super user privileges.
                'authenticatedName' => 'Member', // Name of the authenticated user role.
                'userClass' => 'User',
                'userIdColumn' => 'userId', // Name of the user id column in the database.
                'userNameColumn' => 'username', // Name of the user name column in the database.
    //                'enableBizRule' => true, // Whether to enable authorization item business rules.
    //                'enableBizRuleData' => false, // Whether to enable data for business rules.
    //                'displayDescription' => true, // Whether to use item description instead of name.
    //                'flashSuccessKey' => 'RightsSuccess', // Key to use for setting success flash messages.
    //                'flashErrorKey' => 'RightsError', // Key to use for setting error flash messages.
                'install' => false, // Whether to install rights.
    //                'baseUrl' => 'rights', // Base URL for Rights. Change if module is nested.
                'layout' => 'application.modules.rights.views.layouts.main', // Layout to use for displaying Rights.
                'appLayout' => 'application.views.layouts.blank', // Application layout.
    //                'cssFile' => null, // Style sheet file to use for Rights.
                'debug' => false, // Whether to enable debug mode.
            ),
        ),
    ),
    
    
);