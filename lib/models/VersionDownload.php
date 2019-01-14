<?php

class VersionDownload {

    use ConfigTrait;

	function __construct($ndata) {
		$this->data = $ndata;
	}
    public function __get($key)
    {
        if($key=='is_downloaded') return $this->is_downloaded();
        return $this->data[$key];
    }
	public function is_downloaded()
	{
		$arDir = array();
		$this->assert_download_dir();
		$d = dir($this->downloads_dir());
		while (false !== ($entry = $d->read())) {
            $regex = '/'.$this->data['version'].'\.'.Downloader::unzipped_extension().'/';
			if(preg_match($regex, $entry)==1) array_push($arDir, $entry);
		}
		$d->close();
        # echo "<p>VersionDownload::is_downloaded() ".print_r($arDir, true)."</p>";
		return $arDir;
	}
    private function assert_download_dir()
    {
        $this->fs()->assert_dir_exists($this->downloads_dir());
    }
    private function fs()
    {
      if(!isset($this->filehelper)) $this->filehelper = new FileHelper();
      return $this->filehelper;
    }
    private function downloads_dir()
    {
        return $this->appconfig('presta_versions_download_dir');
    }
    private function download_target_path()
    {
      return $this->downloads_dir().'/'.$this->data['version'].'.zip';
    }
}
?>