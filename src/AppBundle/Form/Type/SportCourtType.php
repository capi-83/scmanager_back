<?php
/**
 * SportCourtType
 * User: thomas
 * Date: 08/06/18
 * Time: 12:51
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SportCourtType
 * @package AppBundle\Form\Type
 */
class SportCourtType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// Pas besoin de rajouter les options avec ChoiceType vu que nous allons l'utiliser via API.
		// Le formulaire ne sera jamais affiché
		$builder->add('type');
		$builder->add('nbrSeats');
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'AppBundle\Entity\SportCourt',
			'csrf_protection' => false
		]);
	}
}