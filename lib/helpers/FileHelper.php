<?php

class FileHelper {

    use StatusTrait;

	public function assert_dir_exists($target_path)
	{
		if(file_exists($target_path)) return true;
		try {
			$this->mkdir($target_path);
			return true;
		} catch (Exception $e) {
			error_log("ERROR when asserting dir $target_path:\n" . $e);
			$this->set_status_message("<p>Could not create dir in $target_path got error <code>$e</code></p>");
			$this->set_status_code = (MainController::ERROR);
		}
	}
  public function mkdir($target_path)
  {
    mkdir($target_path);
  }
	public function assert_dir_writable($dir_path)
	{
		if(!is_writable($dir_path)) {
			$this->set_status_message("<p>Dir " . $dir_path . " is not writable. Check permission rights.");
			$this->set_status_code(MainController::ERROR);
			return false;
		}
		return true;
	}
  public function remove($target_path)
  {
    try {
      $cmd = "rm -rf '".$target_path."'";
      system($cmd, $stat);
    } catch (Exception $e) {
			error_log("ERROR when removing file $target_path:\n" . $e);
			$this->set_status_message("<p>Could not delete file in '$target_path' got error <code>$e</code></p>");
			$this->set_status_code = (MainController::ERROR);
    }
  }
  public function rename($src_path, $target_path)
  {
    try {
      $cmd = "mv '".$src_path."' '".$target_path."'";
      system($cmd, $stat);
    } catch (Exception $e) {
			error_log("ERROR when renaming $src_path -> $target_path:\n" . $e);
			$this->set_status_message("<p>Could not rename file '$src_path' to '$target_path'</p>");
			$this->set_status_code = (MainController::ERROR);
    }
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
			# $this->set_status_message("<p>Error unzipping $src to $target</p>");
			# $this->set_status_code(MainController::ERROR);
			return false;
		}
	}
  public function system_xcopy($src, $dst)
  {
    if(substr($src, -1)!=='/' && substr($src, -1)!=='.' && substr($src, -2)!=='/.') $src .= '/.';
    if(substr($dst, -1)!=='/') $dst .= '/';

    $cmd = "cp -R \"$src\" \"$dst\"";
    exec($cmd, $stat);

    if(is_array($stat) && count($stat) == 0) {
      return true;
    }
    error_log("system_xcopy error for cmd $cmd");
    error_log("\t".print_r($stat, true));
    return false;
  }
  public function xcopy($src, $dst)
  {
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
      if (( $file != '.' ) && ( $file != '..' )) { 
        if ( is_dir($src . '/' . $file) ) { 
          $this->xcopy($src . '/' . $file,$dst . '/' . $file); 
        } 
        else { 
          copy($src . '/' . $file,$dst . '/' . $file); 
        } 
      } 
    } 
    closedir($dir);
  }
}