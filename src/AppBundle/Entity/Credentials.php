<?php
/**
 * Credentials
 *
 * Cette entité n’a aucune annotation Doctrine, elle permet juste de transporter les information user
 *
 * User: thomas
 * Date: 08/06/18
 * Time: 22:22
 */

namespace AppBundle\Entity;

/**
 * Class Credentials
 * @package AppBundle\Entity
 */
class Credentials
{
	/**
	 * @var login
	 */
	protected $login;

	/**
	 * @var password
	 */
	protected $password;

	/**
	 * @return login
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * @param $login
	 */
	public function setLogin($login)
	{
		$this->login = $login;
	}

	/**
	 * @return password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
}