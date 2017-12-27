<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 * @package App\Controller
 */
class ProductController extends AbstractController
{

    /**
     * @Route("/test", name="home")
     *
     * @return Response
     */
    public function index()
    {
        return new Response("Hello Symfony");
    }
}