<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends DataOutputController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

		$this->setErrors(Response::HTTP_UNPROCESSABLE_ENTITY,'Unprocessable entity');
		return $this->invalidResponse(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
