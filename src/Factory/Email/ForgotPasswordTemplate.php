<?php

namespace App\Factory\Email;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordTemplate
{
    private $templating;
    private $router;

    public function __construct(\Twig_Environment $templating, UrlGeneratorInterface $router)
    {
        $this->templating = $templating;
        $this->router = $router;
    }

    public function getRenderedTemplate(string $token): string
    {
        return $this->templating->render('emails/change-password.html.twig', [
            'confirmationUrl' => $this->router->generate(
                'forgot-password_change', ['token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }
}