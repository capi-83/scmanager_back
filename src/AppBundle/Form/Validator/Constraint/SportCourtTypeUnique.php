<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 09/06/18
 * Time: 18:08
 */

namespace AppBundle\Form\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class SportCourtTypeUnique
 * @package AppBundle\Form\Validator\Constraint
 */
class SportCourtTypeUnique extends Constraint
{
	/**
	 * @var string message
	 */
	public $message = 'A arana cannot contain sport court with same type';
}