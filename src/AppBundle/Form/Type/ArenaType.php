<?php
/**
 * Arena Type
 *
 * User: thomas
 * Date: 07/06/18
 * Time: 23:36
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArenaType
 * @package AppBundle\Form\Type
 */
class ArenaType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name');
		$builder->add('address');
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'AppBundle\Entity\Arena',
			'csrf_protection' => false
		]);
	}
}