<?php
namespace AppBundle\Utils;

use Symfony\Component\Yaml\Yaml;

class VersionDownload {

	public static function available_versions()
	{
		$fp = realpath(__DIR__.'/../../../app/config/prestashop_versions.yml');
		$l = Yaml::parse(file_get_contents($fp));
		return $l;
	}
	function __construct($ndata) {
		$this->data = $ndata;
	}
	public function is_downloaded()
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
}
?>