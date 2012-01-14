<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>" media="screen, projection" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <style type='text/css'>
            /* Styles to go in the master CSS file */
            /*div { outline:black dashed 1px; }*/
            .section { clear:both; padding:5px; }
            .column { float:left; height:inherit; }
            .left, .right { width:20%; }

            .middle { width: 60%; }
            .content { padding:5px; }
            .block { /*border:black solid 1px;*/ float: left; width: 90%; }
            .post {
                    /*border: 1px solid black;*/
                    float: left;
                    clear:both;
                    width: 100%;
            }

            /* File-specific styles (regular) */
            body {
                font-family: arial;
                margin: 0px;
                height: 100%;
            }

            #header {
                width: 1000px;
                height:50px;
                margin-left: auto;
                margin-right: auto;
            }

            #headerBar {
                border-bottom: 1px solid #707281;
                -webkit-box-shadow: rgba(0, 0, 0, 0.52) 0 0 5px;
                width:100%;
                background-color: #CEE1E7;
            }

            #pageContainer {
                margin-left: auto;
                margin-right: auto;
            }

            #mainMenu {
                float: left;
                margin-top: 20px;
                margin-left: 95px;
                height: 35px;
                width: 400px;
            }

            #headerLogo {
                color: white;
                float: left;
                height: 100%;
                width: 200px;
                font-size:51px;
            }

            #headerUserWidget {
                float: right;
                height: 100%;
                width: 150px;
                background-color: #CEE1E7;
                border-bottom:  1px solid #707281;
                border-top: none;
                border-collapse: collapse;
            }

            #userProfileDropDown {
                border: 1px solid black;
                background-color: #5FBDCE;
                border-top: none;
                border-collapse: collapse;
                display: none;
                width: 1000px;
                float: right;
                
            }

            #footer {
                height: 100px;
                width: 1000px;
                margin-left: auto;
                margin-right: auto;
            }

            #footerBar {
                width: 100%;
                height: 100px;
                border-top: 1px solid black;
                float: left;
            }

            #contentContainer {
                width: 640px;
                min-height: 700px;
                margin-left: auto;
                margin-right: auto;
                  
            }

            #navigationTabs {
                margin-top: 27px;
                margin-left: 200px;
                float: left;
            }

            #navigationTabs ul {
                margin: 0px;
                padding: 0px;
                list-style-type: none;
            }

            #navigationTabs ul li {
                display: inline;
                background-color: #627995;
                border-top-left-radius: 5px;
                border-top-right-radius: 5px;
                padding: 5px;

            }

            #navigationTabs ul li a {
                background-color: transparent;
                color: #ffffff;
            }

            #navigationTabs ul li a:hover, #navigationTabs ul li.active a {
                background-color: red;
            }
        </style>

<script type="text/javascript">

            /*
            @TODO Deal with onclicks.  Add jquery listeners in a document.ready
            function at bottom of file to replace onclicks.
            */
             function profileToggle(){
                $("#userProfileDropDown").slideToggle();

                /*
                if($("#userProfileDropDown").css('display') == "none"){
                $('#headerUserWidget').css('background-color', '#5FBDCE');
                $("#userProfileDropDown").show();
                $('#headerUserWidget').css('border-bottom-color', '#5FBDCE');
                
                $("#userProfileDropDown").animate({ width: "100%" },
                      { queue: false, duration: 650 })
                         .animate({ height: "350px" }, 650);
                
                $("#userProfileDropDown").slideDown();
                }
                else
                {
                    $("#userProfileDropDown").animate( { width: "150px" },
                      { queue: false, duration: 650 })
                         .animate({ height: "0px" }, 650, 'linear', function()
                         {
                             $("#userProfileDropDown").hide();
                             $("#headerUserWidget").css("border-bottom-color", "#000000");
                             $('#headerUserWidget').css('background-color', '#FFFF00');

                         });
                }
                */

            }
</script>
      
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <div id="pageContainer">

            <div id="headerBar">
            <div id="header">

                <div id="headerLogo">
                    Sourst
                </div>

                <div id="navigationTabs">

                    <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => array(
                            array('label' => 'Home', 'url' => array('/site/index')),
                            array('label' => 'Tracking', 'url' => array('/feed/tracking')),
                            array('label' => 'Favourites', 'url' => array('/feed/favourites')),
                            array('label' => 'Contact', 'url' => array('/site/contact')),
                            array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
                        ),
                    ));
                    ?>
                </div><!-- navigation_tabs -->

                <div id="headerUserWidget">
                    <?php $this->widget('application.widgets.HeaderUserWidget', array()); ?>
                </div>
                

            </div><!-- header -->

            </div>
          


            <div id="contentContainer">

                <div id="userProfileDropDown">
                </div>
                <h1>THIS IS MOBILE</h1>
                <?php echo $content; ?>
                </div>

            <div id="footerBar">
                <div id="footer">
                    
            </div><!-- footer -->
            </div>

        </div><!-- page_container -->
    </body>
    
</html>

<script type="text/javascript">
    $(document).ready(function() {
        // load the user profile immediately and only once instead of every time
        $('#userProfileDropDown').load('/user/ajaxhandler', {function:'loadUserProfile'});
    });
</script>
