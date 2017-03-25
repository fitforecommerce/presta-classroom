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
				'status_code' => $stat,
				'status_cssclass' => $this->css_class_for_code($stat),
				'status_message' => $this->get('app.downloader')->status()['msg'],
				'version' => $version,
				'versions' => $this->get('app.downloader')->available_versions()
			)
		);
	}
	private function css_class_for_code($stat)
	{
		return $this->css_status_codes()[$stat];
	}
	private function css_status_codes()
	{
		return array(
			DefaultController::VOID => 'alert-warning',
			DefaultController::SUCCESS => 'alert-success',
			DefaultController::ERROR => 'alert-danger'
		);
	}
}
?>