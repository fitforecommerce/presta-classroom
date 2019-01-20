<?php

class PrestaCliInstallerRunner {

	use StatusTrait;

	private $user_parameters;

	public function __construct($config)
	{
		$this->config = $config;
		$this->init_user_parameters();
	}
	public function run()
	{
		# Warning
		# When allowing user-supplied data to be passed to this function, use escapeshellarg() or escapeshellcmd() 
		# to ensure that users cannot trick the system into executing arbitrary commands.
    error_log("PrestaCliInstallerRunner::run ".$this->command());
    error_log('php '.$this->cli_script_path());
    # system('php '.$this->cli_script_path());
    # system('ping mysql:3306');
    # system('which php');
		system($this->command());
		$this->set_status_message('PrestaCliInstallerRunner: Execute command for shop '.$this->config->get('shop_index').':<br><code>'.$this->command().'</code>');
		$this->set_status_code(MainController::VOID);
		return true;
	}
	private function set_parameter($key, $nval)
	{
		if(!$this->is_parameter_name($key)) throw new Exception('Trying to set unavailable parameter in PrestaCliInstallerRunner');
		$this->user_parameters[$key] = $nval;
	}
	private function get_parameter($key)
	{
		return $this->user_parameters[$key];
	}
	private function user_parameters()
	{
		return $this->user_parameters;
	}
	private function init_user_parameters()
	{
		$this->user_parameters = array();
		foreach ($this->available_parameters() as $key => $d) {
      if($this->config->get($key)!=NULL) {
        error_log("PrestaCliInstallerRunner::command update param $key to ".$this->config->get($key));
        $this->set_parameter($key, $this->config->get($key));
      } else {
        $this->set_parameter($key, $d['default']);
      }
		}
	}
	# see docs at:
	# http://doc.prestashop.com/display/PS17/Installing+PrestaShop+using+the+command-line+script
	private function command()
	{
		# Warning
		# When allowing user-supplied data to be passed to this function, use escapeshellarg() or escapeshellcmd() 
		# to ensure that users cannot trick the system into executing arbitrary commands.
		$rv  = 'php ';
		$rv .= $this->cli_script_path().' ';
		foreach($this->user_parameters() as $k => $v) {
			$rv .= '--' . $k . '=' . escapeshellarg($v) .' ';
		}
		return $rv;
	}
  private function cli_script_path()
  {
    return $this->config->get('server_path').'/install/index_cli.php';
  }
	private function is_parameter_name($key)
	{
		return  array_key_exists($key, $this->available_parameters());
	}
	private function available_parameters()
	{
    $dbconfig = $this->config->appconfig('dbconfig');
    $i = $this->config->get('step_shop_index');
		return array(
			'step' 			    => array('default' => 'process'),
			'language' 		  => array('default' => 'en'),
			'timezone' 		  => array('default' => 'localhost'),
			'domain' 		    => array('default' => 'localhost'),
			'db_server' 	  => array('default' => 'localhost'), # $dbconfig['host']),
			'db_user' 		  => array('default' => $this->config->db_name_for_index($i)),
			'db_password' 	=> array('default' => 'testclassroom'),
			'db_name' 		  => array('default' => $this->config->db_name_for_index($i)),
			'db_clear' 		  => array('default' => '1'),
			'db_create' 	  => array('default' => '0'),
			'prefix' 		    => array('default' => 'ps_'),
			'engine' 		    => array('default' => 'InnoDB'),
			'name' 			    => array('default' => 'PrestaShop'),
			'activity' 		  => array('default' => '0'),
			'country' 		  => array('default' => 'fr'),
			'firstname' 	  => array('default' => 'John'),
			'lastname' 		  => array('default' => 'Doe'),
			'password' 		  => array('default' => '0123456789'),
			'email' 		    => array('default' => 'pub'),
			'license' 		  => array('default' => '0'),
			'newsletter' 	  => array('default' => '0'),
			'send_email' 	  => array('default' => '1')
		);
	}
}
?>