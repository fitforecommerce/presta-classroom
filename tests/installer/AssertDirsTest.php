<?php

require_once(dirname(__FILE__).'/../../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class AssertDirsTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->tdir = 'public/testshops';
        system("rm -rf $this->tdir");
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
}