<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
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
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->eventDispatcher->dispatch(Events::USER_REGISTERED, new UserEvent($user));
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
           
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
    public function registrationConfirm(Request $request)
    {
        $user = $this->dbService->findOneByCriteria(User::class, ["token" => $request->get("token")]);
        
        if ($user instanceof User)
        {
            $user->setIsActive(true);
            $user->setToken(null);
            $this->dbService->saveData([$user]);
            
            $this->eventDispatcher->dispatch(Events::EMAIL_CONFIRMED);
            
            return $this->redirectToRoute("security_login");
        }
    }

}