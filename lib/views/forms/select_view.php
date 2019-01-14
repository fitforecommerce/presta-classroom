<?php
class SelectView extends FormGroupView
{
  public function input()
  {
    $rv  = '<select ';
    $rv .= $this->name_attr();
    $rv .= $this->id_attr();
    $rv .= $this->required_attr();
    $rv .= 'class="form-control" ';
    $rv .= '>';
    $i=0;
    foreach ($this->options_data() as $v) {
      $rv .= $this->option_tag($v['value'], $v['text'], $i);
      $i++;
    }
    $rv .= '</select>';
    return $rv;
  }

  protected function options_data()
  {
    return $this->vconfig['options'];
  }

  private function option_tag($val, $txt, $i)
  {
    if(!isset($val)) $val = $txt;
    $rv  = '<option value="'.$val.'"';
    $rv .= $this->select_attr($val, $i);
    $rv .= '>';
    $rv .= $txt.'</option>';
    return $rv;
  }
  private function select_attr($testval, $i)
  {
    if($this->vconfig['value']==$testval) return 'selected';
    if($this->vconfig['value']=='' && $i==0) return 'selected';
    return '';
  }
}
?>