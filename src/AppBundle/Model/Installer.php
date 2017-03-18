<?php

class Installer_model extends CI_Model {

	private $status;

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->lang->load('ui', 'english');
		$this->load->helper('url');

		$this->set_msg($this->lang->line('installer_void'));
		$this->set_status(Install::VOID);
		error_log('Installer_model hühü');
	}
	public function download()
	{
		$this->downloader->download();
		$this->set_status($this->downloader->status());
	}
	public function deploy_prestashop_installer()
	{
	}
	public function status()
	{
		return $this->status;
	}
	public function set_status($ns)
	{
		$this->status['steps'] = $this->steps();
		$this->status['code'] = $ns;
	}
	public function set_msg($ns)
	{
		$this->status['msg'] = $ns;
	}
	private function base_url()
	{
		return base_url('index.php/install/');
	}
	private function steps()
	{
		return array(
			'download' 	=> $this->download_step(),
			'setup' 	=> $this->setup_step(),
			'install' 	=> $this->install_step()
		);
	}
	private function download_step()
	{
		$addstr = "<ul>";
		$dv = $this->downloader->downloaded_versions();
		foreach ($dv as $v) {
			$addstr .= "<li>$v</li>";
		}
		$addstr .= "</ul>";
		return array(
			'description' => 'Download the prestashop installer '.$addstr,
			'url' => $this->base_url() . 'download'
		);
	}
	private function setup_step()
	{
		return array(
			'description' => 'Configure your setup',
			'url' => $this->base_url() . 'setup'
		);
	}
	private function install_step()
	{
		return array(
			'description' => 'Run the Prestahop installation process',
			'url' => $this->base_url() . 'install_ps'
		);
	}
}
?>