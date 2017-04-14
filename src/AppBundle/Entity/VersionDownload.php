<?php
namespace AppBundle\Entity;

use Symfony\Component\Yaml\Yaml;

class VersionDownload {

	public static function available_versions()
	{
		$fp = realpath(__DIR__.'/../../../app/config/prestashop_versions_dev.yml');
		$l = Yaml::parse(file_get_contents($fp));
		return $l;
	}
	function __construct($ndata) {
		$this->data = $ndata;
	}
	public function is_downloaded($version)
	{
		if(file_exists($this->download_target_file($version))) return true;
		return false;
	}
}
?>