<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DownloadControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/download');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Downloadable Versions', $crawler->filter('h1')->text());
    }
}
