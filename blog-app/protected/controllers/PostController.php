<?php
class PostController extends Controller {
    /*public function actionIndex() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'visibility = :visibility OR user_id = :user_id';
        $criteria->params = array(
            ':visibility' => 'public',
            ':user_id' => Yii::app()->user->id,
        );
    
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => $criteria,
        ));
    
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }*/
    public function actionIndex() {
        $model = new Post('search');
        $model->unsetAttributes(); // Clear any default values
    
        if (isset($_GET['Post'])) {
            $model->attributes = $_GET['Post'];
        }
    
        // Create a CDbCriteria for filtering posts
        $criteria = new CDbCriteria;
        $criteria->condition = 'visibility = :visibility';
//        $criteria->condition = 'visibility = :visibility OR user_id = :user_id';

        $criteria->params = array(
            ':visibility' => 'public',
   //         ':user_id' => Yii::app()->user->id,
        );
    
        // Add search conditions if a keyword is provided
        if (!empty($model->searchKeyword)) {
           // echo "Inside";
           $criteria->addCondition('(title LIKE :keyword OR content LIKE :keyword)', 'AND');
           $criteria->params[':keyword'] = '%' . $model->searchKeyword . '%';
        }
        // Filter by author (AND)
        if (!empty($model->authorId)) {
            $criteria->addCondition('user_id = :authorId', 'AND');
            $criteria->params[':authorId'] = $model->authorId;
        }

        // Filter by date range (AND)
        if (!empty($model->startDate) && !empty($model->endDate)) {
            $criteria->addBetweenCondition('created_at', $model->startDate, $model->endDate, 'AND');
        }
        // Create a CActiveDataProvider with the combined criteria
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10, // Number of posts per page
            ),
        ));
        //echo "<pre>";
        //print_r($dataProvider);
        // Render the index view
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }
    public function actionView($id) {
        $model = $this->loadModel($id);
        $comment = new Comment();

        if (isset($_POST['Comment'])) {
            $comment->attributes = $_POST['Comment'];
            $comment->post_id = $model->id;
            $comment->user_id = Yii::app()->user->id; // Assuming user authentication is implemented
            if ($comment->save()) {
                Yii::app()->user->setFlash('success', 'Comment added successfully!');
                $this->refresh();
            }
        }

        $this->render('view', array(
            'model' => $model,
            'comment' => $comment,
        ));
    }
    protected function loadModel($id) {
        $model = Post::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested post does not exist.');
        }
        return $model;
    }
    public function actionCreate() {
        $model = new Post;
        if (isset($_POST['Post'])) {
            //echo "<pre>";
            //echo Yii::app()->user->id;
            $model->attributes = $_POST['Post'];
            $model->user_id = Yii::app()->user->id;
            if ($model->save()) {
                Yii::log('Redirecting to post view page.', CLogger::LEVEL_INFO);

                $this->redirect(array('post/view', 'id' => $model->id));
            }
        }
        $this->render('_form', array('model' => $model));
    }

    // Add actions for Update, Delete, and View

    public function filters() {
        return array(
            'accessControl', // Enable access control for all actions
        );
    }
    
    
    
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'like', 'unlike'),
                'users' => array('*'), // Allow all users to view posts
            ),
            array('allow',
                'actions' => array('create', 'userPosts','realTimePosts'),
                'users' => array('@'), // Allow only authenticated users
                'expression' => '!Yii::app()->user->isGuest && Yii::app()->user->verified', // Allow only verified users
            ),
            array('allow',
                'actions' => array('update', 'delete'),
                'users' => array('@'), // Allow only authenticated users
                'expression' => '!Yii::app()->user->isGuest && Yii::app()->user->verified && Post::model()->findByPk($_GET["id"])->user_id == Yii::app()->user->id'
            ),
            array('deny', // Deny all other users
                'users' => array('*'),
            ),
        );
    }
    
    public function actionLike($id) {
        $like = new Like;
        $like->user_id = Yii::app()->user->id;
        $like->post_id = $id;
        if ($like->save()) {
            Yii::app()->user->setFlash('success', 'Post liked!');
        } else {
            Yii::app()->user->setFlash('error', 'Failed to like post.');
        }
        $this->redirect(array('post/view', 'id' => $id));
    }
    
    public function actionUnlike($id) {
        $like = Like::model()->findByAttributes(array(
            'user_id' => Yii::app()->user->id,
            'post_id' => $id,
        ));
        if ($like && $like->delete()) {
            Yii::app()->user->setFlash('success', 'Post unliked!');
        } else {
            Yii::app()->user->setFlash('error', 'Failed to unlike post.');
        }
        $this->redirect(array('post/view', 'id' => $id));
    }
    public function actionUserPosts() {
        // Fetch posts created by the logged-in user
        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id = :user_id';
        $criteria->params = array(':user_id' => Yii::app()->user->id);
    
        $dataProvider = new CActiveDataProvider('Post', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 10, // Number of posts per page
            ),
        ));
    
        // Render the userPosts view
        $this->render('userPosts', array(
            'dataProvider' => $dataProvider,
        ));
    }
    public function actionUpdate($id) {
        $model = $this->loadModel($id);
    
        // Ensure the logged-in user is the owner of the post
        if ($model->user_id !== Yii::app()->user->id) {
            throw new CHttpException(403, 'You are not authorized to update this post.');
        }
    
        if (isset($_POST['Post'])) {
            $model->attributes = $_POST['Post'];
            if ($model->save()) {
                echo "inside save";
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
    
        $this->render('update', array(
            'model' => $model,
        ));
    }

    public function actionDelete($id) {
        $model = $this->loadModel($id);
    
        // Ensure the logged-in user is the owner of the post
        if ($model->user_id !== Yii::app()->user->id) {
            throw new CHttpException(403, 'You are not authorized to delete this post.');
        }
    
        // Delete the post
        if ($model->delete()) {
            Yii::app()->user->setFlash('success', 'Post deleted successfully.');
        } else {
            Yii::app()->user->setFlash('error', 'Failed to delete the post.');
        }
    
        // Redirect to the posts list after deletion
        $this->redirect(array('post/userPosts'));
    }

    public function actionRealTimePosts() {
        // Fetch posts with at least 1 comment and authors with at least 2 blogs
        $posts = Yii::app()->db->createCommand()
            ->select('p.id, p.title, p.content, u.username as author')
            ->from('post p')
            ->join('user u', 'p.user_id = u.id')
            ->join('comment c', 'p.id = c.post_id')
            ->group('p.id')
            ->having('COUNT(c.id) >= 1')
            ->andWhere('(SELECT COUNT(*) FROM post p2 WHERE p2.user_id = u.id) >= 2')
            ->queryAll();
    
        // Render the real-time posts view
        $this->render('realTimePosts', array(
            'posts' => $posts,
        ));
    }

}
?>