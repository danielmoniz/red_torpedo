<?php
return array(
    // Set yiiPath (relative to Environment.php)
    //'yiiPath' => dirname(__FILE__) . '/../../../yii/framework/yii.php',
    //'yiicPath' => dirname(__FILE__) . '/../../../yii/framework/yiic.php',
    //'yiitPath' => dirname(__FILE__) . '/../../../yii/framework/yiit.php',

    // Set YII_DEBUG and YII_TRACE_LEVEL flags
    'yiiDebug' => false,
    'yiiTraceLevel' => 0,
        
    // Static function Yii::setPathOfAlias()
    'yiiSetPathOfAlias' => array(
        // uncomment the following to define a path alias
        //'local' => 'path/to/local-folder'
    ),

    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => array(
        
        // application components
        'components'=>array(
            'urlManager'=>array(
                'urlFormat' => 'path',
                'showScriptName' => false,
                'caseSensitive' => true,
                'rules'=>array(
                ),
            ),
            'errorHandler'=>array(
                // use 'site/error' action to display errors
                'errorAction'=>'site/errorProduction',
            ),
        ),
    ),

    // This is the Console application configuration. Any writable
    // CConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value 'inherit' to copy from generated configWeb.
    'configConsole' => array(
        
    ),
);
?>