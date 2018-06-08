<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 08/06/18
 * Time: 12:56
 */

namespace AppBundle\Controller\Arena;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;
use \FOS\RestBundle\View\View as view;

use AppBundle\Form\Type\SportCourtType;
use AppBundle\Entity\SportCourt;

/**
 * Class SportCourtController
 * @package AppBundle\Controller\Arena
 */
class SportCourtController extends Controller
{
	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View(serializerGroups={"sportcourt"})
	 * @Rest\Get("/arenas/{id}/sportcourts")
	 *
	 * @return array
	 */
	public function getSportCourtAction(Request $request)
	{
		/* @var $arena Arena */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($request->get('id')); // L'identifiant en tant que paramétre n'est plus nécessaire

		if (empty($arena)) {
			return $this->sportCourtNotFound();
		}

		return $arena->getSportCourts();
	}

	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED,serializerGroups={"sportcourt"})
	 * @Rest\Post("/arenas/{id}/sportcourts")
	 *
	 * @throws \Exception
	 *
	 * @return array|form
	 */
	public function postSportCourtAction(Request $request)
	{

		/* @var $arena Place */
		$arena = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:Arena')
			->find($request->get('id'));

		if (empty($arena)) {
			return $this->placeNotFound();
		}

		$sportCourt = new SportCourt();
		$sportCourt->setArena($arena);

		$form = $this->createForm(SportCourtType::class, $sportCourt);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($sportCourt);
			$em->flush();
			return $sportCourt;
		} else {
			return $form;
		}
	}

	private function sportCourtNotFound()
	{
		return View::create(['message' => 'Sport court not found'], Response::HTTP_NOT_FOUND);
	}
}