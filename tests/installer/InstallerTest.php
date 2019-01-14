<?php

require_once(dirname(__FILE__).'/../../lib/include.inc.php');

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
}