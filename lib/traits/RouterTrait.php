<?php
trait RouterTrait
{
  public function www_root_path()
  {
    return $this->router()->www_root_path();
  }
  public function base_path()
  {
    return $this->router()->base_path();
  }
  private function router()
  {
      if(!isset($this->router)) $this->router = Router::from_request();
      return $this->router;
  }
}
?>