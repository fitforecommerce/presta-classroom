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
		$f->get('server_path')->setData($this->default_server_path());

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
		$config = $request->get('installer_config');
		$config['presta_source_dir'] = $this->getParameter('app.presta_versions_download_dir');

		$installer = $this->get('app.installer');
		$installer->set_config($config);
		$installer->run();

		return $this->render(
			'install/execute.html.twig',
			array('status' => $installer->status())
		);
	}
	private function default_server_path()
	{
		$path = $this->getParameter('app.default_shops_dir');
		$fh = $this->get('app.filehelper');
		$fh->assert_dir_exists($path);
		return realpath($path);
	}
}
?>