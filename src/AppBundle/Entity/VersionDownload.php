<?php
namespace AppBundle\Entity;

class VersionDownload {

	function __construct($ndata, $target_path) {
		$this->data = $ndata;
    $this->target_path = $target_path;
	}
	public function is_downloaded()
	{
    error_log("VersionDownload ".$this->download_target_path());
		return file_exists($this->download_target_path());
	}
  public function version()
  {
    return $this->data['version'];
  }
  private function download_target_path()
  {
    return $this->target_path.'/'.$this->data['version'].'.zip';
  }
}
?>