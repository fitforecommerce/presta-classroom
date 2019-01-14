<?php

require_once(dirname(__FILE__).'/../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class InstallerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->tdir = 'public/testshops';
        system("rm -rf $this->tdir");
    }
    /**
     * @expectedException Exception
     */
    public function testNullConfigException()
    {
      $this->expectException(new Installer($empty_config));
    }
    public function testInstallerCreated()
    {
      $empty_config = new InstallerConfig();
      $r = new Installer($empty_config);
      $this->assertInstanceOf(Installer::class, $r);
    }
    public function testShopDirsCreated()
    {
      $this->assertFileNotExists($this->tdir);

      $c = new InstallerConfig();
      $c->set('server_path', $this->tdir);

      $r = new Installer($c);
      $r->assert_dirs();

      $this->assertFileExists($this->tdir);
    }
    public function testInstallersUnzipped()
    {
      ini_set('max_execution_time', 60);

      $c = new InstallerConfig();
      $c->set('server_path', $this->tdir);
      $c->set('number_of_installations', 1);

      $r = new Installer($c);
      $r->assert_dirs();
      $r->unzip_src();

      $this->assertFileExists($this->tdir);
      $this->assertFileExists($this->tdir.'/shop1');
      $this->assertFileExists($this->tdir.'/shop1/install');
      $this->assertFileExists($this->tdir.'/shop1/admin');
      $this->assertFileExists($this->tdir.'/shop1/init.php');
      $this->assertFileExists($this->tdir.'/shop1/index.php');
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
    public function testCLIInstallerRun()
    {
      
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