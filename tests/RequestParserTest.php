<?php

error_reporting(E_STRICT);

require_once(dirname(__FILE__).'/../lib/include.inc.php');

use PHPUnit\Framework\TestCase;
# use PrestaShop\Module\PrestaCollege\VersionChecker;

final class RequestParserTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    public function testVersionCheckerCreated()
    {
        $r = new RequestParser("/download/1.7.0.3", "/download/{version}");
        $this->route_defaults = array(
            'controllerClass' => "DownloadController",
            'action' => "download",
            'method' => "GET"
        );
        $this->assertInstanceOf(RequestParser::class, $r);
    }
    public function testParamsArrayReturned()
    {
        $r = new RequestParser("/download/1.7.0.3", new Route("/download/{version}", $this->route_defaults));
        $p = $r->params();
        $this->assertInternalType('array', $p);
    }
    public function testParamsKeyReturned()
    {
        $r = new RequestParser("/download/1.7.0.3", new Route("/download/{version}", $this->route_defaults));
        $p = $r->params();
        $this->assertArrayHasKey('version', $p);
    }
    public function testSingleParamsValueReturned()
    {
        $r = new RequestParser("/download/1.7.0.3", new Route("/download/{version}", $this->route_defaults));
        $p = $r->params();
        $this->assertInternalType('string', $p['version']);
        $this->assertEquals('1.7.0.3', $p['version']);
    }
    public function testMultipleParamsValueReturned()
    {
        $r = new RequestParser("/download/1.7.0.3/user/485123", new Route("/download/{version}/user/{userid}", $this->route_defaults));
        $p = $r->params();
        $this->assertInternalType('string', $p['version']);
        $this->assertInternalType('string', $p['userid']);
        $this->assertEquals('1.7.0.3', $p['version']);
        $this->assertEquals('485123', $p['userid']);
    }
    public function testMultipleSequentialParamsValueReturned()
    {
        $r = new RequestParser(
            "/download/1.7.0.3/485123/classroom/48abc94",
            new Route("/download/{version}/{userid}/classroom/{classroomid}", $this->route_defaults)
        );
        $p = $r->params();
        $this->assertInternalType('string', $p['version']);
        $this->assertInternalType('string', $p['userid']);
        $this->assertInternalType('string', $p['classroomid']);
        $this->assertEquals('1.7.0.3', $p['version']);
        $this->assertEquals('485123', $p['userid']);
        $this->assertEquals('48abc94', $p['classroomid']);
    }
}