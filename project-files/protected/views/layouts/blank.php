<?php
/**
 * This layout is used for any site pages that should have a blank layout 
 * (rather than the standard site layout).
 */
// DISABLE JQUERY AND USE GOOGLE's JQUERY INSTEAD
$cs=Yii::app()->clientScript;
$cs->scriptMap=array(
    'jquery.js'=>false,
    'jquery.ajaxqueue.js'=>false,
    'jquery.metadata.js'=>false,
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->params->baseCssPath; ?>topics.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>" media="screen, projection" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
        <script src="/javascript/topics.js" type="text/javascript" ></script>
        <script type="text/javascript" src="/javascript/views/pages/tracking.js"></script>
      
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <?php echo $content; ?>
    </body>
    
</html>
