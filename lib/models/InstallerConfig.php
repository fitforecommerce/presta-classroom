<?php

class InstallerConfig
{
  use DataTrait;

  public static function default_data()
  {
    return array(
      'number_of_installations' => 10,
      'server_path' => '',
      'overwrite_targets' => false,
      'web_root_url' => 'localhost',
      'presta_source_dir' => '',
      'presta_version' => '1.7.4.4',
      'shop_index' => 0
    );
  }
  public function __construct($user_data = NULL) 
  {
    if(!isset($user_data)) $user_data = array();
    $this->data = array_merge(InstallerConfig::default_data(), $user_data);
  }
}
?>