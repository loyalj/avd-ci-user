<?php

class User extends CI_Model {

    private $tableName = 'users';
    private $ses_isLoggedIn = 'users.isLoggedIn';
    private $ses_userInfo = 'users.userInfo';

    function __contruct() {
        parent::__construct();

    }

    public function getByID($userID) {

    }

    public function create($username, $email, $password) {

    }

    public function currentUserInfo() {
        return $this->session->userdata($this->ses_userInfo);
    }
    
    public function isLoggedIn() {

        if($this->session->userdata($this->ses_isLoggedIn) && $this->session->userdata($this->ses_userInfo)) {
            return true;
        }

        return false;
    }

    public function login($username, $password) {


        // Login attempt will set the session to
        // logged out; which logs the user out..
        $this->session->set_userdata($this->ses_isLoggedIn, false);
       
        //See if there is a user with this name. 
        $this->db->where('username', $username);
        $checkUser = $this->db->get($this->tableName);
        
        // There should only ever be one use with a given name...
        // But just in case this returns more than 1 row; get out!
        if($checkUser->num_rows() != 1) {
            return false;
        }

        // One row exists
        $user = $checkUser->row();
        $checkPassword = $this->encodePassword($password, $user->salt);
        
        // THe provided password does not match one on record
        if($checkPassword['encodedPassword'] != $user->password) {
            return false;
        }
       
        $sessionUserInfo = array( 
            "id" => $user->id,
            "username" => $user->username,
            "email" => $user->email,
        );
        $this->session->set_userdata($this->ses_userInfo, $sessionUserInfo); 
        $this->session->set_userdata($this->ses_isLoggedIn, true);
        
        
        // No errors, so let's return true I guess.
        return true;
    }

    public function logout() {
        $this->session->unset_userdata($this->ses_userInfo);
        $this->session->unset_userdata($this->ses_isLoggedIn); 
    }


    /*
    * encodePassword converts an unencoded password string
    * and a salt value into an encoded password.  If no salt
    * is passed then a salt is generated.  This is used for
    * encoding a new password which has no salt.
    *
    * @param string $password
    *    The unencoded string password
    * 
    * @param string $salt
    *    If passed in the password is encoded with this salt
    *    If null then a new salt is generated
    *
    * @author loyalj
    */
    public function encodePassword($password, $salt = null) {

        if(!isset($salt)) {
            $salt = $this->generateSalt();
        }

        $encodedPassword = sha1($salt . $password);

        return array('encodedPassword' => $encodedPassword, 'salt' => $salt);  
    }


    public function generateSalt() {
        return sha1(mktime());
    }
}
