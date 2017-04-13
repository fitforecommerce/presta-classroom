<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;
use AppBundle\Traits\StatusTrait;
use Symfony\Component\Config\Definition\Exception\Exception;

class Installer {

	use StatusTrait;

	private $config;

	public function __construct()
	{
	}
	public function run()
	{
		$this->append_status_message('<code>'.print_r($this->config(), true).'</code>');
		try {
			$this->copy_files();
		} catch (Exception $e) {
			$this->set_status_code(DefaultController::ERROR);
			$this->append_status_message($e->__toString());
			return false;
		}
		# $this->set_status_code(DefaultController::SUCCESS);
		# $this->append_status_message("Successfully installed the shops.");
		return $this->status();
	}
	public function config()
	{
		return $this->config;
	}
	public function set_config($conf)
	{
		$this->config = $conf;
	}
	private function copy_files()
	{
		$this->append_status_message("Install to ".$this->config()['server_path']);
		$this->check_target_dir();
		$this->create_dirs($this->config()['server_path']);
	}
	private function check_target_dir()
	{
		if(!$this->assert_target_dir($this->config()['server_path'])) {
			throw new Exception("Unable to create target dir $target_dir", 1);
		}
	}
	private function create_dirs($target_dir)
	{
		for ($i=0; $i < $this->config()['number_of_installations']; $i++) { 
			$this->assert_target_dir($target_dir.'/shop'.($i + 1));
		};
		$this->append_status_message("Successfully created the directories.");
		$this->set_status_code(DefaultController::SUCCESS);
	}
	private function assert_target_dir($td)
	{
		if(file_exists($td)) return true;
		if(!@mkdir($td)) {
			$error = error_get_last();
			$this->set_status_code(DefaultController::ERROR);
			$this->append_status_message("Could not create dir '$td' ".$error['message']);
			return false;
		}
		return true;
	}
}
?>