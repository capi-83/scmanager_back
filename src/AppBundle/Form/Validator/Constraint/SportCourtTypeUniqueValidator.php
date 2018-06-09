<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 09/06/18
 * Time: 18:10
 */

namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class SportCourtTypeUniqueValidator
 * @package AppBundle\Form\Validator\Constraint
 */
class SportCourtTypeUniqueValidator  extends ConstraintValidator
{
	public function validate($sportCourt, Constraint $constraint)
	{
		if (!($sportCourt instanceof \Doctrine\Common\Collections\ArrayCollection)) {
			return;
		}

		$pricesType = [];

		foreach ($sportCourt as $sc) {
			if (in_array($sc->getType(), $pricesType)) {
				$this->context->buildViolation($constraint->message)
					->addViolation();
				return; // Si il y a un doublon, on arrête la recherche
			} else {
				// Sauvegarde des types de prix déjà présents
				$pricesType[] = $sc->getType();
			}
		}
	}

}