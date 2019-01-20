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
    $this->create_database();
    return $this->status();
	}
  private function create_database()
  {
    $stats = [];
    $i = $this->config->get('step_shop_index');
    $dbname = $this->db_name_for_index($i);
    $new_pwd = $this->random_password();
    $q = [];
    $q[] = "CREATE DATABASE IF NOT EXISTS $dbname";
    $q[] = "CREATE USER '$dbname'@'localhost' IDENTIFIED BY '$new_pwd'; ";
    $q[] = "CREATE USER '$dbname'@'%' IDENTIFIED BY '$new_pwd'; ";
    $q[] = "GRANT ALL PRIVILEGES ON $dbname.* TO 'username'@'localhost';";
    $q[] = "FLUSH PRIVILEGES;";

    error_log("DatabaseInstaller::create_database");
    error_log("\t$ql");
    error_log("\t$qe");

    foreach ($q as $rq) {
      $stats[] = $this->db->rawQuery($rq);
    }
    $stats['user']      = $dbname;
    $stats['password']  = $new_pwd;

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
    return $this->config->db_name_for_index($i);
  }
  private function random_password()
  {
    return 'testclassroom';
    return LoginController::createPassword(8);
  }
}
?>