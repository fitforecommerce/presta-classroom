<?php
/**
* The mother of all controllers
*/
class MainController
{
    use ConfigTrait;
    use SmartyTrait;
    use MessageViewTrait;

	const VOID 		= 0;
	const SUCCESS 	= 1;
	const ERROR		= 2;

    protected $message_view_class = 'MessageView';

    public $login_controller;

    public function __construct()
    {
    }
	protected function default_action()
    {
        return 'maincontrollerdefault';
    }
	public function logged_in_user()
	{
		if(!isset($this->logged_in_user)) {
            $this->logged_in_user = $this->session_helper()->logintest();
            $this->s()->assign('logged_in_user', $this->logged_in_user);
        }
		return $this->logged_in_user;
	}
	protected function session_helper() {
		if(isset($this->session_helper)) return $this->session_helper;
        $this->session_helper = new SessionHelper();
        return $this->session_helper;
	}
    protected function controller()
    {
        if(isset($this->controller)) return $this->controller;
        $this->contr_class = $this->router()->controller();
        return $this->contr_class;
    }
    protected function controller_class()
    {
        return get_class($this->controller());
    }
	public function run($params = null) {
        error_log("\n\n****** ".get_class($this)." -> MainController::run action: '".$this->action()."' **********************");
		if(!$this->user_has_access()) {
            error_log("\tMainController::run ERROR user has no access ".$this->action());
            $lc = new LoginController();
            $lc->action = 'showLoginPage';
            return $lc->run();
        }
		if(!method_exists($this, $this->action())) {
            error_log("\tMainController::run ERROR action not found ".$this->action());
			$this->show_action_error($this->action());
			return false;
		}
        error_log("\tMainController::run ->".$this->action());
		call_user_func(array($this, $this->action()), $params);
        return true;
	}
	protected function requires_login()
	{
		return false;
	}
	private function user_has_access()
	{
        error_log("MainController::user_has_access controller: ".$this->controller_class());
        if($this->controller_class()=="LoginController") {
            $whitelist = ['validate', 'logout'];
    		if(in_array($this->action(), $whitelist)){ 
                return true;
            }
        }
		if($this->logged_in_user()) return true;
		if(!$this->requires_login()) {
            return true;
        }
		return false;
	}
	private function show_action_error($action='')
	{
		error_log("MainController::show_action_error: Invalid action chosen");
		error_log("\t Controller class: ".get_class($this));
		error_log("\t action: ".$action);
		$this->msg("Invalid action chosen for '".get_class($this)."->$action'", MessageView::ERROR);
		$this->action = $this->default_action();
		$this->run();
	}
  public function base_path()
  {
    return $this->router()->base_path();
  }
    private function router()
    {
        if(!isset($this->router)) $this->router = Router::from_request();
        return $this->router;
    }
	protected function action()
	{
        if(isset($this->action)) return $this->action;
        $this->action = $this->router()->action();
		if(!isset($this->action) || $this->action=='') {
			$this->action = $this->default_action();
		}
        return $this->action;
	}
}
?>