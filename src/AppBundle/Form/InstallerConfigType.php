<?php
namespace AppBundle\Form;
use AppBundle\Entity\VersionDownload;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\EmailType; 
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class InstallerConfigType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder ->add('server_path', TextareaType::class); 
	}
	public function configureOptions(OptionsResolver $resolver) {
		# $resolver->setDefaults(array( 'data_class' => Post::class,)); 
	}
}	
?>