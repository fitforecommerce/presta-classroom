<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('max_execution_time', 5);

$d = dirname(__FILE__);

require_once($d.'/includes/include.inc.php');
require_once($d.'/helpers/mysqlidb.php');

# Traits
require_once($d.'/traits/DataTrait.php');
require_once($d.'/traits/ConfigTrait.php');
require_once($d.'/traits/SmartyTrait.php');
require_once($d.'/traits/StatusTrait.php');
require_once($d.'/traits/RouterTrait.php');

# Forms helpers
require_once($d.'/views/forms/view.php');
require_once($d.'/views/forms/input_view.php');
require_once($d.'/views/forms/form_group_view.php');
require_once($d.'/views/forms/wizard_step_view.php');

require_once($d.'/views/forms/checkbox_view.php');
require_once($d.'/views/forms/datefield_view.php');
require_once($d.'/views/forms/email_view.php');
require_once($d.'/views/forms/emailfield_view.php');
require_once($d.'/views/forms/radiofield_view.php');
require_once($d.'/views/forms/select_view.php');
require_once($d.'/views/forms/textfield_view.php');
require_once($d.'/views/forms/number_view.php');


# Models
require_once($d.'/models/User.php');
require_once($d.'/helpers//FileHelper.php');
require_once($d.'/models/VersionDownload.php');
require_once($d.'/helpers/SessionHelper.php');
require_once($d.'/models//InstallerConfig.php');

# Views
require_once($d.'/views/MessageView.php');
require_once($d.'/views/MessageViewTrait.php');
require_once($d.'/views/InstallerConfigFormView.php');

require_once($d.'/helpers/Downloader.php');

# Controllers
require_once($d.'/controllers/MainController.php');
require_once($d.'/controllers/LoginController.php');
require_once($d.'/controllers/ConfigurationController.php');
require_once($d.'/controllers/DashboardController.php');
require_once($d.'/controllers/DownloadController.php');
require_once($d.'/controllers/InstallerController.php');

# Helpers
require_once($d.'/helpers/Route.php');
require_once($d.'/helpers/Router.php');
require_once($d.'/helpers/RequestParser.php');
require_once($d.'/helpers/Installer.php');
require_once($d.'/helpers/DatabaseInstaller.php');
require_once($d.'/helpers/PrestaCliInstallerRunner.php');

?>