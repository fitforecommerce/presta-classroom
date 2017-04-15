<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\InstallerController;
use AppBundle\Utils\FileHelper;

class InstallerControllerTest extends WebTestCase
{
	private $file_helper;

	protected function setUp()
	    {
	        if (!extension_loaded('mysqli')) {
	            $this->markTestSkipped(
	              'The MySQLi extension is not available.'
	); }
	}

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
	public function testDirExistError()
	{
        $client = static::createClient();
		$dp = $client->getContainer()->getParameter('app.default_shops_dir');
		$this->file_helper()->remove(realpath($dp));
		$this->assertDirectoryNotExists($dp.'/shop1');

		$this->file_helper()->mkdir($dp);
		$this->file_helper()->mkdir($dp.'/shop2');

        $crawler = $client->request('GET', '/install');

		$form = $crawler->selectButton('Submit')->form();
		$form['installer_config[number_of_installations]'] = 4;
		$form['installer_config[server_path]'] = $client->getContainer()->getParameter('app.default_shops_dir');
		$crawler = $client->submit($form);

		$this->assertDirectoryExists($dp.'/shop1');
		$this->assertDirectoryExists($dp.'/shop2');
		$this->assertFileNotExists($dp.'/shop2/index.php');
		$this->assertGreaterThan(
		    0,
		    $crawler->filter('div.alert')->count()
		);
		$this->assertRegExp('/Target dir \'.*?\' already exists./', $client->getResponse()->getContent());
	}
	public function testFullInstallation()
	{
        $client = static::createClient();
		
		$installations = $client->getContainer()->getParameter('app.test_installations');;
		if($installations < 3) {
			error_log("\nWARNING:\ntesting a total of $installations number of installations in InstallerControllerTest.\nYou may want to increase the number of installations in /app/config/config_test.yml for reliable testing!\n");
		}

		$dp = $client->getContainer()->getParameter('app.default_shops_dir');
		$this->file_helper()->remove(realpath($dp));
		$this->assertDirectoryNotExists($dp.'/shop1');

        $crawler = $client->request('GET', '/install');

		$form = $crawler->selectButton('Submit')->form();
		$form['installer_config[number_of_installations]'] = $installations;
		$form['installer_config[server_path]'] = $client->getContainer()->getParameter('app.default_shops_dir');
		$crawler = $client->submit($form);

		# error_log("testFullInstallation: response:\n\n".$client->getResponse()->__toString());
		for ($i=1; $i < ($installations + 1); $i++) { 
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
