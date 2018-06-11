<?php
/**
 * Classe format de la sortie en json-api
 * User: thomas
 * Date: 10/06/18
 * Time: 17:43
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use \FOS\RestBundle\View\View as view;

class DataOutputController extends Controller
{
	private $meta = array();
	private $data;
	private $errors;
	private $included;

	/**
	 * @return array
	 */
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
	 * @param int $response
	 * @return view
	 */
	protected function invalidResponse( $response = Response::HTTP_BAD_REQUEST )
	{
		return View::create($this->output(),$response );
	}

	/**
	 * @param $form
	 * @return mixed
	 */
	protected function formatFormErrors($form)
	{
		$res = [];
		var_dump(count($form));
		foreach ($form->getErrors() as $fkey => $fval)
		{
			//print_r($f);
		}
		return $form->getErrors();
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
	 * @param $status int
	 * @param $message string
	 * @param mixed $errors
	 */
	public function setErrors($status ,$message, $errors = array())
	{
		$result = [
					'status'		=> $status,
					'message'		=> $message
				];

		if($errors) $result['informations']	= $errors;

		$this->errors = $result;
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