<?php

// change the following paths if necessary

// set environment
require_once(dirname(__FILE__) . '/protected/extensions/yii-environment/Environment.php');
$env = new Environment();
//$env = new Environment('PRODUCTION'); // override mode

// remove the following lines when in production mode
//defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_DEBUG') or define('YII_DEBUG',$env->yiiDebug);

// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $env->yiiTraceLevel);

// run Yii app
//$env->showDebug(); // show produced environment configuration
require_once($env->yiiPath);
Yii::createWebApplication($env->configWeb)->run();

?>
