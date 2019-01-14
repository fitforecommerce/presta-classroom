<?php
#  $pini = readfile('/usr/local/etc/php/conf.d/php.ini');
# phpinfo();
# exit();
ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
ini_set('max_execution_time', 5);

require_once(dirname(__FILE__).'/../lib/include.inc.php');

error_log("******* new request ********");
# error_log(system("ls -a .."));
# error_log("SERVER: " . print_r($_SERVER, true));
# error_log("GET: " . print_r($_GET, true));
# error_log("POST: ".print_r($_POST, true));

$mc = Router::from_request();
$mc->run_controller();

error_log("*********************************************\n\n\n\n\n\n\n\n");
exit();
?>