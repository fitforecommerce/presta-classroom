<?php
class TextfieldView extends FormGroupView
{
  public function input()
  {
    $rv  = '<input class="form-control" ';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr();
    $rv .= $this->maxlength_attr();
    $rv .= $this->type_attr();
    $rv .= 'value="'.$this->vconfig['value'].'" ';
    $rv .= $this->required_attr();
    $rv .= '>';
    return $rv;
  }

  protected function maxlength_attr()
  {
    if(isset($this->vconfig['maxlength'])) {
      return 'maxlength="'.$this->vconfig['maxlength'].'" ';
    }
    return '';
  }
  protected function type_attr()
  {
    return 'type="text" ';
  }
}
?>