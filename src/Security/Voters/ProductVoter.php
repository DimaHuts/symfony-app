<?php

namespace App\Security\Voters;


use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{

    private const EDIT = 'edit';
    private const DELETE = 'delete';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject): bool
    {
        return $subject instanceof Product and in_array($attribute, [self::EDIT, self::DELETE], true);
    }

    protected function voteOnAttribute($attribute, $product, TokenInterface $token)
    {
       if ($this->decisionManager->decide($token, ['ROLE_ADMIN']))
       {
           return true;
       }

       $user = $token->getUser();

       if (!$user instanceof User)
       {
           return false;
       }

       return $user == $product->getUser();
    }
}