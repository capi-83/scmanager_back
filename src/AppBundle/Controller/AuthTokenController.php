<?php
/**
 * AuthenToken Controller
 *
 * User: thomas
 * Date: 08/06/18
 * Time: 22:35
 */

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\Annotations as Rest;
use \FOS\RestBundle\View\View as view;

use AppBundle\Form\Type\CredentialsType;
use AppBundle\Entity\AuthToken;
use AppBundle\Entity\Credentials;


/**
 * Class AuthTokenController
 * @package AppBundle\Controller
 */
class AuthTokenController extends  DataOutputController
{
	/**
	 * @param $request Request
	 *
	 * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"auth-token"})
	 * @Rest\Post("/auth-tokens")
	 *
	 * @throws \Exception
	 *
	 * @return array users
	 */
	public function postAuthTokensAction(Request $request)
	{
		$credentials = new Credentials();
		$form = $this->createForm(CredentialsType::class, $credentials);

		$form->submit($request->request->all());

		if (!$form->isValid()) {
			$this->setErrors(['status'=>Response::HTTP_UNPROCESSABLE_ENTITY,'message' => 'Unprocessable entity']);
			return $this->output();
		}

		$em = $this->get('doctrine.orm.entity_manager');

		$user = $em->getRepository('AppBundle:User')
			->findOneByEmail($credentials->getLogin());

		if (!$user) { // L'utilisateur n'existe pas
			$this->setErrors(['status'=>Response::HTTP_BAD_REQUEST,'message' => 'Invalid credentials']);
			return $this->invalidCredentials($this->output());
		}

		$encoder = $this->get('security.password_encoder');
		$isPasswordValid = $encoder->isPasswordValid($user, $credentials->getPassword());

		if (!$isPasswordValid) {
			$this->setErrors(['status'=>Response::HTTP_BAD_REQUEST,'message' => 'Invalid credentials']);
			return $this->invalidCredentials($this->output());
		}

		$authToken = new AuthToken();
		$authToken->setValue(base64_encode(random_bytes(50)));
		$authToken->setCreatedAt(new \DateTime('now'));
		$authToken->setUser($user);

		$em->persist($authToken);
		$em->flush();

		$this->setIncluded($authToken->getIncluded());
		$this->setData($authToken->getFormatedData());

		return $this->output();
	}

	/**
	 * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
	 * @Rest\Delete("/auth-tokens/{id}")
	 */
	public function removeAuthTokenAction(Request $request)
	{
		$em = $this->get('doctrine.orm.entity_manager');
		$authToken = $em->getRepository('AppBundle:AuthToken')
			->find($request->get('id'));
		/* @var $authToken AuthToken */

		$connectedUser = $this->get('security.token_storage')->getToken()->getUser();

		if ($authToken && $authToken->getUser()->getId() === $connectedUser->getId()) {
			$em->remove($authToken);
			$em->flush();
		} else {
			$this->setErrors($this->invalidCredentials());
			return $this->output();
		}
	}

	private function invalidCredentials($output)
	{
		return View::create([$output], Response::HTTP_BAD_REQUEST);
	}
}