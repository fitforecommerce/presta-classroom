<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Model\Downloader;

class DashboardController extends Controller
{
	private $downloader;
    /**
     * @Route("/dashboard")
     */
    public function showDashboards()
    {
        $dv = $this->downloader()->downloaded_versions();
        return $this->render(
			'dashboard/index.html.twig', 
			array('downloaded_versions' => $dv)
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