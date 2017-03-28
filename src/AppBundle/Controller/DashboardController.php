<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Utils\Downloader;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard")
     */
    public function showDashboards()
    {
        $dv = $this->get('app.downloader')->available_versions();
        return $this->render(
			'dashboard/index.html.twig', 
			array('versions' => $dv)
		);
    }
}
?>