<?php
class InstallerConfigFormView extends View
{

    private $config;

    function __construct($ninst_config) {
        $this->config = $ninst_config;
    }
    public function to_s()
    {
        $rv  = '<form action="'.$this->config->get('base_path').'/install/execute" method="POST">';
        foreach ($this->setup_views() as $v) {
            $rv .= $v->to_s();
        }
        $rv .= '<input class="btn btn-primary" type="submit" value="Send" name="Send" id="Send">';
        $rv .= '</form>';
        return $rv;
    }
    private function views()
    {
        if(isset($this->views)) return $this->views;
        return $this->setup_views();
    }
    private function setup_views()
    {
        # 'number_of_installations' => 10,
        # 'server_path' => '',
        # 'overwrite_targets' => false,
        # 'web_root_url' => 'localhost',
        # 'presta_source_dir' => '',
        # 'presta_version' => '1.7.4.4',
        # 'first_shop_index' => 0
        $vs   = [];
        $vs[] = new NumberView(array(
            'name' => 'number_of_installations',
            'id' => 'number_of_installations',
            'required' => true,
            'value' => $this->config->get('number_of_installations'),
            'label' => 'Number of installations'
        ));
        $vs[] = new TextfieldView(array(
            'name' => 'server_path',
            'id' => 'server_path',
            'required' => true,
            'value' => $this->config->get('server_path'),
            'label' => 'Server path'
        ));
        $vs[] = new CheckboxView(array(
            'name' => 'overwrite_targets',
            'id' => 'overwrite_targets',
            'required' => false,
            'value' => $this->config->get('overwrite_targets'),
            'label' => 'Overwrite Targets'
        ));
        $vs[] = new SelectView(array(
            'name' => 'presta_version',
            'id' => 'presta_version',
            'required' => true,
            'value' => $this->config->get('presta_version'),
            'options' => $this->available_presta_versions(),
            'label' => 'Install version'
        ));
        $this->views = $vs;
        return $this->views;
    }
    private function available_presta_versions()
    {
      $versions = $this->downloader()->available_versions();
      error_log(print_r($versions, true));
      $rv = [];
      foreach ($versions as $k => $val) {
        array_push($rv, array('text' => "Prestashop $k", 'value' => $k));
      }
      return $rv;
    }
    private function downloader()
    {
      if(!isset($this->downloader)) $this->downloader = new Downloader();
      return $this->downloader;
    }
}
?>