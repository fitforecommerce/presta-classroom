<?php
namespace AppBundle\Controller;

use AppBundle\Form\InstallerConfigType;
use AppBundle\Entity\InstallerConfig;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InstallerController extends Controller
{

    /**
	 * @Route("/install")
     */
    public function configure(Request $request)
    {
		$conf = new InstallerConfig();

    $f = $this->createForm(InstallerConfigType::class, $conf, array(
      'downloader' => $this->get('app.downloader')
    ));
    try {
      $f->get('server_path')->setData($this->default_server_path());  
    } catch (\Exception $e) {
      return $this->render('install/ioexception.html.twig', array(
          'message' => $e->getMessage().'<p>Please check your access rights for folder <code>'.$this->getParameter('app.default_shops_dir').'</code>',
          'type' => 'alert-warning'
        )
      );
    }

	    $f->handleRequest($request);

		if ($f->isSubmitted() && $f->isValid()) {
			return $this->execute($f->getData());
		}

		return $this->render(
			'install/configure.html.twig', 
			array('form' => $f->createView())
		);
    }

    /**
	 * @Route("/runinstall")
     */
	public function execute($config)
	{
		$config->setPrestaSourceDir($this->getParameter('app.presta_versions_download_dir'));

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