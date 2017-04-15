<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;
use AppBundle\Traits\StatusTrait;
use Symfony\Component\Config\Definition\Exception\Exception;

use \ZipArchive;

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
			$msg  = "<p><strong>". $e->getMessage()."</strong><br>";
			# $msg .= "<code>".preg_replace('/#\d+.*/', '$0<br>', $e->getTraceAsString())."</code>";
			# $msg .= "</p>";
			$this->append_status_message($msg);
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
		$this->extract_installers();
	}
	private function check_target_dir()
	{
		$td = $this->config()['server_path'];
		if(!$this->assert_target_dir($td)) {
			throw new Exception("Unable to create target dir '$td'", 1);
		}
	}
	private function create_dirs()
	{
		for ($i=0; $i < $this->config()['number_of_installations']; $i++) { 
			$td = $this->config()['server_path'].'/shop'.($i + 1);
			if(file_exists($td)) {
				throw new Exception("Target dir '$td' already exists.", 1);
			}
			$this->create_dir($td);
		};
		$this->append_status_message("Successfully created the directories.");
		$this->set_status_code(DefaultController::SUCCESS);
	}
	private function extract_installers()
	{
		for ($i=0; $i < $this->config()['number_of_installations']; $i++) { 
			$zip = new ZipArchive;
			$tmp_target = $this->config()['server_path'].'/shop'.($i + 1);
			if ($zip->open($this->src_zip_file()) === TRUE) {
			    $zip->extractTo($tmp_target);
			    $zip->close();
			} else {
				$this->set_status_message("<p>Error unzipping $tmp_target</p>");
				$this->set_status_code(DefaultController::ERROR);
			    return false;
			}
		};
		$this->append_status_message("Successfully unzipped the installers.");
		$this->set_status_code(DefaultController::SUCCESS);
		return true;
	}
	private function assert_target_dir($td)
	{
		if(file_exists($td)) return true;
		return $this->create_dir($td);
	}
	private function create_dir($td)
	{
		if(!@mkdir($td)) {
			$error = error_get_last();
			$this->set_status_code(DefaultController::ERROR);
			$this->append_status_message("Could not create dir '$td' ".$error['message']);
			return false;
		}
		return true;
	}
	private function src_zip_file()
	{
		$rv  = $this->config()['presta_source_dir'];
		$rv .= '/'.$this->config()['presta_version'].'.unzipped';
		$rv .= '/prestashop.zip';
		return $rv;
	}
}
?>