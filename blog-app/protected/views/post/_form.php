<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'post-form',
    //'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
    ),
)); ?>

<div class="row">
    <?php echo $form->labelEx($model, 'title'); ?>
    <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'title'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'content'); ?>
    <?php echo $form->textArea($model, 'content', array('rows' => 6, 'cols' => 50)); ?>
    <?php echo $form->error($model, 'content'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'visibility'); ?>
    <?php echo $form->dropDownList($model, 'visibility', array('public' => 'Public', 'private' => 'Private')); ?>
    <?php echo $form->error($model, 'visibility'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
</div>

<?php $this->endWidget(); ?>