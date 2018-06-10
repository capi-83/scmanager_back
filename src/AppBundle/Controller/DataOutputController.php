<?php
/**
 * Classe mere des controller pour la sortie json.
 * User: thomas
 * Date: 10/06/18
 * Time: 17:43
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DataOutputController extends Controller
{

	protected function output($data = array()){
		$date = new \Datetime();

		$meta = [
			"version"		=> "0.1",
			"deliveredAt"	=> $date->format('Y-m-d H:i:s'),
			"nbrResult"		=> count($data)
		];

		$result = [ 'meta' => $meta,
					'data' => $data];

		return $result;
	}
}