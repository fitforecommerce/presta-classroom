<?php
class DownloadController extends MainController
{
  protected function default_action()
  {
    return 'index';
  }
    
  public function index()
  {
    $this->s()->assign('available_downloads', $this->downloader()->available_versions());
    $this->s()->display('download/index.tpl');
  }
  public function download()
  {
    $version = $this->router->params()['version'];
    $this->downloader()->download($version);
    $this->s()->assign('status', $this->downloader()->status());
    $this->s()->assign('version', $version);
    $this->s()->assign('available_downloads', $this->downloader()->available_versions());
    $this->s()->display('download/index.tpl');
  }
  private function downloader()
  {
    if(!isset($this->downloader)) $this->downloader = new Downloader();
    return $this->downloader;
  }
}
?>