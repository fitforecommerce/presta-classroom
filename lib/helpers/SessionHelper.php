<?php
class SessionHelper
{
    public $logged_in_user;

	public function logintest()
	{
		if($this->logged_in_user = $this->check_session()) {
			return $this->logged_in_user;
		}
		return false;
	}
	public function login($user)
	{
        global $_SESSION;
        $cvc = LoginController::createPassword(16);
        $timestamp = date('Y-m-d H:i:s');
        try {
            $this->startSession();
        } catch (Exception $e) {
            error_log('unable to launch session'.$e->getTraceAsString());
            echo "<code>".$e->getMessage()."</code>";
            echo "<code>".$e->getTraceAsString()."</code>";
            exit();
        }
		$_SESSION['user_email']                 = $user->email;
		$_SESSION['timestamp']                  = $timestamp;
		$_SESSION['ip']                         = LoginController::getip();
		$_SESSION['cookie_verification_code']   = $cvc;
		$_SESSION['timeout']                    = time();
	}
	private function startSession()
	{
        global $_SESSION;
        if($this->sessionStarted) return true;
		# session_name("prestaclassroom");

        $session_config = [
            'name' => 'prestaclassroom',
            'cookie_lifetime' => 86400
        ];
		if(!session_start($session_config)) {
		    throw new Exception("unable to start session");
		};
        $_SESSION['timeout'] = time();
		$this->sessionStarted = true;
        return true;
	}
    public function check_session() 
	{
        $this->startSession();
        error_log("SessionHelper::check_session \$_SESSION:\n".print_r($_SESSION, true));
		$msg = null;
		# if(!isset($_SESSION['user'])) $msg = 'User not set in Session';
		if(!is_string($_SESSION['user_email'])) $msg = 'Unable to find user_email in Session ';
		# if(!isset($_SESSION['cookie_verification_code'])) $msg = 'cookie_verification_code in SESSION does not match';
		if(!$this->check_session_timeout()) $msg = 'Session timed out!';
		if($msg) {
            error_log($msg);
			return false;
		}
		return User::create_from_email($_SESSION['user_email']);
    }
	private function check_session_timeout()
	{
        global $_SESSION;
		$inactive = 1000;
		if(count($_SESSION)==0) return false;
		$session_life = time() - $_SESSION['timeout'];
		if($session_life > $inactive) {
			session_destroy();
			return false;
		}
		$_SESSION['timeout'] = time();
		return true;
	}
    public function logout() 
	{
		$user = $this->check_session();
		if($user) {
    		global $_SESSION;
            unset($_SESSION['user_email']);
    		unset($_SESSION['cookie_verification_code']);
            session_unset();
            session_write_close();
		}
        if (!isset($_SESSION['user_email'])) {
            return true;
        }
		return false;
	}
}
?>