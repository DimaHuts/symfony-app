<?php
namespace App\Mailer;

use Symfony\Component\Security\Core\User\UserInterface;

interface MailerInterface
{
    /**
     * Send an email to a user to confirm the account creation.
     */
//    public function sendConfirmationEmailMessage(UserInterface $user);
    
    /**
     * Send an email to a user to confirm the password reset.
     */
//    public function sendResettingEmailMessage(UserInterface $user);
    
}