<?php

namespace App\Factory\Email;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationTemplate
{
    private $templating;
    private $router;

    public function __construct(\Twig_Environment $templating, UrlGeneratorInterface $router)
    {
        $this->templating = $templating;
        $this->router = $router;
    }

    private function getToken()
    {

    }

    public function getRenderedTemplate(string $token): string
    {
        return $this->templating->render('emails/registration.html.twig', [
            'confirmationUrl' => $this->router->generate(
                'registration-confirm', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}