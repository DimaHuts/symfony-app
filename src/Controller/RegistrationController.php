<?php

namespace App\Controller;


use App\Entity\User;
use App\Factory\Email\RegistrationTemplate;
use App\Form\UserType;
use App\Mailer\Mailer;
use App\Validator\User\UserValidator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Events;
use App\Events\UserEvent;
use App\Service\DbService;

class RegistrationController extends Controller
{

    private $eventDispatcher;
    private $dbService;

    /**
     * RegistrationController constructor.
     * @param $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, DbService $dbService)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->dbService = $dbService;
    }

    /**
     * @Route("register", name="register")
     * @Method({"GET", "POST"})
     */
    public function registration(Request $request, Mailer $mailer, RegistrationTemplate $registrationTemplate)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->eventDispatcher->dispatch(Events::PASSWORD_ENCODE, new UserEvent($user));
            $this->eventDispatcher->dispatch(Events::SET_TOKEN, new UserEvent($user));

            $mailer->sendEmailMessage($registrationTemplate->getRenderedTemplate($user->getToken()), (string)$user->getEmail());

            $this->eventDispatcher->dispatch(Events::USER_REGISTERED, new UserEvent($user));
            $this->dbService->saveData([$user]);
           
            return $this->redirectToRoute("security_login");
        }

        return $this->render(
            'register/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("confirm/{token}", name="registration-confirm")
     */
    public function registrationConfirm(Request $request, UserValidator $userValidator, User $user)
    {
        if ($userValidator->isExistedUser($user))
        {
            $this->eventDispatcher->dispatch(Events::EMAIL_CONFIRMED, new UserEvent($user));

            $this->dbService->saveData([$user]);
            
            return $this->redirectToRoute("security_login");
        }
    }

}