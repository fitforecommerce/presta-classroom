<?php
class DatabaseInstaller {

	use StatusTrait;
  use ConfigTrait;

	private $config;

	public function __construct($nconfig)
	{
    if(!$nconfig instanceof InstallerConfig) {
      throw new Exception("Null config passed to Installer::__construct()", 1);
    }

    $this->config = $nconfig;
    $this->setup_db();
    
	}
	public function run()
	{
    $this->create_databases();
    $this->create_users();
    return $this->status();
	}
  private function create_databases()
  {
    $stats = [];
    $fi = $this->first_shop_index();
    for ($i = $fi; $i < $fi + $this->config->get('number_of_installations'); $i++) {
      $dbname = $this->db_name_for_index($i);
      $q = "CREATE DATABASE IF NOT EXISTS $dbname";
      $stats[] = $this->db->rawQuery($q);
    }
    error_log("DatabaseInstaller::create_databases stats: ".print_r($stats, true));
    return $stats;
  }
  private function create_users()
  {
    $stats = [];
    $fi = $this->first_shop_index();
    for ($i = $fi; $i < $fi + $this->config->get('number_of_installations'); $i++) {
      $dbname = $this->db_name_for_index($i);
      $new_pwd = $this->random_password();
      $ql = "CREATE USER '$dbname'@'localhost' IDENTIFIED BY '$new_pwd'; ";
      $qe = "CREATE USER '$dbname'@'%' IDENTIFIED BY '$new_pwd'; ";
      $stats[$i]['ql'] = $this->db->rawQuery($ql);
      $stats[$i]['qe'] = $this->db->rawQuery($qe);
      $stats[$i]['pw'] = $new_pwd;
    }
    error_log("DatabaseInstaller::create_databases stats: ".print_r($stats, true));
    return $stats;
  }
  private function first_shop_index()
  {
    return 1;
  }
  private function setup_db()
  {
    $dbconfig = $this->appconfig('dbconfig');
    $this->db = new MysqliDb ($dbconfig['host'], $dbconfig['user'], 
      $dbconfig['password'], $dbconfig['database']);
  }
  private function db_name_for_index($i)
  {
    return 'shops_'.$i;
  }
  private function random_password()
  {
    return LoginController::createPassword(8);
  }
}
?>