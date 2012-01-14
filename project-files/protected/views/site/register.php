<div class="form register">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
                'id' => 'member-registration-form-Register-form',
                'enableAjaxValidation' => false,
//                'action' => '/user/register',
                'action' => '/site/index',
            ));
    ?>

    <p class="note">Fields with <span class="required">*</span> are required.</p>

    <?php echo $form->errorSummary($model); ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'passwordConfirm'); ?>
        <?php echo $form->passwordField($model, 'passwordConfirm'); ?>
        <?php echo $form->error($model, 'passwordConfirm'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email', 
                (isset($email)) ? array('value'=>$email) : array()); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>

    <?php if (CCaptcha::checkRequirements()): ?>
            <div class="row">
        <?php echo $form->labelEx($model, 'loginCode'); ?>

        <?php echo $form->textField($model, 'loginCode'); ?>
        <?php echo $form->error($model, 'loginCode'); ?>
        <?php $this->widget('CCaptcha'); ?>
        </div>
    <? endif ?>
    
    <div class="hiddenInputs row">
        <?php echo $form->hiddenField($model, 'h', 
                array('value'=>$model->h)); ?>
    </div>
            <br>
            <div class="row buttons">
        <?php echo CHtml::submitButton('Submit'); ?>
        </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->