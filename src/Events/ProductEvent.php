<?php
namespace App\Events;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Product;
use App\Entity\User;

class ProductEvent extends Event
{
    
    private $user;
    private $product;
    
    public function __construct(User $user, Product $product)
    {
        $this->user = $user;
        $this->product = $product;
    }
    
    public function getUser(): User
    {
        return $this->user;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }
    
}

