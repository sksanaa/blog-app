<h1>Your Posts</h1>

<?php $this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'itemView' => '_view', // Use the _view.php file to render each post
    'summaryText' => '', // Hide the summary text
)); ?>