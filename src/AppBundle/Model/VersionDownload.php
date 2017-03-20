<?php
namespace AppBundle\Model;

use Symfony\Component\Yaml\Yaml;

class VersionDownload {

	public static function available_versions()
	{
		$fp = realpath(__DIR__.'/../../../app/config/prestashop_versions.yml');
		$l = Yaml::parse(file_get_contents($fp));
		return $l;
	}
}
?>