<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\InstallerController;
use AppBundle\Utils\FileHelper;

class InstallerControllerTest extends WebTestCase
{
	private $file_helper;

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/install');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Install options', $crawler->filter('h1')->text());
    }
	public function testConfigurationForm()
	{
        $client = static::createClient();
        $crawler = $client->request('GET', '/install');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertCount(1, $crawler->filter('#installer_config_presta_version'));
	}
	public function testInstallation()
	{
        $client = static::createClient();
		$dp = $client->getContainer()->getParameter('app.default_shops_dir');
		$this->file_helper()->remove(realpath($dp));
		$this->assertDirectoryNotExists($dp.'/shop1');

        $crawler = $client->request('GET', '/install');

		$form = $crawler->selectButton('Submit')->form();
		$form['installer_config[number_of_installations]'] = 4;
		$form['installer_config[server_path]'] = $client->getContainer()->getParameter('app.default_shops_dir');
		$client->submit($form);

		for ($i=1; $i < 5; $i++) { 
			$this->assertDirectoryExists($dp.'/shop'.$i);
			$this->assertFileExists($dp.'/shop'.$i.'/index.php');
		}
	}


	private function file_helper()
	{
		if($this->file_helper) return $this->file_helper;
		$this->file_helper = new FileHelper();
		return $this->file_helper;
	}
}
