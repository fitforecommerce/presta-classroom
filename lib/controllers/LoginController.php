<?php
/**
 * This class is responsible for controlling the login process.
 *
 * @package Controller
 * @author Martin Kolb <admin@vt-learn.de>
 *
 */
class LoginController extends MainController {
	protected $sessionStarted = false;
    private $showedLoginPage = false;
    public $session_no = 0;
	# var $message_view_class = 'LoginView';
    
	public function default_action() {
		return 'showLoginPage';
	}
    public function logout()
    {
        # echo "<p>LoginController::logout() reached</p>";
        if($this->session_helper()->logout()) {
            $this->msg("You were successfully logged out!", MessageView::SUCCESS);
        } else {
            $this->msg("You could note be logged out!", MessageView::ERROR);
        }
        $this->showLoginPage();
    }
	public function validate()
	{
		if($this->check_login_form()) {
            # error_log("LoginController::validate() User ".$this->logged_in_user->email."successfully logged in");
			$fwd='Location: '.$this->www_root_path().'/dashboard';
			header($fwd);
		} else {
            # error_log("LoginController::validate() User not logged in");
			$this->showError();
			$this->showLoginPage();
		}
	}
    public function showLoginPage() 
	{
        # throw new Exception("showLoginPage reached");
		unset($_POST['FORM']);
		unset($_REQUEST['FORM']);
        if(!$this->showedLoginPage) {
            $this->s()->display('login/form.tpl');
            $this->showedLoginPage = true;
        }
    }
    private function check_login_form() 
	{
		global $_POST;
        $email   = $_POST['email'];
        $pass    = $_POST['password'];

		if($email=='' || $pass=='') return false;

		$user = User::create_from_email($email);
		if (!$user instanceof User) return false;
		error_log("LoginController.Php"
            ."\nBcrypt::userpass       ".$user->password
            ."\nBcrypt::postpass       ".$pass
            ."\nBcrypt::hash post-pass ".Bcrypt::hash($pass)
            ."\nBcrypt::hash user-pass ".Bcrypt::hash($user->password));
		$is_correct = Bcrypt::check(
			$pass, 
			$user->password_hash(),
			function($pass, $hash) {
				return $hash == sha1($pass); 
			}
		);
		if ($is_correct) {
            $this->session_helper()->login($user);
            $this->logged_in_user = $user;
			return true;
		}
        error_log("LoginController::check_login_form() Bcrypt passwords did not match");
		return false;
    } // end check_login_form()
    private function showError($e=false) 
	{
		if($this->action()=="validate") $this->msg('Invalid login', MessageView::ERROR);
		if($e) {
			echo "<div class='error_stack'>".nl2br($e)."</div>";
		}
    } // END: showError()
	protected function session_helper() {
		if(isset($this->session_helper)) return $this->session_helper;
        $this->session_helper = new SessionHelper();
        return $this->session_helper;
	}
	static function getip () 
	{
	    if (
			isset ($_SERVER['HTTP_CLIENT_IP']) 
			&& LoginController::validip ($_SERVER['HTTP_CLIENT_IP'])
			) {
	        return $_SERVER['HTTP_CLIENT_IP'];
	    	}
	    if (isset ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        foreach (explode (",", $_SERVER['HTTP_X_FORWARDED_FOR']) as $ip) {
	            if (\de\vtlearn\controllers\LoginController::validip (trim ($ip))) {
	                return $ip;
	            }
	        }
	    }
	    if (isset ($_SERVER['HTTP_X_FORWARDED']) 
			&& LoginController::validip ($_SERVER['HTTP_X_FORWARDED'])
			) {
	        return $_SERVER['HTTP_X_FORWARDED'];
	    } elseif (isset ($_SERVER['HTTP_X_FORWARDED_FOR']) 
				&& LoginController::validip ($_SERVER['HTTP_FORWARDED_FOR'])
				) {
	        return $_SERVER['HTTP_FORWARDED_FOR'];
	    } elseif (isset ($_SERVER['HTTP_FORWARDED']) 
				&& LoginController::validip ($_SERVER['HTTP_FORWARDED'])) {
	        return $_SERVER['HTTP_FORWARDED'];
	    } elseif (isset ($_SERVER['HTTP_X_FORWARDED']) 
				&& LoginController::validip ($_SERVER['HTTP_X_FORWARDED'])) {
	        return $_SERVER['HTTP_X_FORWARDED'];
	    } else {
	        return $_SERVER['REMOTE_ADDR'];
	    }
	}
	
	static function validip($ip)
	{
		return ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $ip)) ? FALSE : TRUE;		
	}// end validip()

    static function createPassword($length = 0) 
	{
        if ($length == 0) $length = 12;
        $dummy = array_merge(
			range('0', '9'), 
			range('a', 'z'), 
			range('A', 'Z'), 
			array('#', '&', '@', '$', '_', '%', '?', '+')
		);
        // shuffle array
        mt_srand((double)microtime() * 1000000);
        for ($i = 1;$i <= (count($dummy) * 2);$i++) {
            $swap = mt_rand(0, count($dummy) - 1);
            $tmp = $dummy[$swap];
            $dummy[$swap] = $dummy[0];
            $dummy[0] = $tmp;
        }
		$rv = substr(implode('', $dummy), 0, $length);
        return $rv;
    } // end createPassword()
} // END class LoginController

?>