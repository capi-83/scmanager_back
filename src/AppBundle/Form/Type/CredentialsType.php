<?php
/**
 * Credentials Type
 *
 * User: thomas
 * Date: 08/06/18
 * Time: 22:26
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CredentialsType
 * @package AppBundle\Form\Type
 */
class CredentialsType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('login');
		$builder->add('password');
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'AppBundle\Entity\Credentials',
			'csrf_protection' => false
		]);
	}
}