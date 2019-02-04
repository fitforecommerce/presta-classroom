<?php

trait SmartyTrait {

    protected $smarty;

    protected function s()
    {
        return $this->smarty();
    }
    protected function smarty()
    {
        if(isset($this->smarty)) return $this->smarty;
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($this->templatedir().'/templates/');
        $this->s()->assign('baseurl', $this->webroot());
        return $this->smarty;
    }
    protected function webroot()
    {
      $s = $this->appconfigdata()['webserver'];
      return 'http://'.$s['host'].':'.$s['port'].$s['urlpath'];
    }
    protected function templatedir()
    {
        # error_log(dirname(dirname(__FILE__)));
        return dirname(dirname(__FILE__));
    }
}
?>