<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Utils\Downloader;

class DashboardController extends Controller
{
	private $downloader;
    /**
     * @Route("/dashboard")
     */
    public function showDashboards()
    {
        $dv = $this->downloader()->available_versions();
        return $this->render(
			'dashboard/index.html.twig', 
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