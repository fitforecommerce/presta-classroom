<?php

require_once(dirname(__FILE__).'/../../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class UnzipTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->tdir = 'public/testshops';
        system("rm -rf $this->tdir");
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
}