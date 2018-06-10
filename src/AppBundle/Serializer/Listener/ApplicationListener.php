<?php
/**
 * Application Listener add meta data to serialisation
 * User: thomas
 * Date: 10/06/18
 * Time: 16:02
 */

namespace AppBundle\Serializer\Listener;

use JMS\Serializer\EventDispatcher\Events;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;

/**
 * Class ApplicationListener
 * @package AppBundle\Serializer\Listener
 */
class ApplicationListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return [
			[
				'event' => Events::PRE_SERIALIZE,
				'format' => 'json',
				'method' => 'onPreSerialize',
			]
		];
	}

	public static function onPreSerialize(ObjectEvent $event)
	{
		/*$visitor = $event->getVisitor();
		$data = $event->getObject();
		$date = new \Datetime();

		$meta = [
			"version"		=> "0.1",
			"deliveredAt"	=> $date->format('l jS \of F Y h:i:s A'),
			"nbrResult"		=> count($data)
		];
		$result = ['meta' => $meta, 'data' => $visitor->getResult()];

		// Possibilité de modifier le tableau après sérialisation
		if(!$visitor->hasData("meta"))
			$visitor->setData("data",$result);*/
	}
}