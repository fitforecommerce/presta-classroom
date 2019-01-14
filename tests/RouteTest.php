<?php

error_reporting(E_STRICT);

require_once(dirname(__FILE__).'/../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class RouteTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->defaults = array(
            'controllerClass' => "DownloadController",
            'action' => "download",
            'method' => "GET"
        );
    }
    public function testRouteCreated()
    {
      $r = new Route("/download/{version}", $this->defaults);
      $this->assertInstanceOf(Route::class, $r);
    }
    public function testRequestMatchReturned()
    {
        $r = new Route("/download/{version}", $this->defaults);
        $c = $r->matches_request('/download/1.7.0.3');
        $this->assertTrue($c==1);
    }
    public function testRequestNoMatchReturned()
    {
        $r = new Route("/download", $this->defaults);
        $c = $r->matches_request('/download/1.7.0.3');
        $this->assertTrue($c==0);
    }
    public function testRouteReturnsValues()
    {
        $r = new Route("/download", $this->defaults);
        $c = $r->controllerClass;
        $this->assertTrue(isset($c));
        $this->assertInternalType('string', $c);
    }
}