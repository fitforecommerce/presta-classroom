<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;

class Installer {

	private $status;
	private $config;

	public function __construct()
	{
		$this->status = [];
		$this->set_msg('nothing done');
		$this->set_status(DefaultController::VOID);
	}
	public function run()
	{
		$this->set_msg(print_r($this->config(), true));
		return $this->msg();
	}
	public function config()
	{
		return $this->config;
	}
	public function set_config($conf)
	{
		$this->config = $conf;
	}
	public function status()
	{
		return $this->status;
	}
	public function set_status($ns)
	{
		$this->status['code'] = $ns;
	}
	public function msg()
	{
		return $this->status['msg'];
	}
	public function set_msg($ns)
	{
		$this->status['msg'] = $ns;
	}
}
?>