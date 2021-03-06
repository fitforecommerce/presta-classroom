<?php

function ajax_error_handler($code, $message, $file, $line) 
{
  $stat = json_encode([
    'error'   => true,
    'message' => '<p>Fatal error caught <code>'.$code."\n".$message."\nin file: $line::$file</code>",
    'lastStepId' => $_POST['stepId'],
    'stepsTotal' => 'undefined',
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
    error_log("InstallerController::execute START ".time());
    # set config from post form values
    $this->installer_config()->set_from_post();
    $this->s()->assign('script', $this->install_jssource());
    $this->s()->display('install/ajax_execute.tpl');
    error_log("InstallerController::execute END ".time());
  }
  public function ajax_execute()
  {
    set_error_handler('ajax_error_handler');
    # register_shutdown_function('fatalErrorShutdownHandler');

    global $_POST, $_REQUEST;
    $req = $_POST;
    # error_log('InstallerController::ajax_execute(): ' . print_r($req, true));
    if(function_exists('xdebug_disable')){
      xdebug_disable();
    }

    try {
      $this->installer_config()->set_from_post('installer_config');
      $step = $req['stepId'];
      $step_shop_index = $req['stepShopIndex'];
      $steps_total = count($this->install_steps());

      $this->installer_config()->set('step_shop_index', $req['stepShopIndex']);
      $this->installer_config()->set('steps_total', $steps_total);

      # error_log('InstallerController::ajax_execute(): ' . print_r($this->installer_config(), true));

      $action = $this->install_steps()[$step]['action'];
      error_log("InstallerController:: execute action -> '$action' START ".time());

      # error_log('InstallerController::ajax_execute(): installer_config ' . print_r($this->installer_config(), true));
      $installer = new Installer($this->installer_config());
      $installer->$action();
    } catch (Exception $e) {
      $stat = json_encode([
        'error'   => true,
        'message' => '<p>Error in step '.$step.': '.nl2br($e->getMessage()).'',
        'lastStepId' => $step,
        'lastShopId' => $step_shop_index,
        'stepsTotal' => $steps_total,
        'stepShopIndex' => $step_shop_index
      ]);
        echo $stat;
        exit();
    }

    $stat = json_encode([
      'error'   => false,
      'message' => 'Action '.$action.' done',
      'lastStepId' => $step + 1,
      'stepsTotal' => $steps_total,
      'stepShopIndex' => $step_shop_index
    ]);
    echo $stat;
    error_log("InstallerController:: execute action -> '$action' END ".time());
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
      [ 'ui_string' => 'Make sure the shop directory exists',
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
    error_log($script_src);
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
    $ic->set(
      'base_path',
      $this->base_path()
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