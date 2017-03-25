<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\InstallerConfigType;

class InstallerController extends Controller
{

    /**
	 * @Route("/install")
	 * @Route("/install/configure")
     */
    public function configure()
    {
        $f = $this->createForm(InstallerConfigType::class);
		# $fv = $f->buildView();
        return $this->render(
			'install/configure.html.twig', 
			array('form' => $f->createView())
		);
    }
}
?>