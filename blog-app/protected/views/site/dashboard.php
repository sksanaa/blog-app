<h1>Welcome, <?php echo Yii::app()->user->name; ?>!</h1>
<p>This is your secure dashboard.</p>

<?php //echo Yii::app()->user->id; ?>

<div class="dashboard-links">
    <p><?php echo CHtml::link('Create a New Post', array('post/create')); ?></p>
    <p><?php echo CHtml::link('View Your Posts', array('post/userPosts')); ?></p>
</div>

<!-- Logout Button -->
<p><?php echo CHtml::link('Logout', array('site/logout')); ?></p>