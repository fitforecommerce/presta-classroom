<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileHelper extends Filesystem {

	use \AppBundle\Traits\StatusTrait;

	public function assert_dir_exists($target_path)
	{
		if($this->exists($target_path)) return true;
		try {
			$this->mkdir($target_path);
			return true;
		} catch (Exception $e) {
			error_log("ERROR when asserting dir $target_path:\n" . $e);
			$this->set_status_message("<p>Could not create dir in $target_path got error <code>$e</code></p>");
			$this->set_status_code = (DefaultController::ERROR);
			throw $e;
		}
	}
	public function assert_dir_writable($dir_path)
	{
		if(!is_writable($dir_path)) {
			$this->set_status_message("<p>Dir " . $dir_path . " is not writable. Check permission rights.");
			$this->set_status_code(DefaultController::ERROR);
			return false;
		}
		return true;
	}
	
}