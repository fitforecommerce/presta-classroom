<?php

function ajax_error_handler($code, $message, $file, $line) 
{
  $stat = json_encode([
    'error'   => true,
    'message' => '<p>Fatal error caught <code>'.$code."\n".$message."\nin file: $line::$file</code>",
    'lastId' => $_POST['startId'],
    'numFiles' => 'undefined',
    'postdata' => $_POST
  ]);
}

class InstallerController extends MainController
{

  use ConfigTrait;

  private $installer_config;

  protected function default_action()
  {
    return 'configure';
  }

  public function configure()
  {
    $fv = new InstallerConfigFormView($this->installer_config());
    $this->s()->assign('content', $fv->to_s());
    $this->s()->display('main.tpl');
    return true;
  }
  public function execute()
  {
    # set config from post form values
    $this->installer_config()->set_from_post();
    $this->s()->assign('script', $this->install_jssource());
    $this->s()->display('install/ajax_execute.tpl');
  }
  public function ajax_execute()
  {
    set_error_handler('ajax_error_handler');
    # register_shutdown_function('fatalErrorShutdownHandler');

    global $_POST;
    error_log('InstallerController::ajax_execute(): ' . print_r($_POST, true));
    if(function_exists('xdebug_disable')){
      xdebug_disable();
    }

    $step = $_POST['startId'];
    $steps_total = count($this->install_steps());

    $action = $this->install_steps()[$step]['action'];

    if($action!='setup_db') return true;

    try {
      $this->installer_config()->set_from_post('installer_config');
      error_log('InstallerController::ajax_execute(): installer_config ' . print_r($this->installer_config(), true));
      $installer = new Installer($this->installer_config());
      $installer->$action();
    } catch (Exception $e) {
      $stat = json_encode([
        'error'   => true,
        'message' => '<p>Error in step '.$step.': <code>'.$e->getMessage().'</code>',
        'lastId' => $step,
        'numFiles' => $steps_total
      ]);
        echo $stat;
        exit();
    }

    $stat = json_encode([
      'error'   => false,
      'message' => 'Action '.$action.' done',
      'lastId' => $step + 1,
      'numFiles' => $steps_total
    ]);
    echo $stat;
    return true;
  }
  public function done()
  {
    # $this->s()->assign('script', $script_src);
    $this->s()->display('install/done.tpl');
  }
  private function install_steps()
  {
    return array(
      [ 'ui_string' => 'Set up database',
        'ui_hint' => '',
        'action' => 'setup_db'
      ],
      [ 'ui_string' => 'Make sure directories exist',
        'ui_hint' => '',
        'action' => 'assert_dirs'
      ],
      [ 'ui_string' => 'Unzip the installer',
        'ui_hint' => 'This step might take a few minutes to complete…',
        'action' => 'unzip_src'
      ],
      [ 'ui_string' => 'Copy shop folders',
        'ui_hint' => 'This step might take a few minutes to complete…',
        'action' => 'copy_folders'
      ],
      [ 'ui_string' => 'Run the Prestashop installers',
        'ui_hint' => 'This step might take a few minutes to complete…',
        'action' => 'run_installers'
      ],
      [ 'ui_string' => 'Clean up the installation files',
        'ui_hint' => 'This step is not yet implemented! Remove install dir and rename admin folder',
        'action' => 'cleanup'
      ]
    );
  }
  private function install_jssource()
  {
    $script_path = realpath(dirname(__FILE__)."/../javascript/ajax_install.js");

    $script_src  = '<script type="text/javascript">';
    $script_src .= 'var installer_config = '.$this->installer_config()->to_json().';';
    $script_src .= 'var action_data = '.json_encode($this->install_steps()).';';
    $script_src .= file_get_contents($script_path);
    $script_src .= '</script>';

    return $script_src;
  }
  private function downloader()
  {
    if(!isset($this->downloader)) $this->downloader = new Downloader();
    return $this->downloader;
  }
  private function installer_config()
  {
    if(isset($this->installer_config)) return $this->installer_config;
    $ic = new InstallerConfig();
    $ic->set(
      'presta_source_dir',
      $this->appconfig('presta_versions_download_dir')
    );
    $ic->set(
      'server_path',
      $this->appconfig('shops_install_dir')
    );
    $ic->set(
      'downloaded_versions',
      $this->versions_choice()
    );
    $this->installer_config = $ic;
    return $this->installer_config;
  }
  private function versions_choice()
  {
    $rv = [];
    $av = $this->downloader()->available_versions();
    foreach ($av as $k => $v) {
      $rv[$v->version] = $v->version;
    }
    $rv = array_reverse($rv);
    return $rv;
  }
}
?>