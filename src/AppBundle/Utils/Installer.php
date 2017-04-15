<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;
use AppBundle\Traits\StatusTrait;
use AppBundle\Utils\FileHelper;
use Symfony\Component\Config\Definition\Exception\Exception;

class Installer {

	use StatusTrait;

	private $config;

	public function __construct()
	{
		$this->fs = new FileHelper();
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
		error_log("Installer::copy_files finished:\n\t".print_r($this->status()));
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
			$this->is_overwritable_target($td);
			$this->create_dir($td, $this->overwrite_targets());
		};
		$this->append_status_message("Successfully created the directories.");
		$this->set_status_code(DefaultController::SUCCESS);
	}
	private function extract_installers()
	{
		# extract the first installer then duplicate into the remaining dirs
		$first_target = $this->config()['server_path'].'/shop1';
		if(!$this->fs->unzip($this->src_zip_file(), $first_target)) {
			$this->set_status_message($this->fs->status_message());
			$this->set_status_code(DefaultController::ERROR);
			return false;
		}

		for ($i=1; $i < $this->config()['number_of_installations']; $i++) { 
			$tmp_target = $this->config()['server_path'].'/shop'.($i + 1);
			if(!$this->fs->xcopy($first_target, $tmp_target)) {
				$this->set_status_message("<p>Error unzipping $tmp_target</p>");
				$this->set_status_code(DefaultController::ERROR);
			    return false;
			}
		};
		$this->append_status_message("Successfully unzipped the installers.");
		$this->set_status_code(DefaultController::SUCCESS);
		return true;
	}
	private function is_overwritable_target($td)
	{
		if(file_exists($td) &! $this->overwrite_targets()) {
			throw new Exception("Target dir '$td' already exists and overwriting is not allowed.", 1);
		}
	}
	private function overwrite_targets()
	{
		if(isset($this->config()['overwrite_targets']) && $this->config()['overwrite_targets']) {
			return true;
		}
		return false;
	}
	private function assert_target_dir($td)
	{
		if(file_exists($td)) return true;
		return $this->create_dir($td);
	}
	private function create_dir($td, $overwrite=false)
	{
		if(is_dir($td) && $overwrite) {
			$this->fs->remove($td);
		}
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