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
        return $this->smarty;
    }
    protected function templatedir()
    {
        error_log(dirname(dirname(__FILE__)));
        return dirname(dirname(__FILE__));
    }
}
?>