<?php
/**
 * Classe format de la sortie en json-api
 * User: thomas
 * Date: 10/06/18
 * Time: 17:43
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DataOutputController extends Controller
{
	private $meta = array();
	private $data;
	private $errors;
	private $included;

	protected function output(){

		$meta		= $this->getMeta();
		$data		= $this->getData();
		$errors		= $this->getErrors();
		$included 	= $this->getIncluded();

		$meta["version"]	= "0.1";
		$meta["deliveredAt"]= new \DateTime('now');

		$result = [ 'meta' => $meta];


		if($data) $result['data'] = $data;

		if($errors) $result['errors'] = $errors;

		if($included) $result['included'] = $included;

		return $result;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @param mixed $errors
	 */
	public function setErrors($errors)
	{
		$this->errors = $errors;
	}

	/**
	 * @return mixed
	 */
	public function getMeta()
	{
		return $this->meta;
	}

	/**
	 * @param mixed $meta
	 */
	public function setMeta($meta)
	{
		$this->meta = $meta;
	}

	/**
	 * @return mixed
	 */
	public function getIncluded()
	{
		return $this->included;
	}

	/**
	 * @param mixed $include
	 */
	public function setIncluded($include)
	{
		$this->included = $include;
	}



}