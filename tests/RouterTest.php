<?php

require_once(dirname(__FILE__).'/../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class RouterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testVersionCheckerCreated()
    {
      $r = new Router("/login");
      $this->assertInstanceOf(Router::class, $r);
    }
    public function testLoginControllerReturned()
    {
        $r = new Router("/login/validate");
        $c = $r->controller();
        $this->assertInstanceOf(LoginController::class, $c);
    }
    public function testActionStringReturned()
    {
        $r = new Router("/login/validate");
        $a = $r->action();
        $this->assertInternalType('string', $a);
        $this->assertEquals('validate', $a);
        $r = new Router("/download");
        $a = $r->action();
        $this->assertInternalType('string', $a);
        $this->assertEquals('index', $a);
    }
    public function testParamsArrayReturned()
    {
        $r = new Router("/download/1.7.0.3");
        $p = $r->params();
        $this->assertInternalType('array', $p);
    }
    public function testParamsKeyReturned()
    {
        $r = new Router("/download/1.7.0.3");
        $p = $r->params();
        $this->assertArrayHasKey('version', $p);
    }
    public function testParamsValueReturned()
    {
        $r = new Router("/download/1.7.0.3");
        $p = $r->params();
        $this->assertInternalType('string', $p['version']);
        $this->assertEquals('1.7.0.3', $p['version']);
    }
}