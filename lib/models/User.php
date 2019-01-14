<?php
/**
* Description
*/
class User
{
    public static function create_from_email($em)
    {
        error_log('DANGER!!! CLASS User NOT YET SAFELY IMPLEMENTED!');
        return new User();
    }
    function __construct()
    {
        $this->email    = 'test@vtlearn.de';
        $this->password = 'password';
    }
    function password_hash()
    {
        return Bcrypt::hash($this->password);
    }
}
?>