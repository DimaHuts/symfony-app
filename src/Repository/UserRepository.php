<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct($em, $em->getClassMetadata(User::class));
    }

    /**
     * Loads the user for the given email.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The email
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->em->getRepository(User::class)->findOneBy(["email" => $username]);
    }
}
