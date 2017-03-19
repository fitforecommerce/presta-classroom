<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Model\Downloader;

class DownloadController extends Controller
{
	const VOID 		= 0;
	const SUCCESS 	= 1;
	const ERROR		= 2;

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
     * @Route("/download/{version}")
	 */
	public function download($version)
	{
		$stat = $this->downloader()->status()['code'];
		
		return $this->render(
			'download/download.html.twig',
			array(
				'status_code' => $stat,
				'status_cssclass' => $this->css_class_for_code($stat),
				'status_message' => $this->downloader()->status()['msg'],
				'version' => $version
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
			DownloadController::VOID => 'alert-warning',
			DownloadController::SUCCESS => 'alert-success',
			DownloadController::ERROR => 'alert-danger'
		);
	}
}
?>