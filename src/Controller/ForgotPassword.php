<?php

namespace App\Controller;


use App\Factory\Email\ForgotPasswordTemplate;
use App\Entity\User;
use App\Events;
use App\Form\ForgotPassword\ChangePasswordType;
use App\Form\ForgotPassword\RequestType;
use App\Mailer\Mailer;
use App\Service\DbService;
use App\Validator\User\UserValidator;
use App\Events\UserEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPassword extends AbstractController
{

    private $eventDispatcher;
    private $dbService;
    private $userValidator;

    public function __construct(EventDispatcherInterface $eventDispatcher,
                                DbService $dbService,
                                UserValidator $userValidator)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->dbService = $dbService;
        $this->userValidator = $userValidator;
    }

    /**
     * @Route("/request", name="forgot-password_request")
     */
    public function request(Request $request, Mailer $mailer, ForgotPasswordTemplate $forgotPasswordTemplate)
    {
        if ($request->isMethod('POST'))
        {
            $email = $request->request->get('request')['email'];

            $userExisted = $this->dbService->findOneByCriteria(User::class, ['email' => $email]);
            if ($this->userValidator->isExistedUser($userExisted))
            {
                $this->eventDispatcher->dispatch(Events::SET_TOKEN, new UserEvent($userExisted));
                $mailer->sendEmailMessage($forgotPasswordTemplate->getRenderedTemplate($userExisted->getToken()), $email);

                $this->dbService->saveData([$userExisted]);
                $this->eventDispatcher->dispatch(Events::PASSWORD_FORGOT_REQUEST, new Event());
            }
        }

        return $this->render('forgot-password/request.html.twig', [
            'form' => $this->createForm(RequestType::class, new User())->createView()
        ]);
    }

    /**
     * @Route("/change-password/{token}", name="forgot-password_change")
     */
    public function changePassword(Request $request, User $existedUser)
    {
        $form = $this->createForm(ChangePasswordType::class, $existedUser);
        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('forgot-password/change-password.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->eventDispatcher->dispatch(Events::PASSWORD_ENCODE, new UserEvent($existedUser));
        $this->eventDispatcher->dispatch(Events::PASSWORD_CHANGED_SUCCESS, new UserEvent($existedUser));

        $this->dbService->saveData([$existedUser]);

        return $this->redirectToRoute('homepage');
    }

}