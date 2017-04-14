<?php

namespace AppBundle\Traits;

use AppBundle\Controller\DefaultController;

trait StatusTrait
{
	private $status = array(
		'code' 		=> DefaultController::VOID,
		'message' 	=> [],
		'cssclass' 	=> 'alert-warning',
		'debug'		=> []
	);
	public function status()
	{
		return $this->status;
	}
	public function set_status($nstat)
	{
		$this->status = $nstat;
	}
	public function set_status_code($ncode)
	{
		$this->status['code'] = $ncode;
		$this->status['cssclass'] = $this->css_class_for_code($ncode);
	}
	public function set_status_message($nstr)
	{
		$this->status['message'] = [$nstr];
	}
	public function append_status_message($nstr)
	{
		array_push($this->status['message'], $nstr);
	}
	private function css_class_for_code($stat)
	{
		return $this->css_status_codes()[$stat];
	}
	private function css_status_codes()
	{
		return array(
			DefaultController::VOID => 'alert-warning',
			DefaultController::SUCCESS => 'alert-success',
			DefaultController::ERROR => 'alert-danger'
		);
	}
	
}
?>