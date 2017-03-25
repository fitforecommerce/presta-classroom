<?php
namespace AppBundle\Utils;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use AppBundle\Controller\DefaultController;
use AppBundle\Entity\VersionDownload;

class Downloader {

	private $status;

	# config_item($key)
	public function __construct()
	{
		$this->status = array(
			'msg' => 'Nothing done yet',
			'code' => 0
		);
		$this->fs = new Filesystem();
	}
	public function download($version=NULL)
	{
		$available_versions = $this->available_versions();
		if(!$version) $version = $this->current_version_str();

		# make sure the downloads dir exists
		if(!$this->assert_download_dir()) return false;
		if(!$this->assert_download_dir_writable()) return false;

		# try to download the current version
		try {
			file_put_contents(
				$this->download_target_file($version), 
				fopen($available_versions[$version]['url'], 'r')
			);
		} catch (Exception $e) {
			error_log('error when downloading');
			$this->set_msg("<p>Error downloading $version got exception:  <code>$e</code></p>");
			$this->set_status(DefaultController::ERROR);
		}
		$this->set_msg("Successfully downloaded $version");
		$this->set_status(DefaultController::SUCCESS);
		return true;
	}
	public function available_versions()
	{
		return VersionDownload::available_versions();
	}
	public function status()
	{
		return $this->status;
	}
	public function set_status($ns)
	{
		$this->status['code'] = $ns;
	}
	public function set_msg($ns)
	{
		$this->status['msg'] = $ns;
	}
	public function downloaded_versions()
	{
		$arDir = array();
		$this->assert_download_dir();
		$d = dir($this->download_target_dir());
		while (false !== ($entry = $d->read())) {
			if($entry != '.' && $entry != '..') {
				array_push($arDir, $entry);
			}
		}
		$d->close();
		return $arDir;
	}
	public function is_downloaded($version)
	{
		if(file_exists($this->download_target_file($version))) return true;
		return false;
	}
	private function assert_download_dir()
	{
		if(file_exists($this->download_target_dir())) return true;
		try {
			$this->fs->mkdir($this->download_target_dir());
			return true;
		} catch (Exception $e) {
			error_log("ERROR when creating download dir in Installer_model:\n" . $e);
			$this->set_msg("<p>Could not create dir in " . $this->download_target_dir() . " got error <code>$e</code>");
			$this->set_status(Install::ERROR);
			return false;
		}
	}
	private function assert_download_dir_writable()
	{
		if(!is_writable($this->download_target_dir())) {
			$this->set_msg("<p>Dir " . $this->download_target_dir() . " is not writable, got error <code>$e</code>");
			$this->set_status(Install::ERROR);
			return false;
		}
		return true;
	}
	private function download_target_dir()
	{
		error_log('Downloader::download_target_dir use config values');
		$path  = realpath(__DIR__."/../../../web/bundles");
		$path .= "/presta_versions_download";
		return $path;
	}
	private function download_target_file($version_str)
	{
		return $this->download_target_dir() . "/$version_str.zip";
	}
	private function current_version_url()
	{
		$urls = $this->available_versions();
		return end($urls)['url'];
	}
	private function current_version_str()
	{
		$v = array_keys($this->available_versions());
		return end($v);
	}
}