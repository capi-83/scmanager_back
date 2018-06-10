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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;


use AppBundle\Form\Type\ArenaType;
use AppBundle\Entity\Arena;

/**
 * Class ArenaController
 * @package AppBundle\Controller
 */
class ArenaController extends  DataOutputController
{
	/**
	 * @param $request Request
	 * @Rest\View(serializerGroups={"arena"})
	 * @Rest\Get("/arenas/{id}")
	 *
	 * @return arena
	 */
	public function getArenaAction( Request $request)
	{
		/* @var $place Place */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($request->get('id'));

		if (empty($arena)) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Arena not found');
		}

		return $this->output($arena);
	}


	/**
	 *
	 * @param $request Request
	 * @param $paramFetcher ParamFetcher
	 *
	 *
	 * @Rest\View(serializerGroups={"arena"})
	 * @Rest\Get("/arenas")
	 * @QueryParam(name="offset", requirements="\d+", default="", description="Index de début de la pagination")
	 * @QueryParam(name="limit", requirements="\d+", default="", description="nbr de résultat")
	 * @QueryParam(name="sort", requirements="(asc|desc)", nullable=true, description="Ordre de tri (basé sur le nom)")
	 *
	 * @return array arena
	 */
	public function getArenasAction(Request $request, ParamFetcher $paramFetcher)
	{
		$offset = $paramFetcher->get('offset');
		$limit = $paramFetcher->get('limit');
		$sort = $paramFetcher->get('sort');

		$qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();

		$qb->select('a')
			->from('AppBundle:Arena', 'a');

		if ($offset != "") {
			$qb->setFirstResult($offset);
		}

		if ($limit != "") {
			$qb->setMaxResults($limit);
		}

		if (in_array($sort, ['asc', 'desc'])) {
			$qb->orderBy('a.name', $sort);
		}

		$arenas = $qb->getQuery()->getResult();

		return $this->output($arenas);
	}

	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"arena"})
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
			foreach ($arena->getSportCourts() as $sportCourt) {
				$sportCourt->setArena($arena);
				$em->persist($sportCourt);
			}
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
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT,serializerGroups={"arena"})
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

		if(! $arena) return;
		else {
			foreach ($arena->getSportCourts() as $sportCourt) {
				$em->remove($sportCourt);
			}
			$em->remove($arena);
			$em->flush();
		}
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View(serializerGroups={"arena"})
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
	 * @Rest\View(serializerGroups={"arena"})
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
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Arena not found');
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