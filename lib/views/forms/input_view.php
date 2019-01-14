<?php
class InputView extends View
{
  protected function name_attr($add='')
  {
    return 'name="'.$this->vconfig['name'].'" ';
  }
  protected function id_attr($add='')
  {
    return 'id="'.$this->vconfig['id'].$add.'" ';
  }
  protected function required_attr()
  {
    if($this->vconfig['required']) { return 'required '; }
    return '';
  }

  protected function required_css()
  {
    if($this->vconfig['required']) {
      return $this->attr('class', 'required');
    }
  }
}
?>