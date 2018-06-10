<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 10/06/18
 * Time: 16:52
 */

namespace AppBundle\Serializer\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;

/**
 * Class ApplicationHandler
 * @package AppBundle\Serializer\Handler
 */
class ApplicationHandler implements SubscribingHandlerInterface
{
	public static function getSubscribingMethods()
	{
		return [
			[
				'direction'	=> GraphNavigator::DIRECTION_SERIALIZATION,
				'format'	=> 'json',
				'type' 		=>"",
				'method' => 'serialize',
			]
		];
	}

	public function serialize(JsonSerializationVisitor $visitor, $data, array $type, Context $context)
	{
		$date = new \Datetime();

		$meta = [
			"version"		=> "0.1",
			"deliveredAt"	=> $date->format('l jS \of F Y h:i:s A'),
			"nbrResult"		=> count($data)
		];

		$result = [	"meta"=>$meta,
					"data"=>$data];

		return $visitor->visitArray($result, $type, $context);
	}
}