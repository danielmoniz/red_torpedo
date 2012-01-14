

<style type='text/css'>
    #loginSignup {
        border: 2px solid #333333;
        padding: 15px;
        margin-top: 30px;
        float: left;
    }

</style>
<?php if (Yii::app()->user->isGuest): ?>
    <div id="loginSignup">
        <h3>Login</h3>

        <div id="loginBox">
            <?php echo $loginBox; ?>
        </div>
        <hr />
           <h3>Sign Up</h3>
        <div id="signupBox">
            <?php echo $signupBox; ?>
        </div>
    </div>
    </div>
<?php endif; ?>