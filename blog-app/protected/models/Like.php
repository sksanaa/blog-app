<?php
class Like extends CActiveRecord {
    public function tableName() {
        return 'like';
    }

    public function rules() {
        return array(
            array('user_id, post_id', 'required'),
            array('user_id, post_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
        );
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}
?>