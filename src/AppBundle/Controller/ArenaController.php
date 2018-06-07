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
use \FOS\RestBundle\View\View as view;


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
			return View::create(['message' => 'Arena not found'], Response::HTTP_NOT_FOUND);
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
		return $this->updateArena($request, true);
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Patch("/arenas/{id}")
	 *
	 * @throws \Exception
	 *
	 * @return arena|form
	 */
	public function patchArenaAction(Request $request)
	{
		return $this->updateArena($request, false);
	}

	/**
	 * @param Request $request
	 * @param $clearMissing
	 * @return arena|Form
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	private function updateArena(Request $request, $clearMissing)
	{
		/* @var $arena Arena */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($request->get('id'));

		if (empty($arena)) {
			return View::create(['message' => 'Arena not found'], Response::HTTP_NOT_FOUND);
		}

		$form = $this->createForm(ArenaType::class, $arena);

		// Le paramètre false dit à Symfony de garder les valeurs dans notre
		// entité si l'utilisateur n'en fournit pas une dans sa requête
		$form->submit($request->request->all(), $clearMissing);

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($arena);
			$em->flush();
			return $arena;
		} else {
			return $form;
		}
	}
}