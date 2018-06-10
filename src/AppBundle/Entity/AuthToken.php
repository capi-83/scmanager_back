<?php
/**
 * AuthToken Entity.
 * User: thomas
 * Date: 08/06/18
 * Time: 22:19
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="auth_tokens",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="auth_tokens_value_unique", columns={"value"})}
 * )
 */
class AuthToken
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
	protected $value;

	/**
	 * @ORM\Column(type="datetime")
	 * @var \DateTime
	 */
	protected $createdAt;

	/**
	 * @ORM\ManyToOne(targetEntity="User")
	 * @var User
	 */
	protected $user;


	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}

	public function getUser()
	{
		return $this->user;
	}

	public function setUser(User $user)
	{
		$this->user = $user;
	}

	public function getFormatedData()
	{
		return [
				'type'=>'authtoken',
				'id'=>$this->getId(),
				'attributes'=>
					[
					'value'		=> $this->getValue(),
					'createdAt'	=> $this->getCreatedAt()
					],
				'relationship' => $this->getRelationship()
				];
	}

	public function getRelationship()
	{
		return [
				'type'	=> 'user',
				'id'	=> $this->getUser()->getId(),
				];
	}

	public function getIncluded()
	{
		return $this->getUser()->getFormatedData();
	}
}