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
			return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
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
		$user = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:User')
			->find($request->get('id')); // L'identifiant en tant que paramètre n'est plus nécessaire
		/* @var $user User */

		if (empty($user)) {
			return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
		}

		$form = $this->createForm(UserType::class, $user);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			$em = $this->get('doctrine.orm.entity_manager');
			$em->merge($user);
			$em->flush();
			return $user;
		} else {
			return $form;
		}
	}
}
