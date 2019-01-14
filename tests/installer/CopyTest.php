<?php

require_once(dirname(__FILE__).'/../../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class CopyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->tdir = 'public/testshops';
        system("rm -rf $this->tdir");
    }

    public function testInstallersCopied()
    {
      ini_set('max_execution_time', 60);

      $c = new InstallerConfig();
      $c->set('server_path', $this->tdir);
      $c->set('number_of_installations', 3);

      $r = new Installer($c);
      $r->assert_dirs();
      $r->unzip_src();
      $r->copy_folders();

      $this->assertFileExists($this->tdir);
      for ($i=1; $i < 4; $i++) { 
        $this->assertFileExists($this->tdir."/shop$i");
        $this->assertFileExists($this->tdir."/shop$i/install");
        $this->assertFileExists($this->tdir."/shop$i/admin");
        $this->assertFileExists($this->tdir."/shop$i/init.php");
        $this->assertFileExists($this->tdir."/shop$i/index.php");
      }
    }
}