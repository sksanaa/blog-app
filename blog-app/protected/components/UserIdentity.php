<?php
class UserIdentity extends CUserIdentity {
    private $_id;

    // Authenticate the user
    public function authenticate() {
    	// Find the user by username
    	$user = User::model()->findByAttributes(array('email' => $this->username));
		
    	// Log the result for debugging
    	Yii::log('User: ' . print_r($user, true), CLogger::LEVEL_INFO);

    	// If the user is not found, return an error
    	if ($user === null) {
    	    $this->errorCode = self::ERROR_USERNAME_INVALID;
    	}
    	// If the password is incorrect, return an error
    	elseif (!$user->verifyPassword($this->password)) {
    	    $this->errorCode = self::ERROR_PASSWORD_INVALID;
    	}
    	// If authentication is successful
    	else {
        	$this->_id = $user->id;
        	$this->setState('username', $user->username);
        	$this->setState('email', $user->email);
			$this->setState('verified', $user->verified); // Set the verified attribute
			$this->setState('userid', $user->id); // Set the verified attribute
        	$this->errorCode = self::ERROR_NONE;
    	}

    	return !$this->errorCode;
	}	
	// Get the user ID
    public function getId() {
        return $this->_id;
    }
}
?>