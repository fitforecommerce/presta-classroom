<?php
namespace AppBundle\Utils;

use AppBundle\Controller\DefaultController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use \ZipArchive;

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
	public function unzip($src, $target)
	{
		$zip = new ZipArchive;
		if ($zip->open($src) === TRUE) {
		    $zip->extractTo($target);
		    $zip->close();
			return true;
		} else {
			error_log("ERROR IN FileHelper when unziping $src to $target");
			$this->set_status_message("<p>Error unzipping $src to $target</p>");
			$this->set_status_code(DefaultController::ERROR);
			return false;
		}
	}
	/**
	 * Copy a file, or recursively copy a folder and its contents
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.0.1
	 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
	 * @param       string   $source    Source path
	 * @param       string   $dest      Destination path
	 * @param       int      $permissions New folder creation permissions
	 * @return      bool     Returns true on success, false on failure
	 */
	public function xcopy($source, $dest, $permissions = 0755)
	{
		error_log("FileHelper::xcopy $source to $dest");
	    // Check for symlinks
	    if (is_link($source)) {
	        return symlink(readlink($source), $dest);
	    }

	    // Simple copy for a file
	    if (is_file($source)) {
	        return copy($source, $dest);
	    }

	    // Make destination directory
	    if (!is_dir($dest)) {
	        mkdir($dest, $permissions);
	    }

	    // Loop through the folder
	    $dir = dir($source);
	    while (false !== $entry = $dir->read()) {
	        // Skip pointers
	        if ($entry == '.' || $entry == '..') {
	            continue;
	        }

	        // Deep copy directories
	        $this->xcopy("$source/$entry", "$dest/$entry", $permissions);
	    }
		return true;
	}
}