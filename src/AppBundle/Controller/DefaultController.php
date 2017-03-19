<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	const VOID 		= 0;
	const SUCCESS 	= 1;
	const ERROR		= 2;

    /**
	 * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->redirect('/dashboard');
    }
}
