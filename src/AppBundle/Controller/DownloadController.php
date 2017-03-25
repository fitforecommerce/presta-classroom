<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Utils\Downloader;

class DownloadController extends Controller
{

	private $downloader;

    /**
	 * @Route("/download")
     */
    public function showAvailableDownloads()
    {
        $dv = $this->downloader()->available_versions();
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
		$this->downloader()->download($version);
		$stat = $this->downloader()->status()['code'];
		
		return $this->render(
			'download/download.html.twig',
			array(
				'status_code' => $stat,
				'status_cssclass' => $this->css_class_for_code($stat),
				'status_message' => $this->downloader()->status()['msg'],
				'version' => $version,
				'versions' => $this->downloader()->available_versions()
			)
		);
	}
	private function downloader()
	{
		if(isset($this->downloader)) return $this->downloader;
		$this->downloader = new Downloader();
		return $this->downloader;
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