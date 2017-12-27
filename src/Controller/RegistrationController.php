<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends Controller
{
    /**
     * @Route("register", name="register")
     */
    public function registration()
    {
        return $this->render('register/register.html.twig');
    }

}