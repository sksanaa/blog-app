<?php

class User extends CActiveRecord {
    // Define the table name
    public function tableName() {
        return 'user';
    }

    // Validation rules
    public function rules() {
        return array(
            array('username, email, password', 'required'),
            array('email', 'email'),
            array('email', 'unique'),
            array('email', 'unique', 'message' => 'This email is already in use.'),
            array('password', 'length', 'min' => 6),
            array('verified', 'boolean'), // Add this line
            array('verified', 'safe'), // Add this line

        );
    }

    // Before saving the user
    public function beforeSave() {
        if ($this->isNewRecord) {
            // Generate an authentication key
            $this->auth_key = Yii::app()->security->generateRandomString(10);
            // Hash the password
            $this->password = $this->hashPassword($this->password);
            $this->verified = 0; // New users are not verified by default

        }
        return parent::beforeSave();
    }
    // Hash the password
    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
     // Verify the password
     public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }
    // Find user by email
    public static function findByEmail($email) {
        return self::model()->findByAttributes(array('email' => $email));
    }


    

    // Define the model
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
}