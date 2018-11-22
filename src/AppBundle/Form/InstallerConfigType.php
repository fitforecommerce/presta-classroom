<?php
namespace AppBundle\Form;
use AppBundle\Entity\VersionDownload;
use AppBundle\Entity\InstallerConfig;
use AppBundle\Utils\Downloader;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstallerConfigType extends AbstractType {

	public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('number_of_installations', new Assert\GreaterThan(0));
	}
	public function buildForm(FormBuilderInterface $builder, array $options) {
    $this->downloader = $options['downloader'];

		$builder->add(
			'presta_version', 
			ChoiceType::class, 
			array('choices'  => $this->versions_choice())
		)
			->add('number_of_installations', IntegerType::class, array('data' => 1))
			->add('server_path', TextType::class)
			->add('overwrite_targets', CheckboxType::class, array(
				'data' => false, 
				'label' => 'Overwrite any existing target directories',
				'required' => false
				)
			)
			->add('web_root_url', TextType::class, array('data' => 'http://localhost'))
			->add('submit', SubmitType::class);
	}
	public function configureOptions(OptionsResolver $resolver)
	{
	    $resolver->setDefaults(array(
	        'data_class' => InstallerConfig::class,
	    ));
      $resolver->setRequired('downloader');
	}
	private function versions_choice()
	{
		$rv = [];
		$av = $this->downloader->available_versions();
		foreach ($av as $k => $v) {
			$rv[$v->version()] = $v->version();
		}
		$rv = array_reverse($rv);
		return $rv;
	}
}	
?>