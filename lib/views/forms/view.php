<?php
class View
{
  var $vconfig;

  function __construct($vcfg) {
    $this->vconfig = $vcfg;
  }
  public function to_s()
  {
    return $this->vconfig['text'];
  }

  protected function set_value_from_request()
  {
    global $_REQUEST;
    if(!isset($this->vconfig['value'])) {
      $fid = $this->vconfig['name'];
      $this->vconfig['value'] = $_REQUEST[$fid];
    }
  }
  protected function attr($k, $v)
  {
    return $k.'="'.$v.'" ';
  }
}
?>