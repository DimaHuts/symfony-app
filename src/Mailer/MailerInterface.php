<?php
namespace App\Mailer;

use Symfony\Component\Security\Core\User\UserInterface;

interface MailerInterface
{
    public function sendEmailMessage($renderedTemplate, $toEmail);
}