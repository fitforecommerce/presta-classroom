<?php
trait DataTrait {

	protected $data;

    public function get($key)
    {
      return $this->data[$key];
    }
    public function __get($key)
    {
      return $this->data[$key];
    }
    public function set($key, $value)
    {
      $this->data[$key] = $value;
    }
    public function __set($key, $value)
    {
      $this->set($key, $value);
    }
    public function set_from_post($postkey=NULL)
    {
      global $_POST;
      if($postkey) {
        return $this->set_from_array($_POST[$postkey]);
      }
      return $this->set_from_array($_POST);
    }
    public function set_from_get()
    {
      global $_GET;
      return $this->set_from_array($_GET);
    }
    public function set_from_request()
    {
      global $_REQUEST;
      return $this->set_from_array($_REQUEST);
    }
    public function to_json()
    {
      return json_encode($this->data);
    }
    protected function set_from_array($darray)
    {
      if(!is_array($darray)) return false;

      foreach ($this->data as $k => $v) {
        if(isset($darray[$k])) {
          $this->data[$k] = $darray[$k];
        }
      }
      return true;
    }
}
?>