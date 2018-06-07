<?php
/**
 * Arena Controller.
 *
 * User: thomas
 * Date: 07/06/18
 * Time: 21:24
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;


use AppBundle\Form\Type\ArenaType;
use AppBundle\Entity\Arena;

/**
 * Class ArenaController
 * @package AppBundle\Controller
 */
class ArenaController extends Controller
{
	/**
	 * @param $id int
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Get("/arenas/{id}")
	 *
	 * @return arena
	 */
	public function getArenaAction($id, Request $request)
	{
		/* @var $place Place */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($id);

		if (empty($arena)) {
			return new JsonResponse(['message' => 'Arena not found'], Response::HTTP_NOT_FOUND);
		}

		return $arena;
	}


	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Get("/arenas")
	 *
	 * @return array arena
	 */
	public function getArenasAction(Request $request)
	{
		/* @var $arenas Arena[] */
		$arenas = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->findAll();

		return $arenas;
	}

	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED)
	 * @Rest\Post("/arenas")
	 *
	 * @throws \Exception
	 *
	 * @return arena|form
	 */
	public function postArenasAction(Request $request)
	{
		$arena = new Arena();
		$form = $this->createForm(ArenaType::class, $arena);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($arena);
			$em->flush();
			return $arena;
		} else {
			return $form;
		}
	}

	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/arenas/{id}")
	 *
	 * @throws \Exception
	 *
	 */
	public function removePlaceAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');

		/* @var $arena Arena */
		$arena = $em->getRepository('AppBundle:Arena')
			->find($request->get('id'));

		if($arena) {
			$em->remove($arena);
			$em->flush();
		}
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Put("/arenas/{id}")
	 *
	 * @throws \Exception
	 *
	 * @return arena|form
	 */
	public function putArenaAction(Request $request)
	{
		/* @var $arena Arena */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire

		if (empty($arena)) {
			return new JsonResponse(['message' => 'Arena not found'], Response::HTTP_NOT_FOUND);
		}

		$form = $this->createForm(ArenaType::class, $arena);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->merge($arena);
			$em->flush();
			return $arena;
		} else {
			return $form;
		}
	}
}