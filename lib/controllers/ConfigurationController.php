<?php

class ConfigurationController extends MainController
{

  use ConfigTrait;

  protected function default_action()
  {
    return 'configure';
  }

  public function configure()
  {
    $content  = "<p>ConfigurationController:: do the config</p>";
    $content .= $this->test_db();

    $this->s()->assign('title', "Configuration");
    $this->s()->assign('content', $content);
    $this->s()->display('main.tpl');
    return true;    
  }
  public function execute($config)
  {
    # $config->setPrestaSourceDir($this->getParameter('default_shops_dir'));
    # 
    # $installer = $this->get('app.installer');
    # $installer->set_config($config);
    # $installer->run();
  }
  private function test_db()
  {
    $dbconfig = $this->appconfig('dbconfig');
    $rv = '<p>'.print_r($dbconfig, true).'</p>';
    $db = new MysqliDb ($dbconfig['host'], $dbconfig['user'], 
      $dbconfig['password'], $dbconfig['database']);
    $users = $db->rawQuery('show databases;');
    $rv .= '<p>databases: '.print_r($users, true).'</p>';
    return $rv;
  }
}
?>