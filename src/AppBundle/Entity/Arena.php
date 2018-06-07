<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 07/06/18
 * Time: 21:22
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="arenas",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="arenas_name_unique",columns={"name"})}
 *     )
 */
class Arena
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * Name of the arena
	 * @var $name
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * Adress of the arena
	 * @var $address
	 * @ORM\Column(type="string")
	 */
	protected $address;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}
}