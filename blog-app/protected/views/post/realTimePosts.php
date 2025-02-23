<h1>Real-Time Posts</h1>

<div id="posts-list">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><?php echo CHtml::encode($post['title']); ?></h2>
            <p><?php echo CHtml::encode($post['content']); ?></p>
            <p>Author: <?php echo CHtml::encode($post['author']); ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- AJAX Script for Real-Time Updates -->
<script type="text/javascript">
    function refreshPosts() {
        $.ajax({
            url: '<?php echo Yii::app()->createUrl('post/realTimePosts'); ?>',
            type: 'GET',
            success: function(data) {
                $('#posts-list').html(data);
            },
        });
    }

    // Refresh the posts list every 10 seconds
    setInterval(refreshPosts, 10000);
</script>