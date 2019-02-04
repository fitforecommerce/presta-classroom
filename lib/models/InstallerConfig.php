<?php

class InstallerConfig
{
  use DataTrait;
  use ConfigTrait;

  public static function default_data()
  {
    return array(
      'number_of_installations' => 10,
      'server_path'             => '',
      'overwrite_targets'       => false,
      'presta_source_dir'       => '',
      'presta_version'          => '1.7.4.4',
      'first_shop_index'        => 1,
      'domain'                  => 'localhost',
      'language'                => 'en',
      'country'                 => 'de',
      'firstname'               => 'John',
      'lastname'                => 'Doe',
      'email'                   => 'john@example.org',
      'password'                => 'abcdefg'
    );
  }
  public function __construct($user_data = NULL) 
  {
    if(!isset($user_data)) $user_data = array();
    $this->data = array_merge(InstallerConfig::default_data(), $user_data);
  }
  public function db_name_for_index($i)
  {
    return $this->appconfigdata()['dbconfig']['database'].'_shop_'.$i;
  }
}
?>