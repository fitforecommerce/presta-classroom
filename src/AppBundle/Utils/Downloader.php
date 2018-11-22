<?php
namespace AppBundle\Utils;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use AppBundle\Controller\DefaultController;
use AppBundle\Entity\VersionDownload;
use AppBundle\Utils\FileHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

use \ZipArchive;

class Downloader {

	use \AppBundle\Traits\StatusTrait;

	# config_item($key)
	public function __construct($ntarget)
	{
		$this->target_path = $ntarget;
		$this->fs = new FileHelper();
	}
	public function download($version=NULL)
	{
		if(!$version) $version = $this->current_version_str();
		# make sure the downloads dir exists
		if(!$this->assert_download_dir()) return false;
		if(!$this->assert_download_dir_writable()) return false;
		if(!$this->download_version($version)) return false;
		if(!$this->unzip_download($this->download_target_file($version))) return false;
		$this->set_status_message("Successfully downloaded $version");
		$this->set_status_code(DefaultController::SUCCESS);
		return true;
	}
	public function available_versions()
	{
    if(isset($this->available_versions)) return $this->available_versions;

		$fp = realpath(__DIR__.'/../../../app/config/prestashop_versions_dev.yml');
		$data = Yaml::parse(file_get_contents($fp));
    $downloads = [];
    foreach ($data as $v => $d) {
      $downloads[$v] = new VersionDownload($d, $this->target_path);
    }
    $this->available_versions = $downloads;
		return $this->available_versions;
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
	private function unzip_download($target_file)
	{
		$unzipped_target_path = preg_replace('/\.zip/', '', $target_file).'.unzipped';
		$this->fs->mkdir($unzipped_target_path);
		if(!$this->fs->unzip($target_file, $unzipped_target_path)) {
			$this->append_status_message($this->fs->status_message());
			$this->set_status_code($this->fs->status_code());
			return false;
		}
		return true;
	}
	private function assert_download_dir()
	{
		try {
			return $this->fs->assert_dir_exists($this->download_target_dir());
		} catch (Exception $e) {
			$this->set_status($this->fs->status());
			return false;
		}
	}
	private function assert_download_dir_writable()
	{
		if(!$this->fs->assert_dir_writable($this->download_target_dir())) {
			$this->set_status($this->fs->status());
			return false;
		}
		return true;
	}
	private function download_target_dir()
	{
		return $this->target_path;
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
	private function download_version($version)
	{
		$available_versions = $this->available_versions();
		# try to download the current version
		try {
			file_put_contents(
				$this->download_target_file($version), 
				fopen($available_versions[$version]['url'], 'r')
			);
			return true;
		} catch (Exception $e) {
			$this->set_status_message("<p>Error downloading $version got exception:  <code>$e</code></p>");
			$this->set_status_code(DefaultController::ERROR);
			return false;
		}
	}
}