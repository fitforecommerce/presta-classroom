<?php
class FormGroupView extends InputView
{
  function __construct($vcfg) {
    parent::__construct($vcfg);
    global $_REQUEST;
    if(!isset($this->vconfig['value'])) {
      $fid = $this->vconfig['name'];
      if(isset($_REQUEST[$fid])) {
        $this->vconfig['value'] = $_REQUEST[$fid];
      } else {
        $this->vconfig['value'] = '';
      }
    }
  }
  public function to_s()
  {
    $rv  = '<div class="form-group">';
    $rv .= $this->label();
    $rv .= $this->input();
    $rv .= $this->helptext();
    $rv .= '</div>';
    return $rv;
  }
  public function input()
  {
    return '<p>Default output from FormGroupView</p>';
  }
  protected function label()
  {
    $rv  = '<label ';
    $rv .= $this->attr('for', $this->vconfig['id']);
    $rv .= $this->required_css();
    $rv .= '>'.$this->vconfig['label'];
    $rv .= '</label>';
    return $rv;
  }
  protected function helptext()
  {
    if(!isset($this->vconfig['helptext'])) return '';
    $rv  = '<small id="emailHelp" class="form-text text-muted">';
    $rv .= $this->vconfig['helptext'].'</small>';
    return $rv;
  }
}
?>