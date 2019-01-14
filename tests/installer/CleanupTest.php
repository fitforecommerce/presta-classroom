<?php

require_once(dirname(__FILE__).'/../../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class CleanupTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->tdir = 'public/testshops';
        system("rm -rf $this->tdir");
    }

    public function testCleanedUp()
    {
      ini_set('max_execution_time', 60);
      system("mkdir $this->tdir");
      $this->assertFileExists($this->tdir);

      for ($i=1; $i < 4; $i++) { 
        system("mkdir $this->tdir/shop$i");
        system("mkdir $this->tdir/shop$i/install");
        system("mkdir $this->tdir/shop$i/admin");
      }

      $c = new InstallerConfig();
      $c->set('server_path', $this->tdir);
      $c->set('number_of_installations', 3);

      $r = new Installer($c);
      $r->cleanup();

      for ($i=1; $i < 4; $i++) { 
        $this->assertFileNotExists($this->tdir."/shop$i/install");
        $this->assertFileNotExists($this->tdir."/shop$i/admin");
        $this->assertFileExists($this->tdir."/shop$i/admin123");
      }
    }
}