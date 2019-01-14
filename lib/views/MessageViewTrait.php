<?php

trait MessageViewTrait {

	private $message_view;
	private $edit_view;

	public function msg($str, $class=MessageView::MESSAGE)
	{
        $this->s()->assign('msg',$this->msg_str($str, $class));
	}
	public function msg_str($str, $class=MessageView::MESSAGE)
	{
		$rv = "<div class='alert $class' role='alert'>";
		$rv .= $this->get_header($class);
		$rv .= $str;
		$rv .= "</div>";
		return $rv;
	}
	protected function msg_view()
	{
		if(!isset($this->message_view)) $this->message_view = new $this->message_view_class();
		return $this->message_view;
	}
	private function get_header($type)
	{
		switch ($type) {
			case MessageView::ERROR:
				$rv = '<h2>Fehler</h2>';
				break;
			case MessageView::SUCCESS:
				$rv = '';
			default:
				$rv = '';
				break;
		}
		return $rv;
	}
    
}
?>