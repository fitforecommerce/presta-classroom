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
    $dberror = false;

    $i = $this->config->get('step_shop_index');
    $dbname = $this->db_name_for_index($i);
    $new_pwd = $this->random_password();
    $q = [];
    $q[] = "DROP DATABASE IF EXISTS $dbname";
    $q[] = "CREATE DATABASE IF NOT EXISTS $dbname";
    $q[] = "DROP USER IF EXISTS $dbname";
    $q[] = "CREATE USER IF NOT EXISTS '$dbname'@'localhost' IDENTIFIED BY '$new_pwd'; ";
    $q[] = "CREATE USER IF NOT EXISTS '$dbname'@'%' IDENTIFIED BY '$new_pwd'; ";
    $q[] = "GRANT ALL PRIVILEGES ON $dbname.* TO '$dbname'@'%';";
    $q[] = "GRANT ALL PRIVILEGES ON $dbname.* TO '$dbname'@'localhost';";
    $q[] = "FLUSH PRIVILEGES;";

    error_log("DatabaseInstaller::create_database");
    error_log("\t$ql");
    error_log("\t$qe");

    foreach ($q as $rq) {
      $this->db->rawQuery($rq);
      if($stats[] = $this->db->getLastError()) {
        $dberror = true;
      }
    }
    $stats['user']      = $dbname;
    $stats['password']  = $new_pwd;

    error_log("DatabaseInstaller::create_databases stats: ".print_r($stats, true));
    if($dberror) {
      $errstr  = "Error Processing database requests:\n";
      $errstr .= implode("\n", $stats);
      throw new Exception($errstr, 1);
    }
    return $stats;
  }
  private function first_shop_index()
  {
    return 1;
  }
  private function setup_db()
  {
    $dbconfig = $this->appconfig('dbconfig');
    $this->db = new MysqliDb ($this->db_server(), $dbconfig['user'], 
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
  private function db_server()
  {
    $dbconfig = $this->config->appconfig('dbconfig');
    $rv = $dbconfig['host'];
    if(strlen($dbconfig['port']) >= 4) {
      $rv .= ':'.$dbconfig['port'];
    }
    return $rv;
  }
}
?>