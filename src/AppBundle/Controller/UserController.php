<?php
/**
 * User controller.
 *
 * User: thomas
 * Date: 07/06/18
 * Time: 21:49
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\Controller\Annotations as Rest;

use AppBundle\Form\Type\UserType;
use AppBundle\Entity\User;

/**
 * Class UserController
 * @package AppBundle\Controller
 */
class UserController extends DataOutputController
{
	/**
	 *
	 * @param $request Request
	 *
	 * @Rest\View( serializerGroups={"userall"})
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
		$users = new User($users);

		$this->setData($users->getFormatedData());
		return $this->output();
	}


	/**
	 * @param $request Request
	 *
	 * @Rest\View(serializerGroups={"userall"})
	 * @Rest\Get("/users/{id}")
	 *
	 * @return array
	 */
	public function getUserAction(Request $request)
	{

		/* @var $user User */
		$user = $this->get('doctrine.orm.entity_manager')
			->getRepository('AppBundle:User')
			->find($request->get('id'));

		if (empty($user)) {
			$this->userNotFound();
		}

		return $this->output($user);
	}



	/**
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
	 * @Rest\Post("/users")
	 *
	 * @throws \Exception
	 *
	 * @return user|form
	 */
	public function postUsersAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);

		$form->submit($request->request->all());

		if ($form->isValid()) {
			// le mot de passe en claire est encodé avant la sauvegarde
			$encoder = $this->get('security.password_encoder');
			$encoded = $encoder->encodePassword($user, $user->getPlainPassword());
			$user->setPassword($encoded);
			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($user);
			$em->flush();
			return $this->output($user);
		} else {
			return $this->output($form);
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
		return $this->output();
	}

	/**
	 * @param $request Request
	 *
	 * @Rest\View(serializerGroups={"user"})
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
	 * @Rest\View(serializerGroups={"user"})
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
			return $this->userNotFound();
		}

		if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
			$options = ['validation_groups'=>['Default', 'FullUpdate']];
		} else {
			$options = []; // Le groupe de validation par défaut de Symfony est Default
		}

		$form = $this->createForm(UserType::class, $user, $options);

		$form->submit($request->request->all(), $clearMissing);

		if ($form->isValid()) {
			// Si l'utilisateur veut changer son mot de passe
			if (!empty($user->getPlainPassword())) {
				$encoder = $this->get('security.password_encoder');
				$encoded = $encoder->encodePassword($user, $user->getPlainPassword());
				$user->setPassword($encoded);
			}

			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($user);
			$em->flush();
			return $this->output($user);
		} else {
			return $this->output($form);
		}
	}

	private function userNotFound()
	{
		throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('User not found');
	}
}
