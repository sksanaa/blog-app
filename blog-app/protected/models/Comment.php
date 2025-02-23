<?php
class Comment extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'comment';
    }

    public function rules()
    {
        return array(
            array('user_id, post_id, content', 'required'),
            array('content', 'safe'),
        );
    }

    public function relations()
    {
        return array(
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }
}

?>