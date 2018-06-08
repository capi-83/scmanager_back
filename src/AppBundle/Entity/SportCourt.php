<?php
/**
 *
 * Sport Court entity
 *
 * User: thomas
 * Date: 08/06/18
 * Time: 08:55
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sportCourt",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="sportCourt_type_arena_unique", columns={"type", "arena_id"})}
 * )
 */
class SportCourt
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $type;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $nbrSeats;

	/**
	 * @ORM\ManyToOne(targetEntity="Arena", inversedBy="arenas")
	 * @var Arena
	 */
	protected $arena;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * @return mixed
	 */
	public function getNbrSeats()
	{
		return $this->nbrSeats;
	}

	/**
	 * @param mixed $nbrSeats
	 */
	public function setNbrSeats($nbrSeats)
	{
		$this->nbrSeats = $nbrSeats;
	}

	/**
	 * @return Place
	 */
	public function getArena()
	{
		return $this->arena;
	}

	/**
	 * @param Place $arena
	 */
	public function setArena($arena)
	{
		$this->arena = $arena;
	}


}