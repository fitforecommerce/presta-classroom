<?php
class CheckboxView extends FormGroupView
{
  public function to_s()
  {
    $rv  = '<div class="form-group container">';
    $rv .= '<div class="row text-right">';
    $rv .= '<div class="col col-md-10">'.$this->label().'</div>';
    $rv .= $this->input();

    $rv .= '</div></div>';
    return $rv;
  }
  public function input()
  {
    $rv  = '<div class="col-md-2"><input class="form-control" ';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr();
    $rv .= 'type="checkbox" ';
    # $rv .= 'value="'.$this->vconfig['value'].'" ';
    $rv .= 'value="'.$this->vconfig['name'].'" ';
    $rv .= $this->required_attr();
    $rv .= '></div>';
    return $rv;
  }
}
?>