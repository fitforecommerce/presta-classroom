<?php
class EmailfieldView extends FormGroupView
{
  public function input()
  {
    $rv  = '<input class="form-control" ';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr();
    $rv .= 'size="47" ';
    $rv .= 'type="email" ';
    $rv .= 'value="'.$this->vconfig['value'].'" ';
    $rv .= $this->required_attr();
    $rv .= '>';
    return $rv;
  }
}
?>