<?php

require_once(dirname(__FILE__).'/../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class InstallerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
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
      $tdir = 'public/testshops';
      system("rm -rf $tdir");

      $this->assertFileNotExists($tdir);

      $c = new InstallerConfig();
      $c->set('server_path', $tdir);

      $r = new Installer($c);
      $r->assert_dirs();

      $this->assertFileExists($tdir);
    }
    public function testInstallersUnzipped()
    {
      ini_set('max_execution_time', 60);
      $tdir = 'public/testshops';
      system("rm -rf $tdir");

      $c = new InstallerConfig();
      $c->set('server_path', $tdir);
      $c->set('number_of_installations', 1);

      $r = new Installer($c);
      $r->assert_dirs();
      $r->unzip_src();

      $this->assertFileExists($tdir);
      $this->assertFileExists($tdir.'/shop1');
      $this->assertFileExists($tdir.'/shop1/install');
      $this->assertFileExists($tdir.'/shop1/admin');
      $this->assertFileExists($tdir.'/shop1/init.php');
      $this->assertFileExists($tdir.'/shop1/index.php');
    }
    public function testInstallersCopied()
    {
      ini_set('max_execution_time', 60);
      $tdir = 'public/testshops';
      system("rm -rf $tdir");

      $c = new InstallerConfig();
      $c->set('server_path', $tdir);
      $c->set('number_of_installations', 3);

      $r = new Installer($c);
      $r->assert_dirs();
      $r->unzip_src();
      $r->copy_folders();

      $this->assertFileExists($tdir);
      for ($i=1; $i < 4; $i++) { 
        $this->assertFileExists($tdir."/shop$i");
        $this->assertFileExists($tdir."/shop$i/install");
        $this->assertFileExists($tdir."/shop$i/admin");
        $this->assertFileExists($tdir."/shop$i/init.php");
        $this->assertFileExists($tdir."/shop$i/index.php");
      }
    }
}