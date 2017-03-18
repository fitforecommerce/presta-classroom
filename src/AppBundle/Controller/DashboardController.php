<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
	 * @Route("/", name="homepage")
     * @Route("/dashboard")
     */
    public function numberAction()
    {
        $number = mt_rand(0, 100);

        return $this->render(
			'dashboard/index.html.twig', 
			array('number' => $number)
		);
    }
}
?>