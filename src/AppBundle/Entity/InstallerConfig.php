<?php
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class InstallerConfig
{
	protected $presta_version;
	/**
	* @Assert\GreaterThan(0)
	*/
	protected $number_of_installations;
	protected $server_path;
	protected $overwrite_targets;
	protected $web_root_url;

	public function __construct() {
		$this->init_data();
	}
	public function getPrestaVersion()
	{
		return $this->presta_version;
	}
	public function setPrestaVersion($nv)
	{
		$this->presta_version = $nv;
	}
	public function getNumberOfInstallations()
	{
		return $this->number_of_installations;
	}
	public function setNumberOfInstallations($nv)
	{
		$this->number_of_installations = $nv;
	}
	public function getServerPath()
	{
		return $this->server_path;
	}
	public function setServerPath($nv)
	{
		$this->server_path = $nv;
	}
	public function getOverwriteTargets()
	{
		return $this->overwrite_targets;
	}
	public function setOverwriteTargets($nv)
	{
		$this->overwrite_targets = $nv;
	}
	public function getWebRootUrl()
	{
		return $this->web_root_url;
	}
	public function setWebRootUrl($nv)
	{
		$this->web_root_url = $nv;
	}
	private function init_data()
	{
		$this->number_of_installations = 1;
		$this->server_path = '';
		$this->overwrite_targets = false;
		$this->web_root_url = 'localhost';
	}
}
?>