<?php
namespace App\Controller;

use App\Form\PrestaClassroomConfigurationType;
use App\Service\PrestaClassroomConfiguration;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppConfigurationController extends Controller
{
	use \App\Traits\PrestaClassroomConfigurationTrait;

  public function configure(Request $request)
  {
    $f = $this->createForm(
      PrestaClassroomConfigurationType::class,
      $this->presta_classroom_config()
    );

    return $this->render(
      'install/configure.html.twig', 
      array('form' => $f->createView())
    );
  }
  public function execute($config)
  {
    # $config->setPrestaSourceDir($this->getParameter('default_shops_dir'));
    # 
    # $installer = $this->get('app.installer');
    # $installer->set_config($config);
    # $installer->run();

    return $this->render(
      'configure/execute.html.twig',
      array('status' => $installer->status())
    );
  }
  private function default_shops_dir()
  {
    $path = $this->presta_classroom_config()->get('default_shops_dir');
    return realpath($path);
  }
}
?>