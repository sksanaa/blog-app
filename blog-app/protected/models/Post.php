<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property string $visibility
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Comment[] $comments
 * @property Like[] $likes
 * @property User $user
 */
class Post extends CActiveRecord
{
    public $searchKeyword; // Add this line
	public $authorId;      // For author filter
    public $startDate;     // For date range filter
    public $endDate;       // For date range filter

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'post';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, title, content', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
			array('visibility', 'length', 'max'=>7),
			array('created_at', 'safe'),
			array('searchKeyword, authorId, startDate, endDate', 'safe', 'on' => 'search'), // Add this line

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, title, content, visibility, created_at', 'safe', 'on'=>'search'),
		);
	}
	public function isLikedByUser($userId) {
		return Like::model()->exists('user_id = :user_id AND post_id = :post_id', array(
			':user_id' => $userId,
			':post_id' => $this->id,
		));
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'comments' => array(self::HAS_MANY, 'Comment', 'post_id'),
			'likes' => array(self::HAS_MANY, 'Like', 'post_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'title' => 'Title',
			'content' => 'Content',
			'visibility' => 'Visibility',
			'created_at' => 'Created At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Post the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function search($keyword) {
		$criteria = new CDbCriteria;
	
		// Search by title or content
		$criteria->addSearchCondition('title', $keyword, true, 'OR');
		$criteria->addSearchCondition('content', $keyword, true, 'OR');
	
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => 10, // Number of posts per page
			),
		));
	}
}
