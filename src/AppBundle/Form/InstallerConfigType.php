<?php
namespace AppBundle\Form;
use AppBundle\Entity\VersionDownload;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InstallerConfigType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add(
			'presta_version', 
			ChoiceType::class, 
			array('choices'  => $this->versions_choice())
		);

		$builder->add('number_of_installations', IntegerType::class);

		$builder->add('server_path', TextType::class);
		$builder->add('submit', SubmitType::class);
	}
	public function configureOptions(OptionsResolver $resolver) {
		# $resolver->setDefaults(array( 'data_class' => Post::class,)); 
	}
	private function versions_choice()
	{
		$rv = [];
		$av = VersionDownload::available_versions();
		foreach ($av as $k => $v) {
			$rv[$v['version']] = $v['version'];
		}
		return $rv;
	}
}	
?>