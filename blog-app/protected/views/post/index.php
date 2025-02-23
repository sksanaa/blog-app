<h1>Posts</h1>

<!-- Search and Filter Form -->
<div class="search-form">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'action' => Yii::app()->createUrl('post/index'),
        'method' => 'get',
    )); ?>

    <div class="row">
        <?php echo $form->label($model, 'searchKeyword'); ?>
        <?php echo $form->textField($model, 'searchKeyword', array('placeholder' => 'Search by title or content')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'authorId'); ?>
        <?php echo $form->dropDownList($model, 'authorId', CHtml::listData(User::model()->findAll(), 'id', 'username'), array('empty' => 'Select Author')); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'startDate'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'startDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd', // Date format
                'showAnim' => 'fold',
            ),
            'htmlOptions' => array(
                'placeholder' => 'Start Date',
            ),
        )); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'endDate'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'model' => $model,
            'attribute' => 'endDate',
            'options' => array(
                'dateFormat' => 'yy-mm-dd', // Date format
                'showAnim' => 'fold',
            ),
            'htmlOptions' => array(
                'placeholder' => 'End Date',
            ),
        )); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton('Search'); ?>
    </div>

    <?php $this->endWidget(); ?>
</div>

<!-- Posts List -->
<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view',
)); ?>