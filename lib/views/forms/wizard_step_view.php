<?php
class WizardStepView extends View
{
  function __construct($vcfg) 
  {
    parent::__construct($vcfg);
    if(!isset($this->vconfig['content_views']) || !is_array($this->vconfig['content_views'])) {
      $this->vconfig['content_views'] = array();
    }
  }
  public function to_s()
  {
    $rv  = '<div id="step-'.$this->vconfig['step_index'].'">';
    $rv .= '<h2>'.$this->vconfig['step_title'].'</h2>';
    $rv .= $this->helptext();
    $rv .= '<div id="form-step-'.($this->vconfig['step_index']-1).'" ';
    $rv .= 'role="form" data-toggle="validator">';
    $rv .= $this->content_to_s();
    $rv .= '</div></div>';
    return $rv;
  }
  public function add_content_view($nview)
  {
   array_push($this->vconfig['content_views'], $nview);
  }
  protected function helptext()
  {
    if(!isset($this->vconfig['helptext'])) return '';
    $rv  = '<p class="text-muted">';
    $rv .= $this->vconfig['helptext'].'</p>';
    return $rv;
  }
  private function content_to_s()
  {
    $rv = '';
    foreach ($this->content_views() as $key => $v) {
      $rv .= $v->to_s();
    }
    return $rv;
  }
  private function content_views()
  {
    return $this->vconfig['content_views'];
  }
}
?>