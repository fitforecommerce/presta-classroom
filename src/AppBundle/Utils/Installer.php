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
			return $this->status();
		}
		$this->set_status_code(DefaultController::SUCCESS);
		$this->append_status_message("<br>Successfully installed the shops.");
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
		$target_dir = realpath($this->config()['server_path']);
		echo "<p>target dir: $target_dir";
		if(!$this->assert_target_dir($target_dir)) throw new Exception("Unable to create target dir $target_dir", 1);
		;
	}
	private function assert_target_dir($td)
	{
		if(file_exists($td)) return true;
		if(!@mkdir($td)) {
			$error = error_get_last();
			$this->set_status_code(DefaultController::ERROR);
			$this->append_status_message("Could not create target dir '$td' <br>".$error['message']);
			return false;
		}
	}
}
?>