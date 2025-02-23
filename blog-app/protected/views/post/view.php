<h1><?php echo CHtml::encode($model->title); ?></h1>

<div class="post-content">
    <?php echo CHtml::encode($model->content); ?>
</div>

<div class="post-meta">
    <p>Posted by: <?php echo CHtml::encode($model->user->username); ?></p>
    <p>Visibility: <?php echo CHtml::encode($model->visibility); ?></p>
    <p>Created at: <?php echo CHtml::encode($model->created_at); ?></p>
</div>

<div class="like-section">
    <p>
        <?php if (!Yii::app()->user->isGuest): ?>
            <?php if (!$model->isLikedByUser(Yii::app()->user->id)): ?>
                <?php echo CHtml::link('Like', array('post/like', 'id' => $model->id)); ?>
            <?php else: ?>
                <?php echo CHtml::link('Unlike', array('post/unlike', 'id' => $model->id)); ?>
            <?php endif; ?>
        <?php endif; ?>
        (<?php echo $model->likes ? count($model->likes) : 0; ?> likes)
    </p>
</div>
<?php if(Yii::app()->user->id==$model->user_id){?>

<div class="actions">
    <?php echo CHtml::link('Update', array('update', 'id' => $model->id)); ?>
    <?php echo CHtml::link('Delete', array('delete', 'id' => $model->id), array(
        'submit' => array('delete', 'id' => $model->id),
        'confirm' => 'Are you sure you want to delete this post?',
    )); ?>
</div>
<?php }?>