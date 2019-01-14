<?php
class RadiofieldView extends FormGroupView
{
  public function to_s()
  {
    $rv  = '<div class="form-group">';
    $rv .= '<span '.$this->required_css().'>'.$this->vconfig['label'].'</span>';
    foreach ($this->vconfig['options'] as $i => $v) {
      $rv .= '<div class="form-check">';
      $rv .= $this->radio_input($i + 1, $v['value']);
      $rv .= $this->radio_label($i + 1, $v['text']);
      $rv .= '</div>';
    }
    $rv .= '</div>';
    return $rv;
  }

  private function radio_input($i, $v)
  {
    $checked = $i==$this->default_index() ? true : false;
    $rv  = '<input class="form-check-input" type="radio" ';
    $rv .= 'value="'.$v.'"';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr($i);
    $rv .= $this->checked_attr($v, $checked);
    $rv .= '>';
    return $rv;
  }
  private function radio_label($i, $txt)
  {
    $rv  = '<label class="form-check-label" ';
    $rv .= 'for="'.$this->vconfig['id'].$i.'">';
    $rv .= $txt.'</label>';
    return $rv;
  }
  private function checked_attr($val, $is_default=false)
  {
    if($this->vconfig['value']=='' && $is_default){ return 'checked '; }
    if($val==$this->vconfig['value']) return 'checked ';
    return '';
  }
  private function default_index()
  {
    if(isset($this->vconfig['default_index'])) {
      return $this->vconfig['default_index'];
    }
    return 1;
  }
}
?>