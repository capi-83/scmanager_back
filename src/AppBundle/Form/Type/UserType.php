<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/06/18
 * Time: 23:57
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

/**
 * Class UserType
 * @package AppBundle\Form\Type
 */
class UserType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('firstname');
		$builder->add('lastname');
		$builder->add('email', EmailType::class);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'AppBundle\Entity\User',
			'csrf_protection' => false
		]);
	}
}