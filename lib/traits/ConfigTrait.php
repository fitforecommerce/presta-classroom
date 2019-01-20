<?php
trait ConfigTrait {

    protected function libdir()
    {
        return dirname(dirname(__FILE__));
    }
    protected function publicdir()
    {
        return realpath(dirname(__FILE__)."/../..")."/public";
    }
    public function appconfig($key)
    {
        if($key == 'presta_versions_download_dir' || $key == 'shops_install_dir') {
            return $this->publicdir().$this->appconfigdata()[$key];
        }
        return $this->appconfigdata()[$key];
    }
    private function appconfigdata()
    {
        if(!isset($this->appconfig)) {
            $this->appconfig = spyc_load_file($this->libdir().'/config/config.yml');
        }
        return $this->appconfig;
    }
}
?>