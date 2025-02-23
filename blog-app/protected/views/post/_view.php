<div class="post">
    <h2><?php echo CHtml::link(CHtml::encode($data->title), array('view', 'id' => $data->id)); ?></h2>
    <div class="post-content">
        <?php echo CHtml::encode($data->content); ?>
    </div>
    <div class="post-meta">
        <p>Visibility: <?php echo CHtml::encode($data->visibility); ?></p>
        <p>Created at: <?php echo CHtml::encode($data->created_at); ?></p>
    </div>
    <?php if(Yii::app()->user->id==$data->user_id){?>
    <div class="actions">
        <?php echo CHtml::link('Update', array('update', 'id' => $data->id)); ?>
        <?php echo CHtml::link('Delete', array('delete', 'id' => $data->id), array(
            'submit' => array('delete', 'id' => $data->id),
            'confirm' => 'Are you sure you want to delete this post?',
        )); ?>
    </div>
    <?php }?>
</div>