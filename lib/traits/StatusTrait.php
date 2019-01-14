<?php

trait StatusTrait
{
	private $status = array(
		'code' 		=> MainController::VOID,
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
	public function status_code()
	{
		return $this->status['code'];
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
	public function status_message()
	{
		return $this->status['message'];
	}
	public function append_status_message($nstr)
	{
		if(!is_array($nstr)) {
			array_push($this->status['message'], $nstr);
		} else {
			foreach ($nstr as $v) {
				$this->status['message'][] = $v;
			}
		}
	}
	private function css_class_for_code($stat)
	{
		return $this->css_status_codes()[$stat];
	}
	private function css_status_codes()
	{
		return array(
			MainController::VOID => 'alert-warning',
			MainController::SUCCESS => 'alert-success',
			MainController::ERROR => 'alert-danger'
		);
	}
	
}
?>