<?php
/**
 * User controller.
 *
 * User: thomas
 * Date: 07/06/18
 * Time: 21:49
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;
use \FOS\RestBundle\View\View as view;

use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends Controller
{
	/**
	 * @param $id int
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Get("/users/{user_id}")
	 *
	 * @return user
	 */
	public function getUserAction($id, Request $request)
	{

		/* @var $user User */
		$user = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:User')
			->find($id);

		if (empty($user)) {
			return View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
		}

		return $user;
	}

	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Get("/users")
	 *
	 * @return array users
	 */
	public function getUsersAction(Request $request)
	{
		/* @var $users User[] */
		$users = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:User')
			->findAll();

		return $users;
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED)
	 * @Rest\Post("/users")
	 *
	 * @throws \Exception
	 *
	 * @return user|form
	 */
	public function postUsersAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($user);
			$em->flush();
			return $user;
		} else {
			return $form;
		}
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/users/{id}")
	 *
	 * @throws \Exception
	 */
	public function removeUserAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');

		/* @var $user User */
		$user = $em->getRepository('AppBundle:User')
			->find($request->get('id'));

		if ($user) {
			$em->remove($user);
			$em->flush();
		}
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Put("/users/{id}")
	 *
	 * @throws \Exception
	 *
	 * @return user|form
	 */
	public function updateUserAction(Request $request)
	{
		return $this->updateUser($request, true);
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View()
	 * @Rest\Patch("/users/{id}")
	 *
	 * @throws \Exception
	 *
	 * @return user|form
	 */
	public function patchUserAction(Request $request)
	{
		return $this->updateUser($request, false);
	}

	/**
	 * @param Request $request
	 * @param $clearMissing
	 * @return user|form
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	private function updateUser(Request $request, $clearMissing)
	{
		/* @var $user User */
		$user = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:User')
			->find($request->get('id'));

		if (empty($user)) {
			return View::create(['message' => 'Place not found'], Response::HTTP_NOT_FOUND);
		}

		$form = $this->createForm(UserType::class, $user);

		$form->submit($request->request->all(), $clearMissing);

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($user);
			$em->flush();
			return $user;
		} else {
			return $form;
		}
	}
}
