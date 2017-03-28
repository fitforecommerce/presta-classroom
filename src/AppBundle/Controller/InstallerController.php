<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\InstallerConfigType;
use Symfony\Component\HttpFoundation\Request;

class InstallerController extends Controller
{

    /**
	 * @Route("/install")
     */
    public function configure(Request $request)
    {
        $f = $this->createForm(InstallerConfigType::class);
	    $f->handleRequest($request);
		
		if ($f->isSubmitted() && $f->isValid()) {
			$task = $f->getData();
			return $this->execute($request);
		}

        return $this->render(
			'install/configure.html.twig', 
			array('form' => $f->createView())
		);
    }

    /**
	 * @Route("/runinstall")
     */
	public function execute(Request $request)
	{
		$installer = $this->get('app.installer');
		$installer->set_config($request);
		$installer->run();

		return $this->render(
			'install/execute.html.twig',
			array('status' => $installer->status())
		);
	}
}
?>