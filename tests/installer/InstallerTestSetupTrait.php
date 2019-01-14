<?php
trait InstallerTestSetupTrait {
  public function setUp()
  {
      parent::setUp();
      $this->tdir = 'public/testshops';
      system("rm -rf $this->tdir");
  }
}
?>