<?php //echo CHtml::beginForm(); ?>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'signup-form',
    'enableAjaxValidation' => true, // Enable AJAX validation
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true, // Validate on field change
    ),
)); ?>
<div>
    <?php echo CHtml::label('Username', 'username'); ?>
    <?php echo CHtml::textField('User[username]'); ?>
</div>
<div>
    <?php echo CHtml::label('Email', 'email'); ?>
    <?php echo CHtml::textField('User[email]'); ?>
    <?php echo $form->error($model, 'email'); ?>


</div>
<div>
    <?php echo CHtml::label('Password', 'password'); ?>
    <?php echo CHtml::passwordField('User[password]'); ?>
</div>
<div>
    <?php echo CHtml::submitButton('Signup'); ?>
    <?php $this->endWidget(); ?>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#User_email').on('blur', function() {
            var email = $(this).val();
            if (email) {
                $.ajax({
                    url: '<?php echo Yii::app()->createUrl("site/checkEmail"); ?>',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#User_email_em_').text('This email is already in use.').show();
                        } else {
                            $('#User_email_em_').hide();
                        }
                    }
                });
            }
        });
    });
</script>
<?php echo CHtml::endForm(); ?>