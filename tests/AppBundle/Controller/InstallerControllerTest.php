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
	public function testInstaller()
	{
        $client = static::createClient();
		$dp = $client->getContainer()->getParameter('app.presta_versions_download_dir');
		$td = $this->target_download($dp);

		$this->file_helper()->remove(realpath($dp));

        $crawler = $client->request('GET', '/download/1.7.0.4');
		$this->assertFileExists(realpath($td['zipped_path']));
		$this->assertDirectoryExists(realpath($td['unzipped_path']));
	}
	private function file_helper()
	{
		if($this->file_helper) return $this->file_helper;
		$this->file_helper = new FileHelper();
		return $this->file_helper;
	}
	private function target_download($dp)
	{
		$version = '1.7.0.4';
		return array(
			'version' 			=> $version,
			'zipped_path'		=> $dp.'/'.$version.'.zip',
			'unzipped_path'		=> $dp.'/'.$version.'.unzipped'
		);
	}
	private function remove_dir($file)
	{
		
	}
}
