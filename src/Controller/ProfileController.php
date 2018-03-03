<?php

namespace App\Controller;


use App\Entity\User;
use App\Events;
use App\Events\UploadImageEvent;
use App\Form\ProfileType;
use App\Service\DbServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ProfileController extends Controller
{

    private $dbService;
    private $eventDispatcher;

    /**
     * ProfileController constructor.
     * @param $dbService
     */
    public function __construct(DbServiceInterface $dbService, EventDispatcherInterface $eventDispatcher)
    {
        $this->dbService = $dbService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/profile", name="user-profile")
     * @Method("GET")
     */
    public function profile()
    {
        return $this->render('profile/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/profile/edit/{id}", name="profile-edit")
     */
    public function profileEdit(Request $request, User $user)
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('profile/profile_edit.html.twig', [
                'form' => $form->createView()
            ]);
        }

        $this->eventDispatcher->dispatch(Events::ADD_IMAGE, new UploadImageEvent($user));
        $this->eventDispatcher->dispatch(Events::PROFILE_UPDATED, new Event());

        $this->dbService->saveData([$user]);

        return $this->redirectToRoute('user-profile');
    }


}