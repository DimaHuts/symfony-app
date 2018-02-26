<?php
namespace App\Mailer;

class Mailer implements MailerInterface
{
    
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailMessage($renderedTemplate, $toEmail)
    {
        $message = (new \Swift_Message())
            ->setSubject("No Reply")
            ->setFrom("dmitry.huts@gmail.com")
            ->setTo($toEmail)
            ->setBody($renderedTemplate, 'text/html');
        
        $this->mailer->send($message);
    }

}
