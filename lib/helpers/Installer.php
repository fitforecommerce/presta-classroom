<?php
class Installer {

	use StatusTrait;
  use ConfigTrait;

	private $config;

	public function __construct($nconfig)
	{
    if(!$nconfig instanceof InstallerConfig) {
      throw new Exception("Null config passed to Installer::__construct()", 1);
    }

    $this->config = $nconfig;
    $this->fs = new FileHelper();
	}

  #
  # Runner functions
  #
  public function setup_db()
  {
    $dbi = new DatabaseInstaller($this->config);
    $dbi->run();
    $this->append_status_message("yoho set up the database");
    return true;
  }
  public function assert_dirs()
  {
    $this->fs->assert_dir_exists($this->config->get('server_path'));
  }
  public function unzip_src()
  {
    if($this->config->get('step_shop_index') > 1) return true;
    ini_set('max_execution_time', 60);

    error_log("\n\nInstaller::extract_installers()");
    error_log("\t path:".$this->server_path_for_shop($this->first_shop_index()));

		if(!$this->fs->unzip($this->src_zip_file(), $this->server_path_for_shop($this->first_shop_index()))) {
			$this->set_status_message($this->fs->status_message());
			$this->set_status_code(MainController::ERROR);
			return false;
		}
		$this->set_status_message($this->fs->status_message());
		$this->set_status_code(MainController::SUCCESS);
    return true;
  }
	public function copy_folders()
	{
		$this->append_status_message("Install to ".$this->config->get('server_path'));
		$this->check_target_dir();
		$this->create_dirs($this->config->get('server_path'));

    $fi = $this->config->get('first_shop_index');
    $i = $this->config->get('step_shop_index');

    # Start with the second shop, as the first one was
    # already created when unzipping the installer!
    if($i==1) return true;

		if(!$this->fs->system_xcopy($this->server_path_for_shop($fi), $this->server_path_for_shop($i))) {
      error_log("Installer::copy_folders: received error when copying ".$this->server_path_for_shop($fi)." -> ".$this->server_path_for_shop($i));
			$this->set_status_message("<p>Error copying ".$this->server_path_for_shop($fi)." -> ".$this->server_path_for_shop($i)."</p>");
			$this->set_status_code(MainController::ERROR);
		  return false;
		}

		$this->append_status_message("Successfully unzipped the installers.");
		$this->set_status_code(MainController::SUCCESS);
		return true;
	}
	public function run_installers()
	{
    $i = $this->config->get('step_shop_index');
    ini_set('max_execution_time', 300);

    $wwwconfig = $this->config->appconfig('webserver');

		$tmp_conf  = $this->config;
		$tmp_conf->set('base_uri', $this->base_uri_for_shop($i));
		$tmp_conf->set('server_path', $this->server_path_for_shop($i));
		$tmp_conf->set('shop_index', $i);
    $tmp_conf->set('domain', $wwwconfig['host'].':'.$wwwconfig['port']);

		$tmp_pcli = new PrestaCliInstallerRunner($tmp_conf);
		$tmp_pcli->run();

		$this->append_status_message($tmp_pcli->status_message());
		$this->set_status_code(MainController::SUCCESS);
    return true;
	}
  public function cleanup()
  {
    $i = $this->config->get('step_shop_index');
		$this->fs->remove($this->server_path_for_shop($i).'/install');
    $this->fs->rename($this->server_path_for_shop($i).'/admin', $this->server_path_for_shop($i).'/admin123');
  }
	private function check_target_dir()
	{
		$td = $this->config->get('server_path');
		if(!$this->assert_target_dir($td)) {
			throw new Exception("Unable to create target dir '$td'", 1);
		}
	}
	private function server_path_for_shop($i)
	{
		return $this->config->get('server_path').'/shop'.$i;
	}
  private function base_uri_for_shop($i)
  {
    return $this->appconfig('webserver')['urlpath']."/public/shops/shop$i";
  }
	private function create_dirs()
	{
    $i = $this->config->get('step_shop_index');
    # Start with the second shop, as the first one was
    # already created when unzipping the installer!
    if($i==1) return true;
		$this->is_overwritable_target($this->server_path_for_shop($i));
		$this->create_dir($this->server_path_for_shop($i), $this->overwrite_targets());

		$this->append_status_message("Successfully created the directories.");
		$this->set_status_code(MainController::SUCCESS);
	}
  private function first_shop_index()
  {
    return 1;
  }
	private function is_overwritable_target($td)
	{
		if(file_exists($td)) {
      if(!$this->overwrite_targets()) {
			  throw new Exception("Target dir '$td' already exists and overwriting is not allowed.", 1);
      }
      $this->fs->remove($td);
		}
	}
	private function overwrite_targets()
	{
		if($this->config->get('overwrite_targets')=='overwrite_targets') {
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
			$this->set_status_code(MainController::ERROR);
			$this->append_status_message("Could not create dir '$td' ".$error['message']);
			return false;
		}
		return true;
	}
	private function src_zip_file()
	{
		$rv  = $this->presta_source_dir();
		$rv .= '/'.$this->config->get('presta_version').'.unzipped';
		$rv .= '/prestashop.zip';
		return $rv;
	}
  private function presta_source_dir()
  {
    return $this->appconfig('presta_versions_download_dir');
  }
}
?>