<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Model\Downloader;

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
			'download/download.html.twig', 
			array('versions' => $dv)
		);
    }
	private function downloader()
	{
		if(isset($this->downloader)) return $this->downloader;
		$this->downloader = new Downloader();
		return $this->downloader;
	}
}
?>