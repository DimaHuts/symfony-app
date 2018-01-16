<?php
namespace App\Mailer;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer implements MailerInterface
{
    
    private $mailer;
    private $templating;
    private $router;
    
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating, UrlGeneratorInterface $router)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
    }
    
    public function sendResettingEmailMessage(UserInterface $user)
    {}

    public function sendConfirmationEmailMessage(UserInterface $user)
    {
        $url = $this->router->generate('registration-confirm', ['token' => $user->getToken()], UrlGeneratorInterface::ABSOLUTE_URL);
        $rendered = $this->templating->render('emails/registration.html.twig', array(
            'user' => $user,
            'confirmationUrl' => $url,
        ));
        $this->sendEmailMessage($rendered, "dmitry.huts@gmail.com", (string) $user->getEmail());
    }
    
    private function sendEmailMessage($renderedTemplate, $fromEmail, $toEmail)
    {
//         $renderedLines = explode("\n", trim($renderedTemplate));
//         $subject = array_shift($renderedLines);
//         $body = implode("\n", $renderedLines);
        
        $message = (new \Swift_Message())
        ->setSubject("Registration Confirm")
        ->setFrom($fromEmail)
        ->setTo($toEmail)
        ->setBody($renderedTemplate);
        
        $this->mailer->send($message);
    }

}

