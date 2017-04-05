<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DownloadController extends Controller
{

    /**
	 * @Route("/download")
     */
    public function showAvailableDownloads()
    {
        $dv = $this->get('app.downloader')->available_versions();
        return $this->render(
			'download/available_versions.html.twig', 
			array('versions' => $dv)
		);
    }
	/**
     * @Route("/download/{version}", name="download_version")
	 */
	public function download($version)
	{
		$this->get('app.downloader')->download($version);
		$stat = $this->get('app.downloader')->status()['code'];
		
		return $this->render(
			'download/download.html.twig',
			array(
				'status' => $this->get('app.downloader')->status(),
				'version' => $version,
				'versions' => $this->get('app.downloader')->available_versions()
			)
		);
	}
}
?>