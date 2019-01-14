<?php
class DatefieldView extends FormGroupView
{
  public function input()
  {
    $rv  = '<input class="form-control" ';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr();
    $rv .= 'maxlength="10" ';
    $rv .= 'type="date" ';
    $rv .= 'placeholder="TT.MM.JJJJ" ';
    $rv .= 'pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}" ';
    $rv .= 'value="'.$this->vconfig['value'].'" ';
    $rv .= $this->required_attr();
    $rv .= '>';
    return $rv;
  }
}
?>